<?php include 'ferramentas/ConexaoBD.php';

$login = previneSQLInjection($_REQUEST['login']);
$senha = previneSQLInjection($_REQUEST['senha']);
$tipoUsuario = previneSQLInjection($_REQUEST['tipoUsuario']);
$nomeUsuario = previneSQLInjection($_REQUEST['nomeUsuario']);
$email = previneSQLInjection($_REQUEST['email']);

$sucesso = 0;
$mensagem = "";

$senhaHash = password_hash($senha,PASSWORD_BCRYPT);

$query = "INSERT INTO `usuario`(`login`, `senha`, `nome_usuario`, `email`, `data_cadastro`, `data_modificacao`, `published`) 
		  VALUES ('".$login."','".$senhaHash."','".$nomeUsuario."','".$email."', NOW(), NOW(), 1)";

if($conexao->query($query)){
	$id_usuario = $conexao->lastInsertId();
	
	switch ($tipoUsuario) {
		case "responsavel":
			$cadastro = cadastraResponsavel($id_usuario,$conexao);
		break;
		
		case "instituicao":
			$cadastro = cadastraInstituicao($id_usuario,$conexao);
		break;
		
		case "professor":
			$cadastro = cadastraProfessor($id_usuario,$conexao);
		break;
	}
	
	if($cadastro){
		$sucesso = 1;
		$mensagem = "Cadastro efetuado com sucesso";
	}else{
		$mensagem = "Erro no cadastro do Usuário";
	}
	
}else{
	$mensagem = "Erro no cadastro do Usuário";
}


function cadastraResponsavel($id,$conexao){
	$cpf = previneSQLInjection($_REQUEST['cpf']);
	$dataNascResp = previneSQLInjection($_REQUEST['dataNascResp']);
	$ddd = previneSQLInjection($_REQUEST['ddd']);
	$telefone = previneSQLInjection($_REQUEST['telefone']);
	$grauParent = previneSQLInjection($_REQUEST['grauParent']);
	
	$nomeAluno = previneSQLInjection($_REQUEST['nomeAluno']);
	$dataNascAluno = previneSQLInjection($_REQUEST['dataNascAluno']);
	$cpfAluno = previneSQLInjection($_REQUEST['cpfAluno']);
	$rgAluno = previneSQLInjection($_REQUEST['rgAluno']);
	$grauParent = previneSQLInjection($_REQUEST['grauParent']);
	$certNascAluno = previneSQLInjection($_REQUEST['certNascAluno']);
	$deficienciaAluno = previneSQLInjection($_REQUEST['deficienciaAluno']);
	$cnpjInstituicao = previneSQLInjection($_REQUEST['cnpjInstituicao']);

	try {
		$query = "INSERT INTO `usuarios_responsaveis`(`id_usuario`, `cpf_resp`, `data_nasc`, `ddd_resp`, `tel_resp`, `grau_parentesco`)
			      VALUES (".$id.",'".$cpf."','".$dataNascResp."',".$ddd.",".$telefone.",'".$grauParent."')";
		$conexao->query($query);
		$id_resp = $conexao->lastInsertId();
		
		$query = "INSERT INTO `alunos`(`nome_aluno`, `data_nasc`, `cpf_aluno`, `rg_aluno`, `cert_nasc_aluno`, `data_cadastro`, `data_modificacao`, `published`) 
				  VALUES ('".$nomeAluno."','".$dataNascAluno."','".$cpfAluno."','".$rgAluno."','".$certNascAluno."', NOW(), NOW(), 1)";
		$conexao->query($query);
		$id_aluno = $conexao->lastInsertId();
		
		$query = "SELECT `id` FROM `deficiencias` WHERE `nome` = '".$deficienciaAluno."'";
		$result = $conexao->query($query);
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		$id_deficiencia = $result[0]['id'];
		
		$id_instituicao = getIdInstituicao($cnpjInstituicao, $conexao);
		
		$query = "INSERT INTO `alunos_deficiencias`(`id_aluno`, `id_deficiencia`, `data_cadastro`, `data_modificacao`, `published`) 
				  VALUES (".$id_aluno.",".$id_deficiencia.", NOW(), NOW(), 1)";
		$conexao->query($query);
		
		$query = "INSERT INTO `relacao_responsaveis_alunos`(`id_aluno`, `id_responsavel`, `data_cadastro`, `data_modificacao`, `published`) 
				  VALUES (".$id_aluno.",".$id_resp.", NOW(), NOW(), 1)";
		$conexao->query($query);
		
		$query = "INSERT INTO `relacao_alunos_instituicao`(`id_aluno`, `id_instituicao`, `data_cadastro`, `data_modificacao`, `published`) 
				  VALUES (".$id_aluno.",".$id_instituicao.", NOW(), NOW(), 1)";
		$conexao->query($query);
		
		return true;
	}catch(Exception $e){
		return false;
	}
}

