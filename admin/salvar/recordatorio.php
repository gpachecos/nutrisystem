<?php
	//verificar se o arquivo existe
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

		//   var_dump($_POST);
		//  exit;

		if ( empty( $dataficha ) ) {
			echo "<script>alert('Preencha a Data da Ficha');history.back();</script>";
			exit;
		}

		//formatar a datas
		$dataficha = formataData( $dataficha );
		$datarecordatorio = formataData( $datarecordatorio );
		
        //selecionar os dados conforme o idfichaanamnese para trazer o idhabitosalimentares caso estiver cadastrado
            $sql1 = "
                select r.*, date_format(r.datarecordatorio,'%d/%m/%Y') as datarecordatorio, rh.*
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

			$idrecordatorio 	= $dados->idrecordatorio;

        
		$pdo->beginTransaction();


		//se o id for vazio insere - se não update!
		if ( !empty ( $idfichaanamnese ) && empty ( $idrecordatorio ) ) {


			// insert dos habitos recordatorio
			$sql2 = "insert into recordatorio 
			(idrecordatorio, idfichaanamnese, datarecordatorio, tipodia)
			values 
			(NULL, :idfichaanamnese, :datarecordatorio, :tipodia)";

			$consulta2 = $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":datarecordatorio", $datarecordatorio);
			$consulta2->bindValue(":tipodia", $tipodia);
            

            //gravar o $idrecordatorio
            $sql1 = "
                select r.*, date_format(r.datarecordatorio,'%d/%m/%Y') as datarecordatorio, rh.*
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

			$idrecordatorio 	= $dados->idrecordatorio;
			
		} else {

			//update
            $sql2 = "update recordatorio set 
			datarecordatorio = :datarecordatorio, tipodia = :tipodia
			where idfichaanamnese = :idfichaanamnese and idrecordatorio = :idrecordatorio limit 1";
			$consulta2 =  $pdo->prepare( $sql2 );
			$consulta2->bindValue(":idrecordatorio", $idrecordatorio);
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta2->bindValue(":datarecordatorio", $datarecordatorio);
			$consulta2->bindValue(":tipodia", $tipodia);
		}

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