<?php
	//iniciar a sessao
	session_start();

	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//var_dump($_POST);
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

		$idpatologia = trim ( $_POST["idpatologia"] );

		//verificar se existe este personagem no quadrinho
		$sql = "select * from anamnese_patologia
			where idfichaanamnese = :idfichaanamnese 
			and idpatologia = :idpatologia 
			limit 1";
		$consulta = $pdo->prepare($sql);
		$consulta->bindValue(":idfichaanamnese", $idfichaanamnese);
		$consulta->bindValue(":idpatologia", $idpatologia);
		$consulta->execute();

		//pegar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		//verificar se trouxe algum dado
		if ( !empty($dados->idpatologia ) ) {
			mensagem("Já existe uma patologia cadastrada");
		} else {
			//gravar o personagem e o quadrinho no banco
			$sql = "insert into anamnese_patologia
				(idfichaanamnese, idpatologia) 
				values (:idfichaanamnese, :idpatologia)";
			$consulta = $pdo->prepare($sql);
			$consulta->bindValue(":idfichaanamnese", $idfichaanamnese);
			$consulta->bindValue(":idpatologia", $idpatologia);
			
			if ( $consulta->execute()) {

				$link = "patologias.php?idfichaanamnese=$idfichaanamnese";
				//echo $link;
				sucesso("Patologia incluída",$link);

			} else {

				//$erro = $consulta->errorInfo();
				//print_r( $erro );

				$link = "salvar/patologias.php?idfichaanamnese=$idfichaanamnese";
				//echo $link;
				sucesso("Erro ao inserir patologia",$link);

			}

		}

	}

	//selecionar todos os personagens deste quadrinho
	$sql = "select ap.*, pat.nomepatologia
		from pessoa p
		left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
		left join anamnese_patologia ap on (f.idfichaanamnese = ap.idfichaanamnese)
		left join patologia pat on (ap.idpatologia = pat.idpatologia)
		where f.idfichaanamnese = :idfichaanamnese";
	$consulta = $pdo->prepare($sql);
	$consulta->bindValue(":idfichaanamnese",$idfichaanamnese,PDO::PARAM_INT);
	$consulta->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Adicionar Personagens</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/all.min.css">
</head>
<body>
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<!-- <td>ID</td> -->
			<td>Patologia</td>
			<td>Excluir</td>
		</tr>
	</thead>

	<?php
	//mostrar os resultados
	while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {

		$idpatologia 		= $dados->idpatologia;
		$nomepatologia 		= $dados->nomepatologia;

		// var_dump($nomepatologia);

		echo "<tr>
			<td>$nomepatologia</td>
			<td>
				<a href='javascript:excluir($idfichaanamnese,$idpatologia)'
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
	function excluir(idfichaanamnese,idpatologia){
		if ( confirm ( "Deseja mesmo excluir?" ) ){
			//enviar para uma página para excluir
			location.href='../excluir/patologias/'+idfichaanamnese+'/'+idpatologia;
		}
	}
</script>
</body>
</html>





