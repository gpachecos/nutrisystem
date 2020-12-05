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
			select ac.*
			from pessoa p
			left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
			left join avaliacaoclinica ac on (f.idfichaanamnese = ac.idfichaanamnese) 
			where f.idfichaanamnese = ? limit 1";
		 $consulta1 = $pdo->prepare( $sql1 );
		 $consulta1->bindParam(1,$idfichaanamnese);
		 $consulta1->execute();
		 //recuperar os dados
		 $dados = $consulta1->fetch(PDO::FETCH_OBJ);

		 $idavaliacaoclinica 	= $dados->idavaliacaoclinica;
		

		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) && empty( $idavaliacaoclinica )) {

			// insert dos habitos cotidianos
			$sql2 = "insert into avaliacaoclinica 
            (idavaliacaoclinica, idfichaanamnese, apetite, mastigacao, tempomastigacao, habitointestinal, frequenciaevacuacao, usodelaxante, 
            sanguefezes, temposaguefezes, habitourinario, ingestaohidricadiaria, corurina, ultimamenstruacao, tpm, clicomenstrualregular, 
            contraceptivo, colica, lactante, menopausa)
			values 
            (NULL, :idfichaanamnese, :apetite, :mastigacao, :tempomastigacao, :habitointestinal, :frequenciaevacuacao, :usodelaxante, 
            :sanguefezes, :temposaguefezes, :habitourinario, :ingestaohidricadiaria, :corurina, :ultimamenstruacao, :tpm, 
            :clicomenstrualregular, :contraceptivo, :colica, :lactante, :menopausa)";

			$consulta2 = $pdo->prepare( $sql2 );
            $consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
            $consulta2->bindValue(":apetite", $apetite);
            $consulta2->bindValue(":mastigacao", $mastigacao);
            $consulta2->bindValue(":tempomastigacao", $tempomastigacao);
            $consulta2->bindValue(":habitointestinal", $habitointestinal);
            $consulta2->bindValue(":frequenciaevacuacao", $frequenciaevacuacao);
            $consulta2->bindValue(":usodelaxante", $usodelaxante);
            $consulta2->bindValue(":sanguefezes", $sanguefezes);
            $consulta2->bindValue(":temposaguefezes", $temposaguefezes);
            $consulta2->bindValue(":habitourinario", $habitourinario);
            $consulta2->bindValue(":ingestaohidricadiaria", $ingestaohidricadiaria);
            $consulta2->bindValue(":corurina", $corurina);
            $consulta2->bindValue(":ultimamenstruacao", $ultimamenstruacao);
            $consulta2->bindValue(":tpm", $tpm);
            $consulta2->bindValue(":clicomenstrualregular", $clicomenstrualregular);
            $consulta2->bindValue(":contraceptivo", $contraceptivo);
            $consulta2->bindValue(":colica", $colica);
            $consulta2->bindValue(":lactante", $lactante);
            $consulta2->bindValue(":menopausa", $menopausa);
					
		} else {

			//update
            $sql2 = "update avaliacaoclinica set 
            apetite = :apetite, mastigacao = :mastigacao, tempomastigacao = :tempomastigacao, 
            habitointestinal = :habitointestinal, frequenciaevacuacao = :frequenciaevacuacao, usodelaxante = :usodelaxante, 
            sanguefezes = :sanguefezes, temposaguefezes = :temposaguefezes, habitourinario = :habitourinario, 
            ingestaohidricadiaria = :ingestaohidricadiaria, corurina = :corurina, ultimamenstruacao = :ultimamenstruacao, 
            tpm = :tpm, clicomenstrualregular = :clicomenstrualregular, contraceptivo = :contraceptivo, colica = :colica, 
            lactante = :lactante, menopausa = :menopausa
			where idfichaanamnese = :idfichaanamnese and idavaliacaoclinica = :idavaliacaoclinica limit 1";
            
            $consulta2 =  $pdo->prepare( $sql2 );
            $consulta2->bindValue(":idavaliacaoclinica", $idavaliacaoclinica);
            $consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
            $consulta2->bindValue(":apetite", $apetite);
            $consulta2->bindValue(":mastigacao", $mastigacao);
            $consulta2->bindValue(":tempomastigacao", $tempomastigacao);
            $consulta2->bindValue(":habitointestinal", $habitointestinal);
            $consulta2->bindValue(":frequenciaevacuacao", $frequenciaevacuacao);
            $consulta2->bindValue(":usodelaxante", $usodelaxante);
            $consulta2->bindValue(":sanguefezes", $sanguefezes);
            $consulta2->bindValue(":temposaguefezes", $temposaguefezes);
            $consulta2->bindValue(":habitourinario", $habitourinario);
            $consulta2->bindValue(":ingestaohidricadiaria", $ingestaohidricadiaria);
            $consulta2->bindValue(":corurina", $corurina);
            $consulta2->bindValue(":ultimamenstruacao", $ultimamenstruacao);
            $consulta2->bindValue(":tpm", $tpm);
            $consulta2->bindValue(":clicomenstrualregular", $clicomenstrualregular);
            $consulta2->bindValue(":contraceptivo", $contraceptivo);
            $consulta2->bindValue(":colica", $colica);
            $consulta2->bindValue(":lactante", $lactante);
            $consulta2->bindValue(":menopausa", $menopausa);
			
		}

		//executar
		if ( $consulta2->execute() ) {
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "cadastros/habitosalimentares/$idfichaanamnese" );


		} else {
			//erro do sql
			echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}