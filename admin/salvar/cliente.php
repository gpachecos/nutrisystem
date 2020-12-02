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

		if ( empty( $cpf ) ) {
			echo "<script>alert('Preencha o CPF');history.back();</script>";
			exit;
		}
		else if ( validaCPF($cpf) != 1 ) {
			echo "<script>alert('CPF inválido');history.back();</script>";
			exit;
		}

		//formatar a datas
		$datanascimento = formataData( $datanascimento );

		//verificar se existe alguem com o mesmo cpf
		if ( empty ( $id ) ) {

			//se existe alguem, qualquer um, com o mesmo cpf
			$sql = "select id, nome from cliente where cpf = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$cpf);

		} else {

			//se existe alguem, menos ele mesmo, com o mesmo cpf
			$sql = "select id, nome from cliente where cpf = ? and id <> ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$cpf);
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

		//adicionar um nome de foto 
		$foto = time();

		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( empty ( $id ) ) {

			//criptografar a senha
			$senha = password_hash($senha, PASSWORD_DEFAULT);

			//insert
			$sql = "insert into cliente 
			(id, nome, cpf, datanascimento, email, senha, cep, endereco, complemento, bairro, cidade_id, foto, telefone, celular)
			values 
			(NULL, :nome, :cpf, :datanascimento, :email, :senha, :cep, :endereco, :complemento, :bairro, :cidade_id, :foto, :telefone, :celular)";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindValue(":nome",$nome);
			$consulta->bindValue(":cpf",$cpf);
			$consulta->bindValue(":datanascimento",$datanascimento);
			$consulta->bindValue(":email",$email);
			$consulta->bindValue(":senha",$senha);
			$consulta->bindValue(":cep",$cep);
			$consulta->bindValue(":endereco",$endereco);
			$consulta->bindValue(":complemento",$complemento);
			$consulta->bindValue(":bairro",$bairro);
			$consulta->bindValue(":cidade_id",$cidade_id);
			$consulta->bindValue(":foto",$foto);
			$consulta->bindValue(":telefone",$telefone);
			$consulta->bindValue(":celular",$celular);

		} else if ( empty ( $senha ) ) {
			
			//update
			$sql = "update cliente set nome = :nome,
				cpf = :cpf, datanascimento = :datanascimento, email = :email,
				cep = :cep, endereco = :endereco, complemento = :complemento, bairro = :bairro, 
				cidade_id = :cidade_id, telefone = :telefone, celular = :celular
				where id = :id limit 1";
			$consulta =  $pdo->prepare($sql);
			$consulta->bindValue(":nome",$nome);
			$consulta->bindValue(":cpf",$cpf);
			$consulta->bindValue(":datanascimento",$datanascimento);
			$consulta->bindValue(":email",$email);
			$consulta->bindValue(":cep",$cep);
			$consulta->bindValue(":endereco",$endereco);
			$consulta->bindValue(":complemento",$complemento);
			$consulta->bindValue(":bairro",$bairro);
			$consulta->bindValue(":cidade_id",$cidade_id);
			$consulta->bindValue(":telefone",$telefone);
			$consulta->bindValue(":celular",$celular);
			$consulta->bindValue(":id", $id);

		} else {

			//criptografar a senha
			$senha = password_hash($senha, PASSWORD_DEFAULT);

			//update
			$sql = "update cliente set nome = :nome,
				cpf = :cpf, datanascimento = :datanascimento, email = :email, senha = :senha, 
				cep = :cep, endereco = :endereco, complemento = :complemento, bairro = :bairro, 
				cidade_id = :cidade_id, telefone = :telefone, celular = :celular
				where id = :id limit 1";
			$consulta =  $pdo->prepare($sql);
			$consulta->bindValue(":nome",$nome);
			$consulta->bindValue(":cpf",$cpf);
			$consulta->bindValue(":datanascimento",$datanascimento);
			$consulta->bindValue(":email",$email);
			$consulta->bindValue(":senha",$senha);
			$consulta->bindValue(":cep",$cep);
			$consulta->bindValue(":endereco",$endereco);
			$consulta->bindValue(":complemento",$complemento);
			$consulta->bindValue(":bairro",$bairro);
			$consulta->bindValue(":cidade_id",$cidade_id);
			$consulta->bindValue(":telefone",$telefone);
			$consulta->bindValue(":celular",$celular);
			$consulta->bindValue(":id", $id);
		}

		//executar
		if ( $consulta->execute() ) {


			//se a foto não estiver vazio - copiar
			if ( !empty ( $_FILES["foto"]["name"] ) ) {

				//copiar o arquivo para a pasta

				if ( !copy( $_FILES["foto"]["tmp_name"], 
					"../fotos/".$_FILES["foto"]["name"] )) {

					$msg = "Erro ao copiar foto";
					mensagem( $msg );
				}

				//echo $capa;

				$pastaFotos = "../fotos/";
				$imagem = $_FILES["foto"]["name"];

				redimensionarImagem($pastaFotos,$imagem,$foto);


				if ( !empty ( $id ) ) {
					//update na foto
					$sql = "update cliente set foto = :foto	where id = :id limit 1";
					$consulta =  $pdo->prepare($sql);
					$consulta->bindValue(":foto",$foto);
					$consulta->bindValue(":id",$id);
					$consulta->execute();
				}

			}
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "listar/cliente" );

		} else {
			//erro do sql
			echo $consulta->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}