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
	<title>SCHq - Relatório Ficha de Anamnese</title>
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
		<h1 class="text-center"><strong>Ficha de Anamnese</strong></h1>
		<h3 class="text-center"><strong>Recordatório</strong></h3>
		<p class="text-center">NutriSystem - Sistema de Avaliação Nutricional<br>
		Av. Brasil 1000, Centro - Umuarama - PR</p>

		<!-- CABEÇALHO FICHA ANAMNESE -->
		<?php
				//var_dump($_POST);
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
				// $valorInicial = formataValor( $valorInicial );
				// $valorFinal = formataValor( $valorFinal );

				//verificar se o valor final é menor que o inicial
				if ( $valorFinal < $valorInicial ) {
					mensagem("O valor final não pode ser menor que o inicial");
				}

				//sql da busca por valor
				$sql = "select 
						f.idpessoa
						,p.nome
						,f.idfichaanamnese
						,date_format(f.dataficha,'%d/%m/%Y') as dataficha
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						where f.idfichaanamnese between :valorInicial and :valorFinal ";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":valorInicial",$valorInicial);
				$consulta->bindValue(":valorFinal",$valorFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>


		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td>ID</td>
					<td>Nome da Pessoa</td>
					<td>Nr Registro</td>
					<td>Data da Ficha</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$idpessoa 		  = $linha->idpessoa;
					$nome			  = $linha->nome;
					$idfichaanamnese  = $linha->idfichaanamnese;
					$dataficha  	  = $linha->dataficha;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
							<td>$idpessoa</td>
							<td>$nome</td>
							<td>$idfichaanamnese</td>
							<td>$dataficha</td>
						</tr>";
				}
			?>
		</table>

		
		
		<!-- RECORDATORIO -->
		<?php
				//var_dump($_POST);
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
				// $valorInicial = formataValor( $valorInicial );
				// $valorFinal = formataValor( $valorFinal );

				//verificar se o valor final é menor que o inicial
				if ( $valorFinal < $valorInicial ) {
					mensagem("O valor final não pode ser menor que o inicial");
				}

				//sql da Hábitos de Vida
				$sql = "select 
						r.*  ,date_format(r.datarecordatorio, '%d/%m/%Y') as datarecordatorio
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join recordatorio r on (f.idfichaanamnese = r.idfichaanamnese)
						where f.idfichaanamnese between :valorInicial and :valorFinal ";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":valorInicial",$valorInicial);
				$consulta->bindValue(":valorFinal",$valorFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Recordatório</th>
				</tr>
				<tr>
					<td>Data Recordatório</td></td>
					<td>Tipo do Dia</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datarecordatorio	= $linha->datarecordatorio;
					$tipodia			= $linha->tipodia;
	

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
							<td>$datarecordatorio</td>
							<td>$tipodia</td>
						</tr>";
				}
			?>
		</table>

		<!-- RECORDATORIO -->
		<?php
				//var_dump($_POST);
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
				// $valorInicial = formataValor( $valorInicial );
				// $valorFinal = formataValor( $valorFinal );

				//verificar se o valor final é menor que o inicial
				if ( $valorFinal < $valorInicial ) {
					mensagem("O valor final não pode ser menor que o inicial");
				}

				//sql da Hábitos de Vida
				$sql = "select 
						rh.*  
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join recordatorio r on (f.idfichaanamnese = r.idfichaanamnese)
						left join recordatoriohora rh on (r.idrecordatorio = rh.idrecordatorio)
						where f.idfichaanamnese between :valorInicial and :valorFinal ";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":valorInicial",$valorInicial);
				$consulta->bindValue(":valorFinal",$valorFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td>Horário</td></td>
					<td>Alimento</td>
					<td>Quantidade</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$horario	= $linha->horario;
					$alimento	= $linha->alimento;
					$quantidade	= $linha->quantidade;
	

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
							<td>$horario</td>
							<td>$alimento</td>
							<td>$quantidade</td>
						</tr>";
				}
			?>
		</table>



	</div>
</body>
</html>