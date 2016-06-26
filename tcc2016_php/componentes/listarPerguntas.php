<?php include_once 'ferramentas/ConexaoBD.php';

$login = previneSQLInjection($_REQUEST['login']);
$tipoAvaliador = previneSQLInjection($_REQUEST['avaliador']);
$tipoAvaliado = previneSQLInjection($_REQUEST['avaliado']);

$sucesso = 0;
$mensagem = "";

$query = "SELECT `id` FROM `usus_perguntas` WHERE `usu_avaliador` = '".$tipoAvaliador."' 
		  AND `usu_avaliado` = '".$tipoAvaliado."' AND `published` = 1";

$result = $conexao->query($query);

if($result){
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	if(count($result)>0){
		$sucesso = 1;
		$usu_pergunta = $result[0]['id'];
		
	}else{
		$mensagem = "Erro na Listagem das Questões";
	}
}else{
	$mensagem = "Erro na Listagem das Questões";
}

if($sucesso == 1){
	$query = "SELECT pe.`id`, pe.`texto_pergunta`, pe.`qtde_opcoes`, tp.descricao, pe.tem_sim_e_nao 
				FROM `perguntas` pe LEFT JOIN tipo_perguntas tp 
				ON tp.id = pe.`tipo_pergunta` 
				WHERE pe.`usus_pergunta` = ".$usu_pergunta." AND pe.`published` = 1 AND tp.`published` = 1";
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
		<id_pergunta valor="<?php echo $result[$i]['id'];?>"/>
		<texto_pergunta valor="<?php echo $result[$i]['texto_pergunta'];?>"/>
		<opcoes valor="<?php echo $result[$i]['qtde_opcoes'];?>"/>
		<sim_nao valor="<?php echo $result[$i]['tem_sim_e_nao'];?>"/>
		<tipo_pergunta valor="<?php echo $result[$i]['descricao'];?>"/>
	</pergunta>
	<?php }
	}?>
</xml>