<?php
include_once 'ferramentas/ConexaoBD.php';
$login = previneSQLInjection($_REQUEST['login']);
$senha = previneSQLInjection($_REQUEST['senha']);
$nomeUsuario = "";
$tipoUsuario = "";


$sucesso = 0;
$mensagem = "";
$senhaHash = password_hash($senha,PASSWORD_BCRYPT);

$query =  "SELECT * FROM `usuario` WHERE `login` = '".$login."'";
$result = $conexao->query($query);


if($result){
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	$psw = $result[0]['senha'];
	if($senhaHash != $psw){
		$mensagem = "Usuário inválido";
	}else{
		$sucesso = 0;
		$mensagem = "Usuário válido";
	}
}else{
	$mensagem = "Usuário inválido";
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