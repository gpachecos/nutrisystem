<?php
	// //verificar se o arquivo existe
	// if ( file_exists ( "verificalogin.php" ) )
	// 	include "verificalogin.php";
	// else
	// 	include "../verificalogin.php";

	require_once '../config/funcoes.php';
    require_once '../config/conexao.php';

	//verificar se os dados foram enviados
	if ( $_POST ) {

		foreach ($_POST as $key => $value) {
			$$key = trim ( $value );
		}

		// var_dump($_POST);
		// exit;

		// if ( empty( $dataficha ) ) {
		// 	echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
		// 	exit;
		// }

		// //formatar a datas
		// $dataficha = formataData( $dataficha );
		// $datarecordatorio = formataData( $datarecordatorio );
		
        //selecionar os dados conforme o idfichaanamnese para trazer o idhabitosalimentares caso estiver cadastrado
            $sql1 = "
                select r.*, date_format(r.datarecordatorio,'%d/%m/%Y') as datarecordatorio, rh.*, f.idfichaanamnese
                from pessoa p
                left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
                left join recordatorio r on (f.idfichaanamnese = r.idfichaanamnese) 
                left join recordatoriohora rh on (r.idrecordatorio = rh.idrecordatorio)  
                where f.idfichaanamnese = ? limit 1";
			$consulta1 = $pdo->prepare( $sql1 );
			$consulta1->bindParam(1,$idfichaanamnese);
			$consulta1->execute();
			//recuperar os dados
			$dados = $consulta1->fetch(PDO::FETCH_OBJ);

			$idrecordatoriohora 	= $dados->idrecordatoriohora;
			$idfichaanamnese		= $dados->idfichaanamnese;

        
		$pdo->beginTransaction();



		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) /*&& empty ( $idrecordatoriohora ) */) {
			// var_dump($idfichaanamnese);
			
			// insert dos habitos recordatorio
			$sql2 = "insert into recordatoriohora 
			(idrecordatoriohora,idrecordatorio, horario, alimento, quantidade)
			values 
			(NULL, :idrecordatorio, :horario, :alimento, :quantidade)";

			$consulta2 = $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idrecordatorio", $idrecordatorio);
			$consulta2->bindValue(":horario", $horario);
			$consulta2->bindValue(":alimento", $alimento);
			$consulta2->bindValue(":quantidade", $quantidade);
            
			
		 } //else {

			
		// 	//update
        //     $sql2 = "update recordatoriohora set 
		// 	horario = :horario, alimento = :alimento, quantidade = :quantidade
		// 	where idrecordatorio = :idrecordatorio and idrecordatoriohora = :idrecordatoriohora limit 1";
		// 	$consulta2 =  $pdo->prepare( $sql2 );
		// 	$consulta2->bindValue(":idrecordatorio", $idrecordatorio);
		// 	$consulta2->bindValue(":idrecordatoriohora", $idrecordatoriohora);
		// 	$consulta2->bindValue(":horario", $horario);
		// 	$consulta2->bindValue(":alimento", $alimento);
		// 	$consulta2->bindValue(":quantidade", $quantidade);
		// }

		//executar
		if ( $consulta2->execute() ) {
			

			//salvar no banco
			$pdo->commit();

			$msg = "Registro inserido com sucesso!";
			echo json_encode(['message'=>$msg]);
			//sucesso( $msg, "cadastros/recordatorio/$idfichaanamnese" );


		} else {
			//erro do sql
			echo json_encode(['message'=>"Erro na consulta2: " . $consulta2->errorInfo()[2]]);
			// echo "Erro na consulta2: " . $consulta2->errorInfo()[2];
			// exit;
			// $msg = "Erro ao salvar cliente";
			// mensagem( $msg );
		}

	}