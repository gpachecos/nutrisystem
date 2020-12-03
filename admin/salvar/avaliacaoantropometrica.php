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

		if ( empty( $dataavaliacao ) ) {
			echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
			exit;
		}

		//formatar a datas
        $dataficha = formataData( $dataficha );
        $dataavaliacao = formataData( $dataavaliacao );

		 //selecionar os dados conforme o idfichaanamnese para trazer o idavaliacaoantropometrica caso estiver cadastrado
		 $sql1 = "
			select aa.*
			from pessoa p
			left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
			left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
			where f.idfichaanamnese = ? limit 1";
		 $consulta1 = $pdo->prepare( $sql1 );
		 $consulta1->bindParam(1,$idfichaanamnese);
		 $consulta1->execute();
		 //recuperar os dados
		 $dados = $consulta1->fetch(PDO::FETCH_OBJ);

         $idavaliacaoantropometrica 	= $dados->idavaliacaoantropometrica;
		

		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) && empty( $idavaliacaoantropometrica ) && !empty( $dataavaliacao )) {
            //var_dump($dataavaliacao);
			// insert dos habitos cotidianos
			$sql2 = "insert into avaliacaoantropometrica 
            (idavaliacaoantropometrica, idfichaanamnese, dataavaliacao, altura, pesoatual, pesoideal, bracodireitorelaxado, 
            bracodireitocontraido, antebracodireito, punhodireito, ombro, torax, cintura, abdomen, quadril, panturrilhadireita, 
            coxadireita, coxaproximaldireita, biceps, triceps, subescapular, axilarmedia, toraxica, suprailiaca, abdominal, coxa, 
            panturrilhamedia)
			values 
            (NULL, :idfichaanamnese, :dataavaliacao, :altura, :pesoatual, :pesoideal, :bracodireitorelaxado, :bracodireitocontraido, 
            :antebracodireito, :punhodireito, :ombro, :torax, :cintura, :abdomen, :quadril, :panturrilhadireita, :coxadireita, 
            :coxaproximaldireita, :biceps, :triceps, :subescapular, :axilarmedia, :toraxica, :suprailiaca, :abdominal, 
            :coxa, :panturrilhamedia)";

			$consulta2 = $pdo->prepare( $sql2 );
            $consulta2->bindValue(":idfichaanamnese",$idfichaanamnese);
            $consulta2->bindValue(":dataavaliacao",$dataavaliacao);
            $consulta2->bindValue(":altura",$altura);
            $consulta2->bindValue(":pesoatual",$pesoatual);
            $consulta2->bindValue(":pesoideal",$pesoideal);
            $consulta2->bindValue(":bracodireitorelaxado",$bracodireitorelaxado);
            $consulta2->bindValue(":bracodireitocontraido",$bracodireitocontraido);
            $consulta2->bindValue(":antebracodireito",$antebracodireito);
            $consulta2->bindValue(":punhodireito",$punhodireito);
            $consulta2->bindValue(":ombro",$ombro);
            $consulta2->bindValue(":torax",$torax);
            $consulta2->bindValue(":cintura",$cintura);
            $consulta2->bindValue(":abdomen",$abdomen);
            $consulta2->bindValue(":quadril",$quadril);
            $consulta2->bindValue(":panturrilhadireita",$panturrilhadireita);
            $consulta2->bindValue(":coxadireita",$coxadireita);
            $consulta2->bindValue(":coxaproximaldireita",$coxaproximaldireita);
            $consulta2->bindValue(":biceps",$biceps);
            $consulta2->bindValue(":triceps",$triceps);
            $consulta2->bindValue(":subescapular",$subescapular);
            $consulta2->bindValue(":axilarmedia",$axilarmedia);
            $consulta2->bindValue(":toraxica",$toraxica);
            $consulta2->bindValue(":suprailiaca",$suprailiaca);
            $consulta2->bindValue(":abdominal",$abdominal);
            $consulta2->bindValue(":coxa",$coxa);
            $consulta2->bindValue(":panturrilhamedia",$panturrilhamedia);
					
        } else if ( empty( $idfichaanamnese ) && empty( $idavaliacaoantropometrica ) && empty( $dataavaliacao )){
            $msg = "Favor informar o código da Ficha de Anamnese!";
			mensagem( $msg );
        } else {
            //var_dump($idfichaanamnese);
			//update
            $sql2 = "update avaliacaoantropometrica set 
            
            dataavaliacao = :dataavaliacao, altura = :altura, pesoatual = :pesoatual, pesoideal = :pesoideal, 
            bracodireitorelaxado = :bracodireitorelaxado, bracodireitocontraido = :bracodireitocontraido, 
            antebracodireito = :antebracodireito, punhodireito = :punhodireito, ombro = :ombro, torax = :torax, cintura = :cintura, 
            abdomen = :abdomen, quadril = :quadril, panturrilhadireita = :panturrilhadireita, coxadireita = :coxadireita, 
            coxaproximaldireita = :coxaproximaldireita, biceps = :biceps, triceps = :triceps, subescapular = :subescapular, 
            axilarmedia = :axilarmedia, toraxica = :toraxica, suprailiaca = :suprailiaca, abdominal = :abdominal, coxa = :coxa, 
            panturrilhamedia = :panturrilhamedia
            
            where idfichaanamnese = :idfichaanamnese and idavaliacaoantropometrica = :idavaliacaoantropometrica limit 1";
            
            $consulta2 =  $pdo->prepare( $sql2 );
            $consulta2->bindValue(":idavaliacaoantropometrica",$idavaliacaoantropometrica);
            $consulta2->bindValue(":idfichaanamnese",$idfichaanamnese);
            $consulta2->bindValue(":dataavaliacao",$dataavaliacao);
            $consulta2->bindValue(":altura",$altura);
            $consulta2->bindValue(":pesoatual",$pesoatual);
            $consulta2->bindValue(":pesoideal",$pesoideal);
            $consulta2->bindValue(":bracodireitorelaxado",$bracodireitorelaxado);
            $consulta2->bindValue(":bracodireitocontraido",$bracodireitocontraido);
            $consulta2->bindValue(":antebracodireito",$antebracodireito);
            $consulta2->bindValue(":punhodireito",$punhodireito);
            $consulta2->bindValue(":ombro",$ombro);
            $consulta2->bindValue(":torax",$torax);
            $consulta2->bindValue(":cintura",$cintura);
            $consulta2->bindValue(":abdomen",$abdomen);
            $consulta2->bindValue(":quadril",$quadril);
            $consulta2->bindValue(":panturrilhadireita",$panturrilhadireita);
            $consulta2->bindValue(":coxadireita",$coxadireita);
            $consulta2->bindValue(":coxaproximaldireita",$coxaproximaldireita);
            $consulta2->bindValue(":biceps",$biceps);
            $consulta2->bindValue(":triceps",$triceps);
            $consulta2->bindValue(":subescapular",$subescapular);
            $consulta2->bindValue(":axilarmedia",$axilarmedia);
            $consulta2->bindValue(":toraxica",$toraxica);
            $consulta2->bindValue(":suprailiaca",$suprailiaca);
            $consulta2->bindValue(":abdominal",$abdominal);
            $consulta2->bindValue(":coxa",$coxa);
            $consulta2->bindValue(":panturrilhamedia",$panturrilhamedia);
			
		}

		//executar
		if ( $consulta2->execute() ) {
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "listar/fichaanamneseAvAntro" );


		} else {
			//erro do sql
			echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar Avaliação";
			mensagem( $msg );
		}

	}