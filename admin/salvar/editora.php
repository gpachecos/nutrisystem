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
		if ( isset ( $_POST["nome"] ) ) {
			$nome = trim ( $_POST["nome"] );
		}
		if ( isset ( $_POST["site"] ) ) {
			$site = trim ( $_POST["site"] );
		}

		//validar para ver se não existe nenhum tipo de quadrinho com o nome que será inserido
		if ( empty ( $id ) ) {

			$sql = "select id from editora where nome = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$nome);

		} else {

			$sql = "select id from editora where nome = ? and id <> ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$nome);
			$consulta->bindParam(2,$id);

		}

		//executar o sql
		$consulta->execute();
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		//verificar se o dados trouxe algum resultado
		if ( isset ( $dados->id ) ) {
			$msg = "Já existe um $nome cadastrado na sua base de dados";
			mensagem($msg);
		}

		//se o id estiver vazio - insert
		//se não estiver vazio - update
		if ( empty ( $id ) ) {
			//inserir
			$sql = "insert into editora (nome,site) 
				values ( ?, ? )";
			
			//instanciar o sql na conexao (pdo) e preparar o sql para ser executado
			$consulta = $pdo->prepare( $sql );
			
			//passar o parametro $nome
			$consulta->bindParam(1, $nome);
			$consulta->bindParam(2, $site);

		} else {
			//atualizar
			$sql = "update editora set nome = ?, site = ? 
				where id = ? limit 1";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1, $nome);
			$consulta->bindParam(2, $site);
			$consulta->bindParam(3, $id);

		}

		//verificar se o comando será executado corretamente
		if ( $consulta->execute() ) {

			$msg = "Registro inserido com sucesso!";
			$link = "listar/editora";
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