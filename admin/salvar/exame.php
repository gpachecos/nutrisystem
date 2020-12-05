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

		//   var_dump($_POST);
		//  exit;

		if ( empty( $dataficha ) ) {
			echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
			exit;
		}

		//formatar a datas
		$dataficha = formataData( $dataficha );
		$dataexame = formataData( $dataexame );
		
        //selecionar os dados conforme o idfichaanamnese para trazer o idhabitosalimentares caso estiver cadastrado
            $sql1 = "
                select e.*, p.nome
                from pessoa p
                left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
                left join exame e on (f.idfichaanamnese = e.idfichaanamnese) 
                where f.idfichaanamnese = ? limit 1";
			$consulta1 = $pdo->prepare( $sql1 );
			$consulta1->bindParam(1,$idfichaanamnese);
			$consulta1->execute();
			//recuperar os dados
			$dados = $consulta1->fetch(PDO::FETCH_OBJ);

			$idexame 	= $dados->idexame;
			$nome		= $dados->nome;

        
		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) /*&& empty ( $idexame ) */) {

			//adicionar nome ao arquivo
			$extensao = extensao($_FILES["arquivo"]["name"]);
			$arqexame = time().$extensao;

			// insert dos habitos cotidianos
			$sql2 = "insert into exame 
			(idexame, idfichaanamnese, nomeexame, dataexame, referencia, alteracao, arqexame)
			values 
			(NULL, :idfichaanamnese, :nomeexame, :dataexame, :referencia, :alteracao, :arqexame)";

			$consulta2 = $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":nomeexame", $nomeexame);
			$consulta2->bindValue(":dataexame", $dataexame);
			$consulta2->bindValue(":referencia", $referencia);
			$consulta2->bindValue(":alteracao", $alteracao);
			$consulta2->bindValue(":arqexame", $arqexame);
			
		} else {

			//update
            $sql2 = "update exame set 
			nomeexame = :nomeexame, dataexame = :dataexame, referencia = :referencia, alteracao = :alteracao, arqexam = :arqexame
			where idfichaanamnese = :idfichaanamnese and idexame = :idexame limit 1";
			$consulta2 =  $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idexame", $idexame);
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":nomeexame", $nomeexame);
			$consulta2->bindValue(":dataexame", $dataexame);
			$consulta2->bindValue(":referencia", $referencia);
			$consulta2->bindValue(":alteracao", $alteracao);
			$consulta2->bindValue(":arqexame", $arqexame);
		}

		//executar
		if ( $consulta2->execute() ) {
			

				//se a capa não estiver vazio - copiar
					if ( !empty ( $_FILES["arquivo"]["name"] ) ) {
						//echo $_FILES["arquivo"]["name"];
						//copiar o arquivo para a pasta


						if ( !copy( $_FILES["arquivo"]["tmp_name"], 
							"../arquivosExame/".$arqexame.".".$extensao )) {
							$msg = "Erro ao anexar arquivo";
							mensagem( $msg );

						}

						//echo $capa;

						// $pastaFotos = "../fotos/";
						// $imagem = $_FILES["arquivo"]["name"];

					}


			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "cadastros/exame/$idfichaanamnese" );


		} else {
			//erro do sql
			echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}