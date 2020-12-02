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
			select p.idpessoa, p.nome , f.idfichaanamnese, f.dataficha
			from pessoa p 
			left join fichaanamnese f on (p.idpessoa = f.idpessoa) 
			where p.idpessoa = ? limit 1";
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
	<div class="accordion" id="accordionExample">
		<div class="card">
			<div class="card-header" id="headingOne">
				<h5 class="float-left">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					Grupo de itens colapsável #1
					</button>
				</h5>
				<div class="float-right">

					<a href="listar/pessoaFicha" class="btn btn-info">
						<i class="fas fa-search"></i> Consultar
					</a>

				</div>
			</div>

			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-2">
							<label for="idpessoa">ID Pessoa:</label>
							<input type="text" name="idpessoa" 
							class="form-control" value="<?=$idpessoa;?>">
						</div>
						<div class="col-12 col-md-5">
							<label for="nome">Nome da Pessoa:</label>
							<input type="text" name="nome" id="nome" readonly
							class="form-control">
							<?php
								$tabela =	"pessoa";
								$id 	= 	"idpessoa";
								$campo	= 	"nome";	 
								$valor  =   $idpessoa;
								carregarOpcoesFicha($tabela,$id,$campo,$valor,$pdo);
							?>
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
				</div>
			</div>
		</div>

	</div>


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
					where p.idpessoa = ? limit 1";
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

	
	<div class="coluna">
		<h1 class="float-left">Hábitos Cotidianos</h1>

		<div class="clearfix"></div>
		<hr>
		
		<form name="cadastro" method="post" action="salvar/alimento" enctype="multipart/form-data"
		data-parsley-validate>
		
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
					<label for="ingbebidaalcool ">Ingere Bebida Alcoólica:</label>
					<select name="ingbebidaalcool " id="ingbebidaalcool "
					class="form-control" required  data-parsley-required-message="Selecione uma Restrição">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#ingbebidaalcool ").val('<?=$ingbebidaalcool ;?>')
					</script>
				</div>
				<div class="col-12 col-md-9">
					<label for="freqbebidaalcool ">Frequência:</label>
					<input type="text" name="freqbebidaalcool " 
					class="form-control" value="<?=$freqbebidaalcool ;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="fumante">Fumante:</label>
					<select name="fumante " id="fumante "
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#fumante ").val('<?=$fumante ;?>')
					</script>
				</div>
				<div class="col-12 col-md-10">
					<label for="freqfumo">Frequência:</label>
					<input type="text" name="freqfumo " 
					class="form-control" value="<?=$freqfumo ;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<div class="clearfix"></div>
			<hr>
			<h3>Hábitos de Compras</h3>

			<br>
			<div class="row">
				<div class="col-12 col-md-4">
					<label for="qdtemoradorescasa">Quantas pessoas vivem na casa?</label>
					<input type="text" name="qdtemoradorescasa" 
					class="form-control" value="<?=$qdtemoradorescasa;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-4">
					<label for="quemfazcompra">Quem realiza as compras?</label>
					<input type="text" name="quemfazcompra" 
					class="form-control" value="<?=$quemfazcompra;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-4">
					<label for="localcompra">Local das Compras:</label>
					<input type="text" name="localcompra" 
					class="form-control" value="<?=$localcompra;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-12 col-md-4">
					<label for="freqcompra">Quantas vezes por mês?</label>
					<input type="text" name="freqcompra" 
					class="form-control" value="<?=$freqcompra;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-4">
					<label for="litrosoleo">Quantos litros de Óleo?</label>
					<input type="text" name="litrosoleo" 
					class="form-control" value="<?=$litrosoleo;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-4">
					<label for="quilossal">Quantos quilos de Sal?:</label>
					<input type="text" name="quilossal" 
					class="form-control" value="<?=$quilossal;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
			</div>

			<div class="clearfix"></div>
			<hr>
			<h3>Hábitos de Sono</h3>
			
			<br>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="qualidadesono">Dorme bem?</label>
					<select name="qualidadesono" id="qualidadesono"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#qualidadesono").val('<?=$qualidadesono;?>')
					</script>
				</div>
				<div class="col-12 col-md-10">
					<label for="horassono">Quantas horas de sono?</label>
					<input type="text" name="horassono" 
					class="form-control" value="<?=$horassono;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-12 col-md-12">
					<label for="observacaosono">Alguma observação sobre o sono?</label>
					<input type="text" name="observacaosono" 
					class="form-control" value="<?=$observacaosono;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<div class="clearfix"></div>
			<hr>
			<h3>Exercícios Físicos</h3>
			
			<br>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="praticaexercfisico">Pratica Exercícios?</label>
					<select name="praticaexercfisico" id="praticaexercfisico"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
						<option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#praticaexercfisico").val('<?=$praticaexercfisico;?>')
					</script>
				</div>
				<div class="col-12 col-md-10">
					<label for="exerciciofisico">Quais?</label>
					<input type="text" name="exerciciofisico" 
					class="form-control" value="<?=$exerciciofisico;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-12 col-md-6">
					<label for="freqexercfisico">Quantas vezes na semana?</label>
					<input type="text" name="freqexercfisico" 
					class="form-control" value="<?=$freqexercfisico;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
				<div class="col-12 col-md-6">
					<label for="tempoexercfisico ">Quanto tempo?</label>
					<input type="text" name="tempoexercfisico " 
					class="form-control" value="<?=$tempoexercfisico ;?>"
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

			//selecionar o id da categoria do alimento
			$("#nome").val(<?=$nome;?>);
</script>

