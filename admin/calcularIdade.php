<?php
	//incluir o arquivo da funcao
	include "config/funcoes.php";

	$datanascimento = "";
	if ( isset ( $_GET["datanascimento"] ) )
		$datanascimento = trim ( $_GET["datanascimento"] );

	//verificar se o cpf esta em branco
	if ( empty ( $datanascimento ) ) {
		echo "Forneça uma Data";
		exit;
	} else if ( $datanascimento == "00/00/0000" ) {
		echo "Data inválida";
		exit;
	}

	//executar a função
	echo calcularIdade($datanascimento);