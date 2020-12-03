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
			select hm.*
			from pessoa p
			left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
			left join historicomedico hm on (f.idfichaanamnese = hm.idfichaanamnese) 
			where f.idfichaanamnese = ? limit 1";
		 $consulta1 = $pdo->prepare( $sql1 );
		 $consulta1->bindParam(1,$idfichaanamnese);
		 $consulta1->execute();
		 //recuperar os dados
		 $dados = $consulta1->fetch(PDO::FETCH_OBJ);

		 $idhistoricomedico 	= $dados->idhistoricomedico;
		

		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) && empty( $idhistoricomedico )) {

			
			// insert dos habitos cotidianos
			$sql2 = "insert into historicomedico 
			(idhistoricomedico, idfichaanamnese, usomedicamentos, descmedicamentos, horariomedicamentos, historicofamiliar)
			values 
			(NULL, :idfichaanamnese, :usomedicamentos, :descmedicamentos, :horariomedicamentos, :historicofamiliar)";

			$consulta2 = $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":usomedicamentos", $usomedicamentos);
			$consulta2->bindValue(":descmedicamentos", $descmedicamentos);
			$consulta2->bindValue(":horariomedicamentos", $horariomedicamentos);
			$consulta2->bindValue(":historicofamiliar", $historicofamiliar);
					
		} else {

			//update
            $sql2 = "update historicomedico set 
				usomedicamentos = :usomedicamentos, descmedicamentos = :descmedicamentos, 
				horariomedicamentos = :horariomedicamentos, historicofamiliar = :historicofamiliar
				where idfichaanamnese = :idfichaanamnese and idhistoricomedico = :idhistoricomedico limit 1";
			$consulta2 =  $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":idhistoricomedico", $idhistoricomedico);
			$consulta2->bindValue(":usomedicamentos", $usomedicamentos);
			$consulta2->bindValue(":descmedicamentos", $descmedicamentos);
			$consulta2->bindValue(":horariomedicamentos", $horariomedicamentos);
			$consulta2->bindValue(":historicofamiliar", $historicofamiliar);
			
		}

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