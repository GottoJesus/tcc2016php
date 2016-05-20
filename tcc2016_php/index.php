<?php

$operacao = $_REQUEST["operacao"];

switch ($operacao) {
	case "login":
		include_once "componentes/login.php";
	break;
	
	case "cadastro":
		echo "Cadastro_Usuario";
	break;
	
	case "endereco":
		echo "Endereco";
	break;
	
	default:
		;
	break;
}


?>