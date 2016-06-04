<?php 
$conexao = new PDO("mysql:host=localhost;dbname=db_tcc2016;charset=utf8", "root", "");

function previneSQLInjection($string){
	
	$string = str_ireplace("SELECT", "", $string);
	$string = str_ireplace("INSERT", "", $string);
	$string = str_ireplace("UPDATE", "", $string);
	$string = str_ireplace("DELETE", "", $string);
	$string = str_ireplace(" AND ", "", $string);
	$string = str_ireplace(" OR ", "", $string);
	$string = str_ireplace("CREATE", "", $string);
	$string = str_ireplace("DROP", "", $string);
	$string = str_ireplace("TABLE", "", $string);
	$string = str_ireplace(" INTO ", "", $string);
	$string = str_ireplace("WHERE", "", $string);
	$string = addslashes($string);
	$string = strip_tags($string);
	$string = trim($string);
	
	return $string;
}


?>