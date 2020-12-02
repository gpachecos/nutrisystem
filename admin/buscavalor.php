<?php

	$produto = "";
	if ( isset ( $_GET["produto"] ) ) $produto = trim ( $_GET["produto"] );

	if ( !empty ( $produto ) ) {

		include "config/conexao.php";

		//selecionar o valor do quadrinho
		$sql = "select valor from quadrinho where id = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1, $produto);
		$consulta->execute();

		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		if ( !empty ( $dados->valor ) ) {
			echo number_format($dados->valor,2,",",".");
		}

	}