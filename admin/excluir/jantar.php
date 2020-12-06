<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//iniciar as variaveis
	$idfichaanamnese = $idrefeicao = $idcardapio = $idalimento ="";
	//recuperar as variaveis
	//$p[0] - excluir
	//$p[1] - quadrinho-personagem
	//$p[2] - id do quadrinho
    //$p[3] - id do personagem
    
    // echo"<br><br><br>";
    // var_dump($p);

	if ( isset ( $p[2] ) )
		$idfichaanamnese = trim( $p[2] );
	if ( isset ( $p[3] ) )
        $idrefeicao = trim( $p[3] );
    if ( isset ( $p[4] ) )
        $idalimento = trim( $p[4] );
    if ( isset ( $p[5] ) )
		$idcardapio = trim( $p[5] );

	//verificar se algum esta em branco
	if ( ( empty ( $idfichaanamnese ) ) or ( empty ( $idrefeicao ) ) or ( empty ( $idalimento ) ) or ( empty ( $idcardapio ) ) ) {
		mensagem("Erro ao excluir");
	} else {

		$sql = "delete from cardapio
        where idfichaanamnese = :idfichaanamnese
        and idrefeicao =  :idrefeicao
        and idalimento = :idalimento
		and idcardapio = :idcardapio 
		limit 1";
		$consulta = $pdo->prepare($sql);
		$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
        $consulta->bindValue(":idrefeicao",$idrefeicao);
        $consulta->bindValue(":idalimento",$idalimento);
        $consulta->bindValue(":idcardapio",$idcardapio);
        
		if ( $consulta->execute() ){
			$link = "salvar/jantar.php?idfichaanamnese=$idfichaanamnese";
			sucesso("Registro excluido",$link);
		} else {
            // $erro = $consulta->errorInfo();
			// var_dump($consulta->errorInfo());
			mensagem("Erro ao excluir: ". $erro);
		}
	}