function cadastraInstituicao($id,$conexao){
	$cnpj = previneSQLInjection($_REQUEST['cnpj']);
	$ddd = previneSQLInjection($_REQUEST['ddd']);
	$telefone = previneSQLInjection($_REQUEST['telefone']);
	$cep = previneSQLInjection($_REQUEST['cep']);
	$rua = previneSQLInjection($_REQUEST['rua']);
	$numero = previneSQLInjection($_REQUEST['numero']);
	$bairro = previneSQLInjection($_REQUEST['bairro']);
	$complemento = previneSQLInjection($_REQUEST['complemento']);
	$cidade = previneSQLInjection($_REQUEST['cidade']);
	$estado = previneSQLInjection($_REQUEST['estado']);
	$pais = previneSQLInjection($_REQUEST['pais']);
	
	try {
		$query = "INSERT INTO `usuarios_instituicao`(`id_usuario`, `cnpj`, `ddd_inst`, `tel_inst`, `cep_inst`, `rua_inst`, 
				`numero_inst`, `bairro_inst`, `complemento_inst`, `cidade_inst`, `uf_inst`, `pais_inst`) 
				  VALUES (".$id.",'".$cnpj."',".$ddd.",".$telefone.",".$cep.",'".$rua."',
				  		  ".$numero.",'".$bairro."','".$complemento."','".$cidade."','".$estado."','".$pais."')";
		$conexao->query($query);
		
		return true;
		
	} catch (Exception $e){
		return false;
	}
}

function cadastraProfessor($id,$conexao){
	$cpf = previneSQLInjection($_REQUEST['cpf']);
	$ddd = previneSQLInjection($_REQUEST['ddd']);
	$telefone = previneSQLInjection($_REQUEST['telefone']);
	$dataNasc = previneSQLInjection($_REQUEST['dataNasc']);
	$cnpjInst = previneSQLInjection($_REQUEST['cnpjInst']);
	
	try {
		
		$query = "INSERT INTO `usuarios_professores`(`id_usuario`, `cpf_prof`, `data_nasc`) 
				  VALUES (".$id.",'".$cpf."','".$dataNasc."')";
		$conexao->query($query);
		$id_prof = $conexao->lastInsertId();

		$id_instituicao = getIdInstituicao($cnpjInst, $conexao);
		
		$query = "INSERT INTO `relacao_instituicao_professores`(`id_instituicao`, `id_professor`, `data_cadastro`, `data_modificacao`, `published`) 
				  VALUES (".$id_instituicao.",".$id_prof.", NOW(), NOW(), 1)";
		$conexao->query($query);
		
		return true;
		
	} catch (Exception $e) {
		return false;
	}
	
}

function getIdInstituicao($cnpj, $conexao){
	$query = "SELECT `id` FROM `usuarios_instituicao` WHERE `cnpj` = '".$cnpj."'";
	$result = $conexao->query($query);
	if($result){
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]['id'];
	}else{
		return false;
	}
}

?>

<xml>
	<sucesso valor="<?php echo $sucesso;?>"/>
	<mensagem valor="<?php echo $mensagem;?>"/>
	<login valor="<?php echo $login;?>"/>
	<senha valor="<?php echo $senha;?>"/>
	<nomeUsuario valor="<?php echo $nomeUsuario;?>"/>
	<tipoUsuario valor="<?php echo $tipoUsuario;?>"/>
</xml>