<?php
	//iniciar a sessao
	session_start();

	//validar o login
	include "verificalogin.php";

	//configurar para serem mostrados os erros do php
	ini_set("display_error",1);
	ini_set("display_startup_errors",1);
	error_reporting(E_ALL);

	//incluir a conexao com o banco e as funcoes
	include "config/conexao.php";
	include "config/funcoes.php";

	$venda = $quadrinho = "";

	foreach ($_GET as $key => $value) {
		$$key = trim ( $value );
	}

	//verificar se esta em branco
	if ( ( empty ( $venda ) ) || (empty ( $quadrinho ) ) ) {
		echo "<script>alert('Erro ao excluir');history.back();</script>";
		exit;
	}

	$sql = "delete from venda_quadrinho 
		where venda_id = :venda and quadrinho_id = :quadrinho 
		limit 1";
	$consulta = $pdo->prepare( $sql );
	$consulta->bindValue(":venda", $venda);
	$consulta->bindValue(":quadrinho", $quadrinho);

	if ( $consulta->execute() ) {
		echo "<script>alert('Registro excluído');location.href='salvarQuadrinho.php?venda=$venda';</script>";
		exit;
	}

	echo "<script>alert('Erro ao excluir');location.href='salvarQuadrinho.php?venda=$venda';</script>";
	exit;
