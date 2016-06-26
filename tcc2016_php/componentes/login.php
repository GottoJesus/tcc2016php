<?php
include_once 'ferramentas/ConexaoBD.php';

$login = previneSQLInjection($_REQUEST['login']);
$senha = previneSQLInjection($_REQUEST['senha']);
$nomeUsuario = "";
$tipoUsuario = "";

$sucesso = 0;
$mensagem = "";

$query = "SELECT * FROM `usuario` WHERE `login` = '".$login."'";
$result = $conexao->query($query);

if($result){
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($result)>0){
		$psw = $result[0]['senha'];
		if(!password_verify($senha, $psw)){
			$mensagem = "Erro no Login do Usuário\n  - Senha Inválida";
		}else{
			$sucesso = 1;
			$mensagem = "Usuário válido";
			$nomeUsuario = $result[0]['nome_usuario'];
		
			$id = $result[0]['id'];
		
			// verificar qual o tipo do usuario
			
			$tipoUsuario = "";
			
			$query = "SELECT * FROM `usuarios_instituicao` WHERE `id_usuario` = ".$id."";
			$result = $conexao->query($query);
		
			if($result){
				$result = $result->fetchAll(PDO::FETCH_ASSOC);
				if(count($result)>0){
					$tipoUsuario = 'instituicao';
				}
			}
			if($tipoUsuario == ""){
				$query = "SELECT * FROM `usuarios_professores` WHERE `id_usuario` = ".$id."";
				$result = $conexao->query($query);
				if($result){
					$result = $result->fetchAll(PDO::FETCH_ASSOC);
					if(count($result)>0){
						$tipoUsuario = 'professor';
					}
				}
			}
			
			if($tipoUsuario == ""){
				$query = "SELECT * FROM `usuarios_responsaveis` WHERE `id_usuario` = ".$id."";
				$result = $conexao->query($query);
				if($result){
					$result = $result->fetchAll(PDO::FETCH_ASSOC);
					if(count($result)>0){
						$tipoUsuario = 'responsavel';
					}
				}
			}
				
		}
	}else{
		$mensagem = "Erro no Login do Usuário\n  - Usuário não existe";
	}
	
}else{
	$mensagem = "Erro no Login do Usuário\n  - Usuário não existe";
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