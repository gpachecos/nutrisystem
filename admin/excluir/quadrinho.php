<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";


	//verificar se esta sendo enviado o id
	if ( isset ( $p[2] ) ) {

		$id = (int)$p[2];
		/* 
		echo "<pre>";
		var_dump($id);
		var_dump($p);
		*/

		//verificar se existe um quadrinho com este tipo
		$sql = "select id from venda_quadrinho 
			where quadrinho_id = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$id);
		$consulta->execute();
		
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		//verificar se trouxe algum id
		if ( isset ( $dados->id ) ) {
			$msg = "Este registro não pode ser excluído pois existe uma venda relacionada";
			mensagem( $msg );
		}

		//excluir o quadrinho
		$sql = "delete from quadrinho where id = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$id);

		//verificar se o registro foi excluido
		if ( $consulta->execute() ) {
			$msg = "Registro excluído com sucesso";
			mensagem ( $msg );
		} else {
			$msg = "Erro ao excluir registro";
			mensagem( $msg );
		}

	} else {

		$msg = "Ocorreu um erro ao excluir";
		mensagem( $msg );

	}