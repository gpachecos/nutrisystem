<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//verificar se os dados foram enviados
	if ( $_POST ) {

		//recuperar os dados digitados
		if ( isset ( $_POST["idcidade"] ) ) {
			$id = trim ( $_POST["idcidade"] );
		}
		if ( isset ( $_POST["cidade"] ) ) {
			$cidade = trim ( $_POST["cidade"] );
		}
		if ( isset ( $_POST["estado"] ) ) {
			$estado = trim ( $_POST["estado"] );
		}

		

		//se o id estiver vazio - insert
		//se não estiver vazio - update
		if ( empty ( $idcidade ) ) {
			//inserir
			$sql = "insert into cidade (cidade,estado) 
				values ( ?, ? )";
			
			//instanciar o sql na conexao (pdo) e preparar o sql para ser executado
			$consulta = $pdo->prepare( $sql );
			
			//passar o parametro $nome
			$consulta->bindParam(1, $cidade);
			$consulta->bindParam(2, $estado);

		} else {
			//atualizar
			$sql = "update cidade set cidade = ?, estado = ? 
				where idcidade = ? limit 1";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1, $cidade);
			$consulta->bindParam(2, $estado);
			$consulta->bindParam(3, $idcidade);

		}

		//verificar se o comando será executado corretamente
		if ( $consulta->execute() ) {

			//$msg = "Registro inserido com sucesso!";
			include "listar/cidade.php";


		} else {

			$msg = "Erro ao inserir/atualizar registro!";
			mensagem($msg);

		}

	} else {
		//erro 
		$msg = "Erro ao efetuar requisição";
		mensagem($msg);
	}