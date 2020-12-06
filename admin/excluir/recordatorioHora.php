<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";


	//verificar se esta sendo enviado o id
	if ( isset ( $p[2] ) ) {

		$idrecordatoriohora = (int)$p[2];
		/* 
		echo "<pre>";
		var_dump($id);
		var_dump($p);
		*/

		//verificar se existe um exame com este tipo
		// $sql = "select * from recordatoriohora 
		// 	where idrecordatoriohora = ? limit 1";
		// $consulta = $pdo->prepare( $sql );
		// $consulta->bindParam(1,$idexame);
		// $consulta->execute();
		
        // $dados = $consulta->fetch(PDO::FETCH_OBJ);

        // $arqexame 	= $dados->arqexame;
        //  var_dump($idrecordatoriohora);
        //  exit;
        

		// //verificar se trouxe algum id
		// if ( isset ( $dados->id ) ) {
		// 	$msg = "Este registro não pode ser excluído pois existe um quadrinho relacionado";
		// 	mensagem( $msg );
		// }

		//excluir a editora
		$sql = "delete from recordatoriohora where idrecordatoriohora = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$idrecordatoriohora);

		//verificar se o registro foi excluido
		if ( $consulta->execute() ) {

            //excluindo o arquivo na rede
            //unlink("../arquivosExame/".$arqexame);

			$msg = "Exame excluído com sucesso";
			mensagem ( $msg );
		} else {
			$msg = "Erro ao excluir item de recordatório";
			mensagem( $msg );
		}

	} else {

		$msg = "Ocorreu um erro ao excluir";
		mensagem( $msg );

	}