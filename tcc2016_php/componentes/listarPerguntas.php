<?php include_once 'ferramentas/ConexaoBD.php';

$login = previneSQLInjection($_REQUEST['login']);
$tipoAvaliador = previneSQLInjection($_REQUEST['avaliador']);
$tipoAvaliado = previneSQLInjection($_REQUEST['avaliado']);

$sucesso = 0;
$mensagem = "";

$query = "SELECT `id` FROM `tipo_perguntas` WHERE `usu_avaliador` = '".$tipoAvaliador."' 
		  AND `usu_avaliado` = '".$tipoAvaliado."' AND `published` = 1";

$result = $conexao->query($query);

if($result){
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	if(count($result)>0){
		$sucesso = 1;
		$tipo_pergunta = $result[0]['id'];
		
	}else{
		$mensagem = "Erro na Listagem das Questões";
	}
}else{
	$mensagem = "Erro na Listagem das Questões";
}

if($sucesso == 1){
	$query = "SELECT `texto_pergunta`, `qtde_opcoes` FROM `perguntas` WHERE `tipo_pergunta` = ".$tipo_pergunta." AND `published` = 1";
	$result = $conexao->query($query);
	
	if($result){
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		if(count($result)<0){
			$sucesso = 0;
			$mensagem = "Erro na Listagem das Questões";
		}
	}else{
		$mensagem = "Erro na Listagem das Questões";
	}
}
?>

<xml>
	<sucesso valor="<?php echo $sucesso;?>"/>
	<mensagem valor="<?php echo $mensagem;?>"/>
	<?php if($sucesso == 1){
	for($i = 0; $i < count($result); $i++){?>
	<pergunta>
		<texto_pergunta valor="<?php echo $result[$i]['texto_pergunta'];?>"/>
		<opcoes valor="<?php echo $result[$i]['qtde_opcoes'];?>"/>
	</pergunta>
	<?php }
	}?>
</xml>