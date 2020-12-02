<?php

	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	if ( $_POST ) {
		

		foreach ($_POST as $key => $value) {
			$$key = trim ( $value );

			//echo "<p>$key $value</p>";
		}

		if ( empty( $cliente_id ) ) {
			echo "<script>alert('Selecione um cliente');history.back();</script>";
			exit;
		}


		//formatar a data
		$data = formataData( $data );

		//verificar se irá inserir ou atualizar
		if ( empty ( $id ) ) {
			//inserir
			$sql = "insert into venda (id, cliente_id, usuario_id, data, status)
				values
				(NULL, :cliente_id, :usuario_id, :data, :status)";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindValue(":cliente_id",$cliente_id);
			$consulta->bindValue(":usuario_id",$usuario_id);
			$consulta->bindValue(":data",$data);
			$consulta->bindValue(":status",$status);


		} else {
			//atualizar
			$sql = "update venda set cliente_id = :cliente_id, status = :status, data = :data 
				where id = :id limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindValue(":cliente_id",$cliente_id);
			$consulta->bindValue(":data",$data);
			$consulta->bindValue(":status",$status);
			$consulta->bindValue(":id",$id);

		}

		//executar o sql
		if ( $consulta->execute() ) {

			if ( empty ( $id ) ) $id = $pdo->lastInsertId();

			//redirecionar a tela para a venda
			echo "<script>location.href='cadastros/venda/$id';</script>";

		} else {

			echo "<script>alert('Erro ao cadastrar');history.back();</script>";
			exit;

		}

	} else {

		echo "Requisição inválida";

	}