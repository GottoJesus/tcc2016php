<?php
$operacao = $_REQUEST["operacao"];

switch ($operacao) {
	case "login":
		include_once "componentes/login.php";
	break;
	
	case "cadastro":
		include_once "componentes/cadastro_usu.php";
	break;
	
	case "listarPerguntas":
		include_once "componentes/listarPerguntas.php";
	break;
	
	case "termosUso":
		include_once "componentes/termosUso.php";
	break;
	
	case "listarDeficiencias":
		include_once "componentes/listarDeficiencias.php";
	break;
}
?>