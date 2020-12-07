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
		<h3 class="text-center"><strong>CARDÁPIO</strong></h3>
		<p class="text-center">NutriSystem - Sistema de Avaliação Nutricional<br>
		Av. Brasil 1000, Centro - Umuarama - PR</p>

		<!-- CABEÇALHO FICHA ANAMNESE -->
		<?php
				//var_dump($_POST);
				//iniciar os valores
				$dataFinal = $dataInicial = "";
				//verificar se foram enviados
				if ( isset ( $_POST["dataInicial"] ) ) {
					$dataInicial = trim ( $_POST["dataInicial"] );
				}
				if ( isset ( $_POST["dataFinal"] ) ) {
					$dataFinal = trim ( $_POST["dataFinal"] );
				}

				$dataInicial = formataData($dataInicial);
				$dataFinal = formataData($dataFinal);

				//transformar em valor 10,00 -> 10.00
				// $valorInicial = formataValor( $valorInicial );
				// $valorFinal = formataValor( $valorFinal );

				//verificar se o valor final é menor que o inicial
				if ( $dataFinal < $dataInicial ) {
					mensagem("A data final não pode ser menor que a inicial");
				}

				//sql da busca por valor
				$sql = "select 
						f.idpessoa
						,p.nome
						,f.idfichaanamnese
						,date_format(f.dataficha,'%d/%m/%Y') as dataficha
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						where f.dataficha between :dataInicial and :dataFinal ";
				$consulta = $pdo->prepare( $sql );
				// $consulta->bindValue(":dataInicial",$valorInicial);
				// $consulta->bindValue(":dataFinal",$valorFinal);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
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

		
		
		<!-- HISTÓRICO MÉDICO -->
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
							date_format(c.datacardapio,'%d/%m/%Y') as datacardapio
							,r.nomerefeicao
							,a.nomealimento
							,c.peso
							,c.energia
							,c.proteina
							,c.lipideo
							,c.carboidrato
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
						left join refeicao r on (c.idrefeicao = r.idrefeicao)
						left join alimento a on (a.idalimento = c.idalimento)
						where f.idfichaanamnese = :idfichaanamnese 
						  and c.datacardapio between :dataInicial and :dataFinal
						  and c.idrefeicao = 1
						order by c.idrefeicao, a.nomealimento";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Café da Manhã</th>
				</tr>
				<tr>
					<td>Data Cardápio</td>
					<td>Alimento</td></td>
					<td>Peso(g)</td>
					<td>Energia</td>
					<td>Proteínas</td>
					<td>Lipídeos</td>
					<td>Carboidratos</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datacardapio	= $linha->datacardapio;
					$nomealimento	= $linha->nomealimento;
					$peso			= $linha->peso;
					$energia		= $linha->energia;
					$proteina		= $linha->proteina;
					$lipideo		= $linha->lipideo;
					$carboidrato	= $linha->carboidrato;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$datacardapio</td>
						<td>$nomealimento</td>
						<td>$peso</td>
						<td>$energia</td>
						<td>$proteina</td>
						<td>$lipideo</td>
						<td>$carboidrato</td>
						</tr>";
				}
			?>
		</table>


		<!-- HISTÓRICO MÉDICO -->
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
							date_format(c.datacardapio,'%d/%m/%Y') as datacardapio
							,r.nomerefeicao
							,a.nomealimento
							,c.peso
							,c.energia
							,c.proteina
							,c.lipideo
							,c.carboidrato
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
						left join refeicao r on (c.idrefeicao = r.idrefeicao)
						left join alimento a on (a.idalimento = c.idalimento)
						where f.idfichaanamnese = :idfichaanamnese 
						  and c.datacardapio between :dataInicial and :dataFinal
						  and c.idrefeicao = 2
						order by c.idrefeicao, a.nomealimento";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Lanche da Manhã</th>
				</tr>
				<tr>
					<td>Data Cardápio</td>
					<td>Alimento</td></td>
					<td>Peso(g)</td>
					<td>Energia</td>
					<td>Proteínas</td>
					<td>Lipídeos</td>
					<td>Carboidratos</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datacardapio	= $linha->datacardapio;
					$nomealimento	= $linha->nomealimento;
					$peso			= $linha->peso;
					$energia		= $linha->energia;
					$proteina		= $linha->proteina;
					$lipideo		= $linha->lipideo;
					$carboidrato	= $linha->carboidrato;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$datacardapio</td>
						<td>$nomealimento</td>
						<td>$peso</td>
						<td>$energia</td>
						<td>$proteina</td>
						<td>$lipideo</td>
						<td>$carboidrato</td>
						</tr>";
				}
			?>
		</table>


		<!-- HISTÓRICO MÉDICO -->
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
							date_format(c.datacardapio,'%d/%m/%Y') as datacardapio
							,r.nomerefeicao
							,a.nomealimento
							,c.peso
							,c.energia
							,c.proteina
							,c.lipideo
							,c.carboidrato
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
						left join refeicao r on (c.idrefeicao = r.idrefeicao)
						left join alimento a on (a.idalimento = c.idalimento)
						where f.idfichaanamnese = :idfichaanamnese 
						  and c.datacardapio between :dataInicial and :dataFinal
						  and c.idrefeicao = 3
						order by c.idrefeicao, a.nomealimento";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Almoço</th>
				</tr>
				<tr>
					<td>Data Cardápio</td>
					<td>Alimento</td></td>
					<td>Peso(g)</td>
					<td>Energia</td>
					<td>Proteínas</td>
					<td>Lipídeos</td>
					<td>Carboidratos</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datacardapio	= $linha->datacardapio;
					$nomealimento	= $linha->nomealimento;
					$peso			= $linha->peso;
					$energia		= $linha->energia;
					$proteina		= $linha->proteina;
					$lipideo		= $linha->lipideo;
					$carboidrato	= $linha->carboidrato;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$datacardapio</td>
						<td>$nomealimento</td>
						<td>$peso</td>
						<td>$energia</td>
						<td>$proteina</td>
						<td>$lipideo</td>
						<td>$carboidrato</td>
						</tr>";
				}
			?>
		</table>
		

		<!-- HISTÓRICO MÉDICO -->
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
							date_format(c.datacardapio,'%d/%m/%Y') as datacardapio
							,r.nomerefeicao
							,a.nomealimento
							,c.peso
							,c.energia
							,c.proteina
							,c.lipideo
							,c.carboidrato
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
						left join refeicao r on (c.idrefeicao = r.idrefeicao)
						left join alimento a on (a.idalimento = c.idalimento)
						where f.idfichaanamnese = :idfichaanamnese 
						  and c.datacardapio between :dataInicial and :dataFinal
						  and c.idrefeicao = 4
						order by c.idrefeicao, a.nomealimento";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Café da Tarde</th>
				</tr>
				<tr>
					<td>Data Cardápio</td>
					<td>Alimento</td></td>
					<td>Peso(g)</td>
					<td>Energia</td>
					<td>Proteínas</td>
					<td>Lipídeos</td>
					<td>Carboidratos</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datacardapio	= $linha->datacardapio;
					$nomealimento	= $linha->nomealimento;
					$peso			= $linha->peso;
					$energia		= $linha->energia;
					$proteina		= $linha->proteina;
					$lipideo		= $linha->lipideo;
					$carboidrato	= $linha->carboidrato;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$datacardapio</td>
						<td>$nomealimento</td>
						<td>$peso</td>
						<td>$energia</td>
						<td>$proteina</td>
						<td>$lipideo</td>
						<td>$carboidrato</td>
						</tr>";
				}
			?>
		</table>

		<!-- HISTÓRICO MÉDICO -->
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
							date_format(c.datacardapio,'%d/%m/%Y') as datacardapio
							,r.nomerefeicao
							,a.nomealimento
							,c.peso
							,c.energia
							,c.proteina
							,c.lipideo
							,c.carboidrato
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
						left join refeicao r on (c.idrefeicao = r.idrefeicao)
						left join alimento a on (a.idalimento = c.idalimento)
						where f.idfichaanamnese = :idfichaanamnese 
						  and c.datacardapio between :dataInicial and :dataFinal
						  and c.idrefeicao = 5
						order by c.idrefeicao, a.nomealimento";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Jantar</th>
				</tr>
				<tr>
					<td>Data Cardápio</td>
					<td>Alimento</td></td>
					<td>Peso(g)</td>
					<td>Energia</td>
					<td>Proteínas</td>
					<td>Lipídeos</td>
					<td>Carboidratos</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datacardapio	= $linha->datacardapio;
					$nomealimento	= $linha->nomealimento;
					$peso			= $linha->peso;
					$energia		= $linha->energia;
					$proteina		= $linha->proteina;
					$lipideo		= $linha->lipideo;
					$carboidrato	= $linha->carboidrato;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$datacardapio</td>
						<td>$nomealimento</td>
						<td>$peso</td>
						<td>$energia</td>
						<td>$proteina</td>
						<td>$lipideo</td>
						<td>$carboidrato</td>
						</tr>";
				}
			?>
		</table>


		<!-- HISTÓRICO MÉDICO -->
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
							date_format(c.datacardapio,'%d/%m/%Y') as datacardapio
							,r.nomerefeicao
							,a.nomealimento
							,c.peso
							,c.energia
							,c.proteina
							,c.lipideo
							,c.carboidrato
						from pessoa p
						left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
						left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese)
						left join refeicao r on (c.idrefeicao = r.idrefeicao)
						left join alimento a on (a.idalimento = c.idalimento)
						where f.idfichaanamnese = :idfichaanamnese 
						  and c.datacardapio between :dataInicial and :dataFinal
						  and c.idrefeicao = 6
						order by c.idrefeicao, a.nomealimento";
				$consulta = $pdo->prepare( $sql );
				$consulta->bindValue(":idfichaanamnese",$idfichaanamnese);
				$consulta->bindValue(":dataInicial",$dataInicial);
				$consulta->bindValue(":dataFinal",$dataFinal);
				// $consulta->bindValue(":dataInicial",$dataInicial);
				// $consulta->bindValue(":dataFinal",$dataFinal);
				$consulta->execute();
			?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Ceia</th>
				</tr>
				<tr>
					<td>Data Cardápio</td>
					<td>Alimento</td></td>
					<td>Peso(g)</td>
					<td>Energia</td>
					<td>Proteínas</td>
					<td>Lipídeos</td>
					<td>Carboidratos</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$datacardapio	= $linha->datacardapio;
					$nomealimento	= $linha->nomealimento;
					$peso			= $linha->peso;
					$energia		= $linha->energia;
					$proteina		= $linha->proteina;
					$lipideo		= $linha->lipideo;
					$carboidrato	= $linha->carboidrato;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$datacardapio</td>
						<td>$nomealimento</td>
						<td>$peso</td>
						<td>$energia</td>
						<td>$proteina</td>
						<td>$lipideo</td>
						<td>$carboidrato</td>
						</tr>";
				}
			?>
		</table>

	</div>
</body>
</html>