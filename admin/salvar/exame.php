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

		//  var_dump($ingbebidaalcool);
		//  exit;

		if ( empty( $dataficha ) ) {
			echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
			exit;
		}

		//formatar a datas
		$dataficha = formataData( $dataficha );
		
        //selecionar os dados conforme o idfichaanamnese para trazer o idhabitosalimentares caso estiver cadastrado
                $sql1 = "
                select e.*
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

        
		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) && empty ( $idexame ) ) {

			
			// insert dos habitos cotidianos
			$sql2 = "insert into habitosalimentares 
			(idhabitosalimentares, idfichaanamnese, suplementos, alergia, intolerancia, aversao)
			values 
			(NULL, :idfichaanamnese, :suplementos, :alergia, :intolerancia, :aversao)";

			$consulta2 = $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":suplementos", $suplementos);
			$consulta2->bindValue(":alergia", $alergia);
			$consulta2->bindValue(":intolerancia", $intolerancia);
			$consulta2->bindValue(":aversao", $aversao);
			
		} //else {

			// // //criptografar a senha
			// // $senha = password_hash($senha, PASSWORD_DEFAULT);

			//update
        //     $sql2 = "update habitoscotidiano set 
		// 		restricaoalimentar = :restricaoalimentar, descrestricao = :descrestricao, ingbebidaalcool = :ingbebidaalcool, 
		// 		freqbebidaalcool = :freqbebidaalcool, fumante = :fumante, freqfumo = :freqfumo, qdtemoradorescasa = :qdtemoradorescasa, 
		// 		quemfazcompra = :quemfazcompra, localcompra = :localcompra, freqcompra = :freqcompra, litrosoleo = :litrosoleo, 
		// 		quilossal = :quilossal, qualidadesono = :qualidadesono, horassono = :horassono, observacaosono = :observacaosono, 
		// 		praticaexercfisico = :praticaexercfisico, exerciciofisico = :exerciciofisico, freqexercfisico = :freqexercfisico, 
		// 		tempoexercfisico = :tempoexercfisico
		// 		where idfichaanamnese = :idfichaanamnese limit 1";
		// 	$consulta2 =  $pdo->prepare( $sql2 );
		// 	$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
		// 	$consulta2->bindValue(":restricaoalimentar", $restricaoalimentar);
		// 	$consulta2->bindValue(":descrestricao", $descrestricao);
		// 	$consulta2->bindValue(":ingbebidaalcool", $ingbebidaalcool);
		// 	$consulta2->bindValue(":freqbebidaalcool", $freqbebidaalcool);
		// 	$consulta2->bindValue(":fumante", $fumante);
		// 	$consulta2->bindValue(":freqfumo", $freqfumo);
		// 	$consulta2->bindValue(":qdtemoradorescasa", $qdtemoradorescasa);
		// 	$consulta2->bindValue(":quemfazcompra", $quemfazcompra);
		// 	$consulta2->bindValue(":localcompra", $localcompra);
		// 	$consulta2->bindValue(":freqcompra", $freqcompra);
		// 	$consulta2->bindValue(":litrosoleo", $litrosoleo);
		// 	$consulta2->bindValue(":quilossal", $quilossal);
		// 	$consulta2->bindValue(":qualidadesono", $qualidadesono);
		// 	$consulta2->bindValue(":horassono", $horassono);
		// 	$consulta2->bindValue(":observacaosono", $observacaosono);
		// 	$consulta2->bindValue(":praticaexercfisico", $praticaexercfisico);
		// 	$consulta2->bindValue(":exerciciofisico", $exerciciofisico);
		// 	$consulta2->bindValue(":freqexercfisico", $freqexercfisico);
		// 	$consulta2->bindValue(":tempoexercfisico", $tempoexercfisico);
		// }

		//executar
		if ( $consulta2->execute() ) {
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "listar/pessoa" );


		} else {
			//erro do sql
			echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}