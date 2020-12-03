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

		//var_dump($_POST);
		//  exit;

		if ( empty( $dataficha ) ) {
			echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
			exit;
		}

		//formatar a datas
		$dataficha = formataData( $dataficha );
		

		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( empty ( $idfichaanamnese ) ) {

			// VALIDAÇÃO para verificar se já existe uma ficha para a mesma pessoa na mesma data, caso houver não executa.
			$sql3 = "
				select p.idpessoa, p.nome , f.idfichaanamnese, f.dataficha
				from pessoa p 
				left join fichaanamnese f on (p.idpessoa = f.idpessoa) 
				where p.idpessoa = ? and f.dataficha = ? limit 1";
			$consulta3 = $pdo->prepare( $sql3 );
			$consulta3->bindParam(1,$idpessoa);
			$consulta3->bindParam(2,$dataficha);
			$consulta3->execute();
			//recuperar os dados
			$dados3 = $consulta3->fetch(PDO::FETCH_OBJ);
			$dataficha3 		= $dados3->dataficha;
			$idfichaanamnese3 		= $dados3->idfichaanamnese;

			//verificar se o dados trouxe algum resultado
			if ( $dataficha3 == $dataficha ) {
				$msg = "Já existe uma Ficha Anamnese cadastrada na sua base de dados, para esta data. É a ficha: $idfichaanamnese3";
				mensagem($msg);
				exit;
			}




			// //criptografar a senha
			// $senha = password_hash($senha, PASSWORD_DEFAULT);

			//insert da ficha de anamnese
			$sql = "insert into fichaanamnese 
			(idfichaanamnese, idpessoa, dataficha )
			values 
			(NULL, :idpessoa, :dataficha)";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindValue(":idpessoa",$idpessoa);
            $consulta->bindValue(":dataficha",$dataficha);

			if ( $consulta->execute() ) {

				//busca o id da ficha para carregar na variavel para executar o insert dos habitos cotidianos
					$sql1 = "
						select p.idpessoa, p.nome , f.idfichaanamnese, f.dataficha
						from pessoa p 
						left join fichaanamnese f on (p.idpessoa = f.idpessoa) 
						where p.idpessoa = ? and f.dataficha = ? limit 1";
					$consulta1 = $pdo->prepare( $sql1 );
					$consulta1->bindParam(1,$idpessoa);
					$consulta1->bindParam(2,$dataficha);
					$consulta1->execute();
					//recuperar os dados
					$dados = $consulta1->fetch(PDO::FETCH_OBJ);

					//$idpessoa 			= $dados->idpessoa;
					//$nome 				= $dados->nome;
					$idfichaanamnese 	= $dados->idfichaanamnese;
					//$dataficha 			= $dados->dataficha;


				//salvar no banco
				$pdo->commit();

			} else {

				//erro do sql
				echo "<br><br><br>Erro na consulta: ". $consulta->errorInfo()[2];
				exit;
				$msg = "Erro ao salvar Ficha Anamnese";
				mensagem( $msg );

			}


			// //selecionar os dados conforme o id
			// $sql1 = "
			// 	select p.idpessoa, p.nome , f.idfichaanamnese, f.dataficha
			// 	from pessoa p 
			// 	left join fichaanamnese f on (p.idpessoa = f.idpessoa) 
			// 	where p.idpessoa = ? and f.dataficha = ? limit 1";
			// $consulta1 = $pdo->prepare( $sql1 );
			// $consulta1->bindParam(1,$idpessoa);
			// $consulta1->bindParam(2,$dataficha);
			// $consulta1->execute();
			// //recuperar os dados
			// $dados = $consulta1->fetch(PDO::FETCH_OBJ);

			// //$idpessoa 			= $dados->idpessoa;
			// //$nome 				= $dados->nome;
			// $idfichaanamnese 	= $dados->idfichaanamnese;
			// //$dataficha 			= $dados->dataficha;

			
			$pdo->beginTransaction();

			// insert dos habitos cotidianos
			$sql2 = "insert into habitoscotidiano 
			(idhabitoscotidiano, idfichaanamnese, restricaoalimentar, descrestricao, ingbebidaalcool, freqbebidaalcool, fumante, 
			 freqfumo, qdtemoradorescasa, quemfazcompra, localcompra, freqcompra, litrosoleo, quilossal, qualidadesono, horassono, 
			 observacaosono, praticaexercfisico, exerciciofisico, freqexercfisico, tempoexercfisico	)
			values 
			(NULL, :idfichaanamnese, :restricaoalimentar, :descrestricao, :ingbebidaalcool, :freqbebidaalcool, :fumante, 
			 :freqfumo, :qdtemoradorescasa, :quemfazcompra, :localcompra, :freqcompra, :litrosoleo, :quilossal, :qualidadesono, :horassono, 
			 :observacaosono, :praticaexercfisico, :exerciciofisico, :freqexercfisico, :tempoexercfisico)";

			$consulta2 = $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":restricaoalimentar", $restricaoalimentar);
			$consulta2->bindValue(":descrestricao", $descrestricao);
			$consulta2->bindValue(":ingbebidaalcool", $ingbebidaalcool);
			$consulta2->bindValue(":freqbebidaalcool", $freqbebidaalcool);
			$consulta2->bindValue(":fumante", $fumante);
			$consulta2->bindValue(":freqfumo", $freqfumo);
			$consulta2->bindValue(":qdtemoradorescasa", $qdtemoradorescasa);
			$consulta2->bindValue(":quemfazcompra", $quemfazcompra);
			$consulta2->bindValue(":localcompra", $localcompra);
			$consulta2->bindValue(":freqcompra", $freqcompra);
			$consulta2->bindValue(":litrosoleo", $litrosoleo);
			$consulta2->bindValue(":quilossal", $quilossal);
			$consulta2->bindValue(":qualidadesono", $qualidadesono);
			$consulta2->bindValue(":horassono", $horassono);
			$consulta2->bindValue(":observacaosono", $observacaosono);
			$consulta2->bindValue(":praticaexercfisico", $praticaexercfisico);
			$consulta2->bindValue(":exerciciofisico", $exerciciofisico);
			$consulta2->bindValue(":freqexercfisico", $freqexercfisico);
			$consulta2->bindValue(":tempoexercfisico", $tempoexercfisico);
			
			



		} else {

			// // //criptografar a senha
			// // $senha = password_hash($senha, PASSWORD_DEFAULT);

			//update
            $sql2 = "update habitoscotidiano set 
				restricaoalimentar = :restricaoalimentar, descrestricao = :descrestricao, ingbebidaalcool = :ingbebidaalcool, 
				freqbebidaalcool = :freqbebidaalcool, fumante = :fumante, freqfumo = :freqfumo, qdtemoradorescasa = :qdtemoradorescasa, 
				quemfazcompra = :quemfazcompra, localcompra = :localcompra, freqcompra = :freqcompra, litrosoleo = :litrosoleo, 
				quilossal = :quilossal, qualidadesono = :qualidadesono, horassono = :horassono, observacaosono = :observacaosono, 
				praticaexercfisico = :praticaexercfisico, exerciciofisico = :exerciciofisico, freqexercfisico = :freqexercfisico, 
				tempoexercfisico = :tempoexercfisico
				where idfichaanamnese = :idfichaanamnese limit 1";
			$consulta2 =  $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":restricaoalimentar", $restricaoalimentar);
			$consulta2->bindValue(":descrestricao", $descrestricao);
			$consulta2->bindValue(":ingbebidaalcool", $ingbebidaalcool);
			$consulta2->bindValue(":freqbebidaalcool", $freqbebidaalcool);
			$consulta2->bindValue(":fumante", $fumante);
			$consulta2->bindValue(":freqfumo", $freqfumo);
			$consulta2->bindValue(":qdtemoradorescasa", $qdtemoradorescasa);
			$consulta2->bindValue(":quemfazcompra", $quemfazcompra);
			$consulta2->bindValue(":localcompra", $localcompra);
			$consulta2->bindValue(":freqcompra", $freqcompra);
			$consulta2->bindValue(":litrosoleo", $litrosoleo);
			$consulta2->bindValue(":quilossal", $quilossal);
			$consulta2->bindValue(":qualidadesono", $qualidadesono);
			$consulta2->bindValue(":horassono", $horassono);
			$consulta2->bindValue(":observacaosono", $observacaosono);
			$consulta2->bindValue(":praticaexercfisico", $praticaexercfisico);
			$consulta2->bindValue(":exerciciofisico", $exerciciofisico);
			$consulta2->bindValue(":freqexercfisico", $freqexercfisico);
			$consulta2->bindValue(":tempoexercfisico", $tempoexercfisico);
		}

		//executar
		if ( $consulta2->execute() ) {
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			//sucesso( $msg, "listar/pessoa" );


		} else {
			//erro do sql
			echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}