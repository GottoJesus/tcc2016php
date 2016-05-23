<?php
$operacao = $_REQUEST["operacao"];

switch ($operacao) {
	case "login":
		include_once "componentes/login.php";
	break;
	
	case "cadastro":
		include_once "componentes/cadastro_usu.php";
	break;
	
	case "listaPerguntas":
		include_once "componentes/lista_perguntas.php";
	break;
	
	case "termosUso":
		include_once "componentes/termosUso.php";
	break;
	
	default:
		;
	break;
}
?>