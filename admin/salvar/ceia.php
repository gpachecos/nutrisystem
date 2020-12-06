<?php
	//iniciar a sessao
	session_start();

	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	// var_dump($_POST);
	//var_dump($_GET);
	
	$idfichaanamnese = "";
	if ( isset ( $_GET["idfichaanamnese"] ) ) 
		$idfichaanamnese = trim( $_GET["idfichaanamnese"] );

	if ( isset ( $_POST["idfichaanamnese"] ) ) 
		$idfichaanamnese = trim ( $_POST["idfichaanamnese"] );

		// var_dump($idfichaanamnese);
	//echo $id; 
	//verificar se o id esta branco
	if ( empty ( $idfichaanamnese ) ) {
		echo "<script>alert('Erro ao gravar informação');</script>";
	}

	//incluir a conexao com o banco de dados
	include "../config/conexao.php";
	include "../config/funcoes.php";

	//verificar se foi enviado algo por post
	//salvar o registro no banco de dados
	if ( $_POST ){

        $idrefeicao = 6;
        $idalimento = trim ( $_POST["idalimento"] );
        $datacardapio = trim ( $_POST["dataficha"] );
        $peso = trim ( $_POST["peso"] );

		$datacardapio = formataData( $datacardapio );

        //carregar valores da tabela alimentos para o calculo
        $sql1 = "select energia, proteina, lipideo, carboidrato from alimento
                where idalimento = :idalimento";
        $consulta1 = $pdo->prepare($sql1);
        $consulta1->bindValue(":idalimento", $idalimento);
        $consulta1->execute();

        $dados1 = $consulta1->fetch(PDO::FETCH_OBJ);
        $energia        = $dados1->energia;
        $proteina       = $dados1->proteina;
        $lipideo        = $dados1->lipideo;
        $carboidrato    = $dados1->carboidrato;



		//verificar se existe este alimento no cardapio
		$sql2 = "select * from cardapio
			where idfichaanamnese = :idfichaanamnese 
			and idrefeicao = :idrefeicao 
			";
		$consulta2 = $pdo->prepare($sql2);
		$consulta2->bindValue(":idfichaanamnese", $idfichaanamnese);
		$consulta2->bindValue(":idrefeicao", $idrefeicao);
		$consulta2->execute();

		//pegar os dados
        $dados2 = $consulta2->fetch(PDO::FETCH_OBJ);
        //$idalimento1 = $dados2->idalimento;

		//verificar se trouxe algum dado
		if ( !empty($dados->idrefeicao ) && !empty( $dados2->idalimento)) {
			mensagem("Já existe uma refeicao cadastrada no cardapio");
		} else {
            //gravar o cardapio com refeicao e alimento
            
            /**** CALCULO  ****/

                $energia1       = calculaProporcaoAlimento($peso, $energia);
                $proteina1      = calculaProporcaoAlimento($peso, $proteina);
                $lipideo1       = calculaProporcaoAlimento($peso, $lipideo);
                $carboidrato1   = calculaProporcaoAlimento($peso, $carboidrato);

            /******************/

			$sql = "insert into cardapio
				(idcardapio, idfichaanamnese, idrefeicao, idalimento, datacardapio, peso, energia, proteina, lipideo, carboidrato) 
				values (NUll,:idfichaanamnese, :idrefeicao, :idalimento, :datacardapio, :peso, :energia, :proteina, :lipideo, :carboidrato)";
			$consulta = $pdo->prepare($sql);
			$consulta->bindValue(":idfichaanamnese", $idfichaanamnese);
            $consulta->bindValue(":idrefeicao", $idrefeicao);
            $consulta->bindValue(":idalimento", $idalimento);
            $consulta->bindValue(":datacardapio", $datacardapio);
            $consulta->bindValue(":peso", $peso);
            $consulta->bindValue(":energia", $energia1);
            $consulta->bindValue(":proteina", $proteina1);
            $consulta->bindValue(":lipideo", $lipideo1);
            $consulta->bindValue(":carboidrato", $carboidrato1);
			
			if ( $consulta->execute()) {
                $link = "";

				$link = "ceia.php?idfichaanamnese=$idfichaanamnese";
				var_dump($link);
				sucesso("Alimento incluído",$link);

			} else {

				// $erro = $consulta->errorInfo();
				// print_r( $erro );

				$link = "ceia.php?idfichaanamnese=$idfichaanamnese";
				//echo $link;
				sucesso("Erro ao inserir alimento",$link);

			}

		}

	}

    //selecionar todos os personagens deste quadrinho
    $idrefeicao = 6;
    $sql3 = 
        "select c.*, date_format(c.datacardapio,'%d/%m/%Y') as datacardapio, a.nomealimento
		from pessoa p
		left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
        left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
        left join alimento a on (c.idalimento = a.idalimento)
		where f.idfichaanamnese = :idfichaanamnese and idrefeicao = :idrefeicao";
	$consulta3 = $pdo->prepare($sql3);
    $consulta3->bindValue(":idfichaanamnese",$idfichaanamnese);
    $consulta3->bindValue(":idrefeicao",$idrefeicao);
	$consulta3->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Adicionar Alimento</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/all.min.css">
</head>
<body>
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<!-- <td>ID</td> -->
            <td>Nome do Alimento</td>
            <td>Porção(g)</td>
            <td>Calorias</td>
            <td>Proteinas</td>
            <td>Lipídeos</td>
            <td>Carboidratos</td>
			<td>Excluir</td>
		</tr>
	</thead>

	<?php
	//mostrar os resultados
	while ( $dados = $consulta3->fetch(PDO::FETCH_OBJ) ) {

		$idcardapio 		= $dados->idcardapio;
		$datacardapio 		= $dados->datacardapio;
		$idrefeicao 		= $dados->idrefeicao;
        $idalimento 		= $dados->idalimento;
        $nomealimento 		= $dados->nomealimento;
        $peso        		= $dados->peso;
        $energia 		    = $dados->energia;
        $proteina 		    = $dados->proteina;
        $lipideo 		    = $dados->lipideo;
        $carboidrato 		= $dados->carboidrato;

		// var_dump($nomepatologia);

		echo "<tr>
            <td>$nomealimento</td>
            <td>$peso</td>
            <td>$energia</td>
            <td>$proteina</td>
            <td>$lipideo</td>
            <td>$carboidrato</td>
			<td>
				<a href='javascript:excluir($idfichaanamnese,$idrefeicao,$idalimento,$idcardapio)'
				class='btn btn-danger'>
					<i class='fas fa-trash'></i>
				</a>
			</td>
		</tr>";

	}

	?>
</table>
<script type="text/javascript">
	//funcao para excluir
	function excluir(idfichaanamnese,idrefeicao,idalimento,idcardapio){
		if ( confirm ( "Deseja mesmo excluir?" ) ){
			//enviar para uma página para excluir
			location.href='../excluir/ceia/'+idfichaanamnese+'/'+idrefeicao+'/'+idalimento+'/'+idcardapio;
		}
	}
</script>
</body>
</html>