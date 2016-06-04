<?php include 'ferramentas/ConexaoBD.php';

$login = previneSQLInjection($_REQUEST['login']);
$senha = previneSQLInjection($_REQUEST['senha']);
$tipoUsuario = previneSQLInjection($_REQUEST['tipoUsuario']);
$nomeUsuario = previneSQLInjection($_REQUEST['nomeUsuario']);
$email = previneSQLInjection($_REQUEST['email']);

$sucesso = 0;
$mensagem = "";

$senhaHash = password_hash($senha,PASSWORD_BCRYPT);

$query = "INSERT INTO `usuario`(`login`, `senha`, `nome_usuario`, `email`) 
		  VALUES ('".$login."','".$senhaHash."','".$nomeUsuario."','".$email."')";

if($conexao->query($query)){
	$id_usuario = $conexao->lastInsertId();
	
	switch ($tipoUsuario) {
		case "responsavel":
			cadastraResponsavel($id_usuario,$conexao);
		break;
		
		case "instituicao":
			cadastraInstituicao($id_usuario,$conexao);
		break;
		
		case "professor":
			cadastraProfessor($id_usuario,$conexao);
		break;
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
			      VALUES (".$id.",'".$cpf."','".$dataNascResp."',".$ddd.",".$telefone.",'".$id."')";
		$conexao->query($query);
		$id_resp = $conexao->lastInsertId();
		
		$query = "INSERT INTO `alunos`(`nome_aluno`, `data_nasc`, `cpf_aluno`, `rg_aluno`, `cert_nasc_aluno`) 
				  VALUES ('".$nomeAluno."','".$dataNascAluno."','".$cpfAluno."','".$rgAluno."','".$certNascAluno."')";
		$conexao->query($query);
		$id_aluno = $conexao->lastInsertId();
		
		$query = "SELECT `id` FROM `deficiencias` WHERE `nome` = '".$deficienciaAluno."'";
		$result = $conexao->query($query);
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		$id_deficiencia = $result[0]['id'];
		
		
		$query = "SELECT `id` FROM `usuarios_instituicao` WHERE `cnpj` = '".$cnpjInstituicao."'";
		$result = $conexao->query($query);
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		$id_instituicao = $result[0]['id'];
		
		
		$query = "INSERT INTO `alunos_deficiencias`(`id_aluno`, `id_deficiencia`) 
				  VALUES (".$id_aluno.",".$id_deficiencia.")";
		$conexao->query($query);
		
		$query = "INSERT INTO `relacao_responsaveis_alunos`(`id_aluno`, `id_responsavel`) 
				  VALUES (".$id_aluno.",".$id_resp.")";
		$conexao->query($query);
		
		$query = "INSERT INTO `relacao_alunos_instituicao`(`id_aluno`, `id_instituicao`) 
				  VALUES (".$id_aluno.",".$id_instituicao.")";
		$conexao->query($query);
		
		$sucesso = 1;
		$mensagem = "Usuario cadastrado com sucesso!!";
		
	}catch(Exception $e){
		$mensagem = "Erro no cadastro do Responsável";
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
		
		$sucesso = 1;
		$mensagem = "Usuario cadastrado com sucesso!!";
		
	} catch (Exception $e){
		$mensagem = "Erro no cadastro da Instituição";
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
		
		$query = "SELECT `id` FROM `usuarios_instituicao` WHERE `cnpj` = '".$cnpjInstituicao."'";
		$result = $conexao->query($query);
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		$id_instituicao = $result[0]['id'];
		
		$query = "INSERT INTO `relacao_instituicao_professores`(`id_instituicao`, `id_professor`) 
				  VALUES (".$id_instituicao.",".$id_prof.")";
		$conexao->query($query);
		
		$sucesso = 1;
		$mensagem = "Usuario cadastrado com sucesso!!";
		
	} catch (Exception $e) {
		$mensagem = "Erro no cadastro do Professor";
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