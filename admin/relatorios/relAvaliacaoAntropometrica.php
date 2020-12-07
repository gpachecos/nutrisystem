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
		<h3 class="text-center"><strong>Avaliação Antropométrica</strong></h3>
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
				$sql = "select aa.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
				from pessoa p
				left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
				left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
				where f.idfichaanamnese = :idfichaanamnese and aa.dataavaliacao between :dataInicial and :dataFinal ";
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
					<th>Avaliação Antropométrica</th>
				</tr>
				<tr>
					<td>Data Avaliação</td>
					<td>Altura</td></td>
					<td>Peso Atual</td>
					<td>Peso Ideal</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$dataavaliacao	= $linha->dataavaliacao;
					$altura			= $linha->altura;
					$pesoatual		= $linha->pesoatual;
					$pesoideal		= $linha->pesoideal;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
							<td>$dataavaliacao</td>
							<td>$altura</td>
							<td>$pesoatual</td>
							<td>$pesoideal</td>
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
				$sql = "select aa.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
				from pessoa p
				left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
				left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
				where f.idfichaanamnese = :idfichaanamnese and aa.dataavaliacao between :dataInicial and :dataFinal ";
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
					<th>Membros Superiores</th>
				</tr>
				<tr>
					<td>Data Avaliação</td>
					<td>Braço Direito Relaxado</td>
					<td>Braço Direito Contraído</td>
					<td>Antebraço Direito</td>
					<td>Punho Direito</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$dataavaliacao	= $linha->dataavaliacao;
					$bracodireitorelaxado	= $linha->bracodireitorelaxado;
					$bracodireitocontraido	= $linha->bracodireitocontraido;
					$antebracodireito	= $linha->antebracodireito;
					$punhodireito	= $linha->punhodireito;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$dataavaliacao</td>
						<td>$bracodireitorelaxado</td>
						<td>$bracodireitocontraido</td>
						<td>$antebracodireito</td>
						<td>$punhodireito</td>
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
				$sql = "select aa.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
				from pessoa p
				left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
				left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
				where f.idfichaanamnese = :idfichaanamnese and aa.dataavaliacao between :dataInicial and :dataFinal ";
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
					<th>Tronco</th>
				</tr>
				<tr>
					<td>Data Avaliação</td>
					<td>Ombro</td>
					<td>Tórax</td>
					<td>Cintura</td>
					<td>Abdômem</td>
					<td>Quadril</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$dataavaliacao	= $linha->dataavaliacao;
					$ombro	= $linha->ombro;
					$torax	= $linha->torax;
					$cintura	= $linha->cintura;
					$abdomen	= $linha->abdomen;
					$quadril	= $linha->quadril;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$dataavaliacao</td>
						<td>$bracodireitorelaxado</td>
						<td>$bracodireitocontraido</td>
						<td>$antebracodireito</td>
						<td>$punhodireito</td>
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
				$sql = "select aa.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
				from pessoa p
				left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
				left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
				where f.idfichaanamnese = :idfichaanamnese and aa.dataavaliacao between :dataInicial and :dataFinal ";
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
					<th>Membros Inferiores</th>
				</tr>
				<tr>
					<td>Data Avaliação</td>
					<td>Panturrilha Direita</td>
					<td>Coxa Direita</td>
					<td>Coxa Proximal Direita</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$dataavaliacao	= $linha->dataavaliacao;
					$panturrilhadireita	= $linha->panturrilhadireita;
					$coxadireita	= $linha->coxadireita;
					$coxaproximaldireita	= $linha->coxaproximaldireita;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$dataavaliacao</td>
						<td>$panturrilhadireita</td>
						<td>$coxadireita</td>
						<td>$coxaproximaldireita</td>
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
				$sql = "select aa.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
				from pessoa p
				left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
				left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
				where f.idfichaanamnese = :idfichaanamnese and aa.dataavaliacao between :dataInicial and :dataFinal ";
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
					<th>Pregas Cutâneas</th>
				</tr>
				<tr>
					<td>Data Avaliação</td>
					<td>Bíceps</td>
					<td>Tríceps</td>
					<td>Subescapular</td>
					<td>Axilar Média</td>
					<td>Toráxica</td>
					<td>Supra-ilíaca</td>
					<td>Abdominal</td>
					<td>Coxa</td>
					<td>Panturrilha Média</td>
				</tr>
			</thead>
			
			<?php
				while ( $linha = $consulta->fetch(PDO::FETCH_OBJ) )
				{
					//recuperar as variaveis
					$dataavaliacao	= $linha->dataavaliacao;
					$biceps	= $linha->biceps;
					$triceps	= $linha->triceps;
					$subescapular	= $linha->subescapular;
					$axilarmedia	= $linha->axilarmedia;
					$toraxica	= $linha->toraxica;
					$suprailiaca	= $linha->suprailiaca;
					$abdominal	= $linha->abdominal;
					$coxa	= $linha->coxa;
					$panturrilhamedia	= $linha->panturrilhamedia;
					$dataavaliacao	= $linha->dataavaliacao;

					//mostrar os dados dentro da linha da tabela (tr)
					echo "<tr>
						<td>$dataavaliacao</td>
						<td>$biceps</td>
						<td>$triceps</td>
						<td>$subescapular</td>
						<td>$axilarmedia</td>
						<td>$toraxica</td>
						<td>$suprailiaca</td>
						<td>$abdominal</td>
						<td>$coxa</td>
						<td>$panturrilhamedia</td>
						<td>$dataavaliacao</td>
						</tr>";
				}
			?>
		</table>



	</div>
</body>
</html>