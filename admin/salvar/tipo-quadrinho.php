<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//verificar se os dados foram enviados
	if ( $_POST ) {

		//recuperar os dados digitados
		if ( isset ( $_POST["id"] ) ) {
			$id = trim ( $_POST["id"] );
		}
		if ( isset ( $_POST["tipo"] ) ) {
			$tipo = trim ( $_POST["tipo"] );
		}

		//validar para ver se não existe nenhum tipo de quadrinho com o nome que será inserido
		if ( empty ( $id ) ) {

			$sql = "select id from tipo where tipo = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$tipo);

		} else {

			$sql = "select id from tipo where tipo = ? and id <> ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$tipo);
			$consulta->bindParam(2,$id);

		}

		//executar o sql
		$consulta->execute();
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		//verificar se o dados trouxe algum resultado
		if ( isset ( $dados->id ) ) {
			$msg = "Já existe um $tipo cadastrado na sua base de dados";
			mensagem($msg);
		}

		//se o id estiver vazio - insert
		//se não estiver vazio - update
		if ( empty ( $id ) ) {
			//inserir
			$sql = "insert into tipo (tipo) 
				values ( ? )";
			
			//instanciar o sql na conexao (pdo) e preparar o sql para ser executado
			$consulta = $pdo->prepare( $sql );
			
			//passar o parametro $tipo
			$consulta->bindParam(1, $tipo);

		} else {
			//atualizar
			$sql = "update tipo set tipo = ? 
				where id = ? limit 1";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1, $tipo);
			$consulta->bindParam(2, $id);

		}

		//verificar se o comando será executado corretamente
		if ( $consulta->execute() ) {

			$msg = "Registro inserido com sucesso!";
			$link = "listar/tipo-quadrinho";
			sucesso($msg, $link);

		} else {

			$msg = "Erro ao inserir/atualizar registro!";
			mensagem($msg);

		}

	} else {
		//erro 
		$msg = "Erro ao efetuar requisição";
		mensagem($msg);
	}