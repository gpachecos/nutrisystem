<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//verificar se os dados foram enviados
	if ( $_POST ) {

		foreach ($_POST as $key => $value) {
			$$key = trim ( $value );
		}

		//formatar a datas
		$dataficha = formataData( $dataficha );

		

		//executar o sql
		$consulta->execute();
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		//verificar se o dados trouxe algum resultado
		if ( isset ( $dados->idpessoa ) ) {
			$msg = "Já existe um $nome cadastrado na sua base de dados";
			mensagem($msg);
		}

		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( empty ( $idpessoa ) ) {

			// //criptografar a senha
			// $senha = password_hash($senha, PASSWORD_DEFAULT);

			//insert
			$sql = "insert into pessoa 
			(idpessoa, nome, sexo, datanasc, idade, email, estadocivil, ocupacao, cpf, rg, telefone, cep, endereco, complemento, bairro, idcidade)
			values 
			(NULL, :nome, :sexo, :datanascimento, :idade, :email, :estadocivil, :ocupacao, :cpf, :rg, :telefone, :cep, :endereco, :complemento, :bairro, :idcidade)";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindValue(":nome",$nome);
            $consulta->bindValue(":sexo",$sexo);
            $consulta->bindValue(":datanascimento",$datanascimento);
            $consulta->bindValue(":idade",$idade);
			$consulta->bindValue(":email",$email);
            $consulta->bindValue(":estadocivil",$estadocivil);
            $consulta->bindValue(":ocupacao",$ocupacao);
            $consulta->bindValue(":cpf",$cpf);
			$consulta->bindValue(":rg",$rg);
			$consulta->bindValue(":telefone",$telefone);
            $consulta->bindValue(":cep",$cep);
			$consulta->bindValue(":endereco",$endereco);
			$consulta->bindValue(":complemento",$complemento);
			$consulta->bindValue(":bairro",$bairro);
			$consulta->bindValue(":idcidade",$idcidade);




		// } else if ( empty ( $senha ) ) {
			
		// 	//update
		// 	$sql = "update cliente set nome = :nome,
		// 		cpf = :cpf, datanascimento = :datanascimento, email = :email,
		// 		cep = :cep, endereco = :endereco, complemento = :complemento, bairro = :bairro, 
		// 		cidade_id = :cidade_id, telefone = :telefone, celular = :celular
		// 		where id = :id limit 1";
		// 	$consulta =  $pdo->prepare($sql);
		// 	$consulta->bindValue(":nome",$nome);
		// 	$consulta->bindValue(":cpf",$cpf);
		// 	$consulta->bindValue(":datanascimento",$datanascimento);
		// 	$consulta->bindValue(":email",$email);
		// 	$consulta->bindValue(":cep",$cep);
		// 	$consulta->bindValue(":endereco",$endereco);
		// 	$consulta->bindValue(":complemento",$complemento);
		// 	$consulta->bindValue(":bairro",$bairro);
		// 	$consulta->bindValue(":cidade_id",$cidade_id);
		// 	$consulta->bindValue(":telefone",$telefone);
		// 	$consulta->bindValue(":celular",$celular);
		// 	$consulta->bindValue(":id", $id);

		} else {

			// //criptografar a senha
			// $senha = password_hash($senha, PASSWORD_DEFAULT);

			//update
            $sql = "update pessoa set 
                nome = :nome, sexo = :sexo, datanasc = :datanascimento, idade = :idade, email = :email, 
                estadocivil = :estadocivil, ocupacao = :ocupacao, cpf = :cpf, rg = :rg, telefone = :telefone, 
                cep = :cep, endereco = :endereco, complemento = :complemento, bairro = :bairro, idcidade = :idcidade
				where idpessoa = :idpessoa limit 1";
			$consulta =  $pdo->prepare($sql);
			$consulta->bindValue(":nome",$nome);
            $consulta->bindValue(":sexo",$sexo);
            $consulta->bindValue(":datanascimento",$datanascimento);
            $consulta->bindValue(":idade",$idade);
			$consulta->bindValue(":email",$email);
            $consulta->bindValue(":estadocivil",$estadocivil);
            $consulta->bindValue(":ocupacao",$ocupacao);
            $consulta->bindValue(":cpf",$cpf);
			$consulta->bindValue(":rg",$rg);
			$consulta->bindValue(":telefone",$telefone);
            $consulta->bindValue(":cep",$cep);
			$consulta->bindValue(":endereco",$endereco);
			$consulta->bindValue(":complemento",$complemento);
			$consulta->bindValue(":bairro",$bairro);
            $consulta->bindValue(":idcidade",$idcidade);
            $consulta->bindValue(":idpessoa",$idpessoa);
		}

		//executar
		if ( $consulta->execute() ) {

			// //se a foto não estiver vazio - copiar
			// if ( !empty ( $_FILES["foto"]["name"] ) ) {

			// 	//copiar o arquivo para a pasta

			// 	if ( !copy( $_FILES["foto"]["tmp_name"], 
			// 		"../fotos/".$_FILES["foto"]["name"] )) {

			// 		$msg = "Erro ao copiar foto";
			// 		mensagem( $msg );
			// 	}

			// 	//echo $capa;

			// 	$pastaFotos = "../fotos/";
			// 	$imagem = $_FILES["foto"]["name"];

			// 	redimensionarImagem($pastaFotos,$imagem,$foto);


			// 	if ( !empty ( $id ) ) {
			// 		//update na foto
			// 		$sql = "update cliente set foto = :foto	where id = :id limit 1";
			// 		$consulta =  $pdo->prepare($sql);
			// 		$consulta->bindValue(":foto",$foto);
			// 		$consulta->bindValue(":id",$id);
			// 		$consulta->execute();
			// 	}

			// }
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "listar/pessoa" );

		} else {
			//erro do sql
			echo $consulta->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}