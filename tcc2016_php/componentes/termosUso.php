<?php include_once 'ferramentas/ConexaoBD.php';

$query = "SELECT * FROM `termos_uso` WHERE `published` = 1 ORDER BY `id_termo` DESC";
$sucesso = 0;
$mensagem = "";

$result = $conexao->query($query);

if($result){
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	
	$termos = $result[0]['descricao_termo'];
	$sucesso = 1;
	$mensagem = "Consulta realizada com Sucesso.\n\n";
	
	echo $mensagem;
	echo $termos;
	
}else{
	
	$mensagem = "Erro na Consulta no Banco de Dados";
	echo $mensagem;
}

?>