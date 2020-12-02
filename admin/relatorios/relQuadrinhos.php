<?php
	//iniciar a sessao
	session_start();

	//configurar para serem mostrados os erros do php
	ini_set("display_error",1);
	ini_set("display_startup_errors",1);
	error_reporting(E_ALL);

	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//incluir a conexao com o banco e as funcoes
	include "../config/conexao.php";
	include "../config/funcoes.php";

	//porta do servidor
	$porta = $_SERVER["SERVER_PORT"];

?>
<!DOCTYPE html>
<html lang="pr-BR">
<head>
	<title>SCHq - Relatório de Quadrinhos</title>
	<meta charset="utf-8">

	<base href="http://<?=$_SERVER['SERVER_NAME']. ":$porta" . $_SERVER['SCRIPT_NAME']?>">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/style.css">

	<link rel="shortcut icon" href="images/icone.png">

	<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../js/popper.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	
</head>
<body class="bg">
	<div class="container">
		<h1 class="text-center">Relatório de Quadrinhos</h1>
		<p class="text-center">HQLandia - Loja de Revistas em Quadrinhos<br>
		Av. Brasil 1000, Centro - Douradina - PR</p>

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td>ID</td>
					<td>Título do Quadrinho</td>
					<td>Nr</td>
					<td>Data de Lançamento</td>
					<td>Valor</td>
				</tr>
			</thead>
			<?php
				//iniciar os valores
				$valorInicial = $valorFinal = "";
				//verificar se foram enviados
				if ( isset ( $_POST["valorInicial"] ) ) {
					$valorInicial = trim ( $_POST["valorInicial"] );
				}
				if ( isset ( $_POST["valorFinal"] ) ) {
					$valorFinal = trim ( $_POST["valorFinal"] );
				}

				//transformar em valor 10,00 -> 10.00
				$valorInicial = formataValor( $valorInicial );
				$valorFinal = formataValor( $valorFinal );

				//verificar se o valor final é menor que o inicial
				if ( $valorFinal < $valorInicial ) {
					mensagem("O valor final não pode ser menor que o inicial");
				}

				//sql da busca por valor
				$sql = "select id, titulo, numero, valor,
					date_format(data, '%d/%m/%Y') datalancamento 
					from quadrinho 
					where valor >= :valorInicial 
					and valor <= :valorFinal
					order by valor ";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":valorInicial",$valorInicial);
				$consulta->bindValue(":valorFinal",$valorFinal);
				$consulta->execute();

				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$id 	= $linha->id;
					$titulo	= $linha->titulo;
					$valor  = $linha->valor;
					$valor  = number_format($valor,2,",",".");
					$numero = $linha->numero;
					$data 	= $linha->datalancamento;
					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
							<td>$id</td>
							<td>$titulo</td>
							<td>$numero</td>
							<td>$data</td>
							<td>R$ $valor</td>
						</tr>";
				}
			?>
		</table>
	</div>
</body>
</html>