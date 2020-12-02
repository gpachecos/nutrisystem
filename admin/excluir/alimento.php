<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";


	//verificar se esta sendo enviado o id
	if ( isset ( $p[2] ) ) {

		$idalimento = (int)$p[2];
		/* 
		echo "<pre>";
		var_dump($id);
		var_dump($p);
		*/

		//verificar se existe um quadrinho com este tipo
		$sql = "select idcardapio from cardapio 
			where idalimento = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$idalimento);
		$consulta->execute();
		
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		//verificar se trouxe algum id
		if ( isset ( $dados->idcardapio ) ) {
			$msg = "Este registro não pode ser excluído, pois existe um Cardápio relacionado.";
			mensagem( $msg );
		}

		//excluir o quadrinho
		$sql = "delete from alimento where idalimento = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$idalimento);

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