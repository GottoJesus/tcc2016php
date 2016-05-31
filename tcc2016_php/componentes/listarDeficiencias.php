<?php include_once 'ferramentas/ConexaoBD.php';

$query = "SELECT `nome`,`resumo_definicao` FROM `deficiencias` WHERE `published` = 1";
$sucesso = 0;
$mensagem = "";

$result = $conexao->query($query);

if($result){
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<xml>
<?php for($i = 0; $i < count($result); $i++){?>
	<deficiencia>
		<descricao valor="<?php echo $result[$i]['nome'];?>"/>
		<resumo valor="<?php echo $result[$i]['resumo_definicao'];?>"/>
	</deficiencia>
<?php }
}?>
</xml>