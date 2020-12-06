<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//iniciar as variaveis
	$idfichaanamnese = $idpatologia = "";
	//recuperar as variaveis
	//$p[0] - excluir
	//$p[1] - quadrinho-personagem
	//$p[2] - id do quadrinho
	//$p[3] - id do personagem
	if ( isset ( $p[2] ) )
		$idfichaanamnese = trim( $p[2] );
	if ( isset ( $p[3] ) )
		$idpatologia = trim( $p[3] );

	//verificar se algum esta em branco
	if ( ( empty ( $idfichaanamnese ) ) or ( empty ( $idpatologia ) ) ) {
		mensagem("Erro ao excluir");
	} else {

		$sql = "delete from anamnese_patologia
		where idfichaanamnese = :idfichaanamnese 
		and idpatologia = :idpatologia 
		limit 1";
		$consulta = $pdo->prepare($sql);
		$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
		$consulta->bindValue(":idpatologia",$idpatologia);

		if ( $consulta->execute() ){
			$link = "salvar/patologias.php?idfichaanamnese=$idfichaanamnese";
			sucesso("Registro excluido",$link);
		} else {
			mensagem("Erro ao excluir");
		}
	}