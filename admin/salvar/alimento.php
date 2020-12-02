<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	/*
	echo "<br><br>";
	print_r( $_POST );
	echo "<br><br>";
	print_r( $_GET );
	echo "<br><br>";
	print_r( $_FILES );
	*/

	//iniciar uma transação
	$pdo->beginTransaction();

	//se os dados vieram por POST
	if ( $_POST ) {
        //iniciar as variaveis
        $idalimento = $idcategoriaalimento = $nomealimento = 
        $energia = $proteina = $lipideo = $carboidrato = "";


		//recuperar as variaveis
		foreach ($_POST as $key => $value) {
			//echo "<p>$key $value</p>";
			//$key - nome do campo
			//$value - valor do campo (digitado)
			if ( isset ( $_POST[$key] ) ) {
				$$key = trim ( $value );
			} 
        }
        
        //var_dump($idcategoriaalimento,$idalimento);

		

		//se o id for vazio insere - se não update!
		if ( empty ( $idalimento ) ) {

			//adicionar um nome de foto de capa
			// $capa = time();

			//insert
			$sql = "insert into alimento 
			(idalimento, idcategoriaalimento, nomealimento, energia, proteina, lipideo, carboidrato)
			values 
			(NULL, :idcategoriaalimento, :nomealimento, :energia, :proteina, :lipideo,:carboidrato)";

			$consulta = $pdo->prepare( $sql );
			$consulta->bindValue(":idcategoriaalimento",$idcategoriaalimento);
			$consulta->bindValue(":nomealimento",$nomealimento);
			$consulta->bindValue(":energia",$energia);
			$consulta->bindValue(":proteina",$proteina);
			$consulta->bindValue(":lipideo",$lipideo);
			$consulta->bindValue(":carboidrato",$carboidrato);

		} else {
			//update
			$sql = "update alimento set idcategoriaalimento = :idcategoriaalimento,
                nomealimento = :nomealimento, 
                energia = :energia, 
                proteina = :proteina, 
                lipideo = :lipideo, 
				carboidrato = :carboidrato 
                where idalimento = :idalimento limit 1";
              //  echo $sql;
			$consulta =  $pdo->prepare($sql);
			$consulta->bindValue(":idcategoriaalimento",$idcategoriaalimento);
			$consulta->bindValue(":nomealimento",$nomealimento);
			$consulta->bindValue(":energia",$energia);
			$consulta->bindValue(":proteina",$proteina);
			$consulta->bindValue(":lipideo",$lipideo);
			$consulta->bindValue(":carboidrato",$carboidrato);
			$consulta->bindValue(":idalimento", $idalimento);
            
            //echo $sql;
		}

		//executar
		if ( $consulta->execute() ) {


			

			//se a capa não estiver vazio - copiar
			// if ( !empty ( $_FILES["capa"]["name"] ) ) {
			// 	echo $_FILES["capa"]["name"];
			// 	//copiar o arquivo para a pasta

			// 	if ( !copy( $_FILES["capa"]["tmp_name"], 
			// 		"../fotos/".$_FILES["capa"]["name"] )) {

			// 		$msg = "Erro ao copiar foto";
			// 		mensagem( $msg );
			// 	}

			// 	//echo $capa;

			// 	$pastaFotos = "../fotos/";
			// 	$imagem = $_FILES["capa"]["name"];

			// 	redimensionarImagem($pastaFotos,$imagem,$capa);
			// }
			
			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			sucesso( $msg, "listar/alimento" );

		} else {
			//erro do sql
			echo "<br>". $consulta->errorInfo()[2];
			exit;
			$msg = "Erro ao salvar alimento";
			mensagem( $msg );
		}


	} else {
		//se não foi veio do formulario
		$msg = "Requisição inválida";
		mensagem( $msg );
	}

	