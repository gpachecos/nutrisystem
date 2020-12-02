<?php

	//inciar variaveis
	$cidade = $estado = "";

	//recuperar as viariaveis
	if ( isset ( $_GET["cidade"] ) )
		$cidade = trim ( $_GET["cidade"] );
	if ( isset ( $_GET["estado"] ) )
		$estado = trim ( $_GET["estado"] );

	//incluir conexao com banco
	include "config/conexao.php";

	//sql para buscar a cidade
	$sql = "select idcidade from cidade 
		where cidade = ? and estado = ?
		limit 1";
	$consulta = $pdo->prepare( $sql );
	$consulta->bindParam(1, $cidade);
	$consulta->bindParam(2, $estado);
	$consulta->execute();
	//recuperar os dados
	$dados = $consulta->fetch(PDO::FETCH_OBJ);

	//verifica se trouxe resultado
	if ( empty ( $dados->idcidade ) ) echo "Erro";
	else echo $dados->idcidade;

