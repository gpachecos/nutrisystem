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
        

		if ( empty( $dataficha ) ) {
			echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
			exit;
		}

		//formatar a datas
		$dataficha = formataData( $dataficha );
		
        //selecionar os dados conforme o idfichaanamnese para trazer o idhabitosalimentares caso estiver cadastrado
                $sql1 = "
                select ha.*
                from pessoa p
                left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
                left join habitosalimentares ha on (f.idfichaanamnese = ha.idfichaanamnese) 
                where f.idfichaanamnese = ? limit 1";
                $consulta1 = $pdo->prepare( $sql1 );
                $consulta1->bindParam(1,$idfichaanamnese);
                $consulta1->execute();
                //recuperar os dados
                $dados = $consulta1->fetch(PDO::FETCH_OBJ);

                $idhabitosalimentares 	= $dados->idhabitosalimentares;
               

                // var_dump($idhabitosalimentares);
                // var_dump($idfichaanamnese);
		        // exit;


		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) && empty( $idhabitosalimentares )) {

			
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
			
		} else {

			// // //criptografar a senha
			// // $senha = password_hash($senha, PASSWORD_DEFAULT);

			//update
            $sql2 = "update habitosalimentares set 
                suplementos = :suplementos, alergia = :alergia, intolerancia = :intolerancia, aversao = :aversao
				where idhabitosalimentares = :idhabitosalimentares and idfichaanamnese = :idfichaanamnese limit 1";
			$consulta2 =  $pdo->prepare( $sql2 );
            $consulta2->bindValue(":idhabitosalimentares", $idhabitosalimentares);
            $consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":suplementos", $suplementos);
			$consulta2->bindValue(":alergia", $alergia);
			$consulta2->bindValue(":intolerancia", $intolerancia);
			$consulta2->bindValue(":aversao", $aversao);
			
		}

		//executar
		if ( $consulta2->execute() ) {
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "cadastros/avaliacaoantropometrica/$idfichaanamnese" );


		} else {
			//erro do sql
			echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar cliente";
			mensagem( $msg );
		}

	}