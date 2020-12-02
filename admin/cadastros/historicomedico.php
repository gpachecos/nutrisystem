<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis de pessoa
	$idpessoa = $nome = $idfichaanamnese = $dataficha = "";

	//verificar o id - $p[2]
	if ( isset ( $p[2] ) ) {
		
		//selecionar os dados conforme o id
		$sql = "
			select p.idpessoa, p.nome , f.idfichaanamnese, date_format(f.dataficha,'%d/%m/%Y') as dataficha
			from pessoa p 
			left join fichaanamnese f on (p.idpessoa = f.idpessoa) 
			where f.idfichaanamnese = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

        $idpessoa 			= $dados->idpessoa;
		$nome 				= $dados->nome;
		$idfichaanamnese 	= $dados->idfichaanamnese;
		$dataficha 			= $dados->dataficha;

		
	}

?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Ficha de Anamnese</h1>
		
		<div class="float-right">

			<a href="listar/pessoaFicha" class="btn btn-info">
				<i class="fas fa-search"></i> Consultar
			</a>

		</div>
			
		<div class="clearfix"></div>
		

		<form name="cadastro" method="post" action="salvar/habitoscotidianos" data-parsley-validate enctype="multipart/form-data">

			
					<div class="row">
						<div class="col-12 col-md-2">
							 <label for="idpessoa">ID Pessoa:</label>  
							<input type="text" name="idpessoa" id="idpessoa" readonly
							class="form-control" value="<?=$idpessoa;?>">
						</div>
						<div class="col-12 col-md-5">
							<label for="nome">Nome da Pessoa:</label>
							<input list="nomes" id="nome" required data-parsley-required-message="Selecione uma Pessoa"
							class="form-control" onblur="selecionaPessoa(this.value)" value="<?=$nome;?>">

							<datalist id="nomes">
								<?php
									$sql = "select idpessoa, nome from pessoa order by nome";
									$consulta = $pdo->prepare( $sql );
									$consulta->execute();
									//recuperar os dados
									while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {

										echo "<option>$dados->idpessoa - $dados->nome</option>";

									}

								?>
							</datalist>

						</div>
						<div class="col-12 col-md-2">
							<label for="idfichaanamnese">Registro:</label>
							<input type="text" name="idfichaanamnese" 
							class="form-control" readonly
							value="<?=$idfichaanamnese;?>">
						</div>
						<div class="col-12 col-md-2">
							<label for="dataficha">Data da Ficha:</label>
							<input type="text" name="dataficha" required
							class="form-control" data-mask="99/99/9999"
							data-parsley-required-message="Preencha este campo"
							value="<?=$dataficha;?>">
						</div>
					</div>
			

<br>

<hr>

	<?php
		/********  TRAZER DADOS DA FICHA DE HABITOS COTIDIANOS  ********/

		//inicializar as variaveis dos habitos cotidianos
		$restricaoalimentar = $descrestricao = $ingbebidaalcool = $freqbebidaalcool = $fumante = $freqfumo = 
		$qdtemoradorescasa = $quemfazcompra = $localcompra = $freqcompra = $litrosoleo = $quilossal = $qualidadesono = 
		$horassono = $observacaosono = $praticaexercfisico = $exerciciofisico = $freqexercfisico = $tempoexercfisico = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			$sql = "select hc.*
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join habitoscotidiano hc on (f.idfichaanamnese = hc.idfichaanamnese) 
					where f.idfichaanamnese = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
			$restricaoalimentar		= $dados->restricaoalimentar;
			$descrestricao 			= $dados->descrestricao;
			$ingbebidaalcool 		= $dados->ingbebidaalcool;
			$freqbebidaalcool 		= $dados->freqbebidaalcool;
			$fumante 				= $dados->fumante;
			$freqfumo 				= $dados->freqfumo;
			$qdtemoradorescasa 		= $dados->qdtemoradorescasa;
			$quemfazcompra 			= $dados->quemfazcompra;
			$localcompra 			= $dados->localcompra;
			$freqcompra 			= $dados->freqcompra;
			$litrosoleo 			= $dados->litrosoleo;
			$quilossal 				= $dados->quilossal;
			$qualidadesono 			= $dados->qualidadesono;
			$horassono 				= $dados->horassono;
			$observacaosono 		= $dados->observacaosono;
			$praticaexercfisico 	= $dados->praticaexercfisico;
			$exerciciofisico 		= $dados->exerciciofisico;
			$freqexercfisico 		= $dados->freqexercfisico;
			$tempoexercfisico 		= $dados->tempoexercfisico;
		}
	
	?>

	
	<!-- <div class="coluna"> -->
		<h1 class="float-left">Hábitos Cotidianos</h1>

		<div class="clearfix"></div>
		<hr>
		
		<!-- <form name="cadastro" method="post" action="salvar/habitoscotidianos" enctype="multipart/form-data"
		data-parsley-validate> -->
		
			<h3>Hábitos de Vida</h3>
			<br>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="restricaoalimentar">Restrição Alimentar:</label>
					<select name="restricaoalimentar" id="restricaoalimentar"
					class="form-control" required  data-parsley-required-message="Selecione uma Restrição">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#restricaoalimentar").val('<?=$restricaoalimentar;?>')
					</script>
				</div>
				<div class="col-12 col-md-10">
					<label for="descrestricao">Qual Restrição:</label>
					<input type="text" name="descrestricao" 
					class="form-control" value="<?=$descrestricao;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>
			
			<br>
			<div class="row">
				<div class="col-12 col-md-3">
					<label for="ingbebidaalcool">Ingere Bebida Alcoólica:</label>
					<select name="ingbebidaalcool" id="ingbebidaalcool"
					class="form-control" required  data-parsley-required-message="Selecione uma Restrição">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#ingbebidaalcool ").val('<?=$ingbebidaalcool;?>')
					</script>
				</div>
				<div class="col-12 col-md-9">
					<label for="freqbebidaalcool">Frequência:</label>
					<input type="text" name="freqbebidaalcool" 
					class="form-control" value="<?=$freqbebidaalcool;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="fumante">Fumante:</label>
					<select name="fumante" id="fumante"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#fumante ").val('<?=$fumante;?>')
					</script>
				</div>
				<div class="col-12 col-md-10">
					<label for="freqfumo">Frequência:</label>
					<input type="text" name="freqfumo" 
					class="form-control" value="<?=$freqfumo ;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

		

			<br>			

			<div class="col-lg-12" style="text-align: right">
				<button type="submit" class="btn btn-success">
					Salvar/Alterar
				</button>
			</div>

		</form>

	</div> <!--fim da pagina -->
</div> <!-- fim do container -->

<script type="text/javascript">
	$(document).ready(function(){
		//aplicar o summernote
		$("#resumo").summernote({
			placeholder:"Digite o resumo",
			height: 200,
			lang: 'pt-BR'
		});

		//aplica a mascara de valor no campo
		$("#valor").maskMoney({
			thousands: ".",
			decimal: ","
		});
	})

	function selecionaPessoa(nome) {
				nome = nome.split(" - ");
				$("#idpessoa").val(nome[0])
			}
</script>

