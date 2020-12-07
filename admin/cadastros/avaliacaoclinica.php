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

			<!-- <a href="listar/pessoaFicha" class="btn btn-info">
				<i class="fas fa-search"></i> Consultar
			</a> -->

		</div>
			
		<div class="clearfix"></div>
		

		<form name="cadastro" method="post" action="salvar/avaliacaoclinica" data-parsley-validate enctype="multipart/form-data">

			
					<div class="row">
						<div class="col-12 col-md-2">
							 <label for="idpessoa">ID Pessoa:</label>  
							<input type="text" name="idpessoa" id="idpessoa" readonly
							class="form-control" value="<?=$idpessoa;?>">
						</div>
						<div class="col-12 col-md-5">
							<label for="nome">Nome da Pessoa:</label>
							<input list="nomes" id="nome" readonly
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
							class="form-control" data-mask="99/99/9999" readonly
							value="<?=$dataficha;?>">
						</div>
					</div>
			

<br>

<hr>

	<?php
		/********  TRAZER DADOS DA FICHA DE HABITOS COTIDIANOS  ********/

		//inicializar as variaveis dos habitos cotidianos
        $apetite = $mastigacao = $tempomastigacao = $habitointestinal = $frequenciaevacuacao = 
        $usodelaxante = $sanguefezes = $temposaguefezes = $habitourinario = $ingestaohidricadiaria = $corurina = $ultimamenstruacao = 
        $tpm = $clicomenstrualregular = $contraceptivo = $colica = $lactante = $menopausa = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			$sql = "select ac.*
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join avaliacaoclinica ac on (f.idfichaanamnese = ac.idfichaanamnese) 
					where f.idfichaanamnese = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
            $apetite				= $dados->apetite;
            $mastigacao				= $dados->mastigacao;
            $tempomastigacao		= $dados->tempomastigacao;
            $habitointestinal		= $dados->habitointestinal;
            $frequenciaevacuacao	= $dados->frequenciaevacuacao;
            $usodelaxante			= $dados->usodelaxante;
            $sanguefezes			= $dados->sanguefezes;
            $temposaguefezes		= $dados->temposaguefezes;
            $habitourinario			= $dados->habitourinario;
            $ingestaohidricadiaria	= $dados->ingestaohidricadiaria;
            $corurina				= $dados->corurina;
            $ultimamenstruacao		= $dados->ultimamenstruacao;
            $tpm					= $dados->tpm;
            $clicomenstrualregular	= $dados->clicomenstrualregular;
            $contraceptivo			= $dados->contraceptivo;
            $colica					= $dados->colica;
            $lactante				= $dados->lactante;
			$menopausa				= $dados->menopausa;

			$ingestaohidricadiaria = number_format($ingestaohidricadiaria, 2, ",", ".");
			
		}
	
	?>

	
		<h1 class="float-left">Avaliação Clínica</h1>

        <div class="clearfix"></div>
        <br>

			<div class="row">
				<div class="col-12 col-md-4">
					<label for="apetite">Apetite:</label>
					<select name="apetite" id="apetite"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Normal">Normal</option>
                        <option value="Aumentado">Aumentado</option>
                        <option value="Diminuído">Diminuído</option>
					</select>
					<script type="text/javascript">
						$("#apetite").val('<?=$apetite;?>')
					</script>
                </div>
                <div class="col-12 col-md-4">
					<label for="mastigacao">Mastigação:</label>
					<select name="mastigacao" id="mastigacao"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Normal">Normal</option>
                        <option value="Rápida">Rápida</option>
                        <option value="Lenta">Lenta</option>
					</select>
					<script type="text/javascript">
						$("#mastigacao").val('<?=$mastigacao;?>')
					</script>
				</div>
				<div class="col-12 col-md-4">
					<label for="tempomastigacao">Quanto tempo?</label>
					<input type="text" name="tempomastigacao" 
					class="form-control" value="<?=$tempomastigacao;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
            </div>
            
            <br>
            <div class="row">
				<div class="col-12 col-md-4">
					<label for="habitointestinal">Hábito Intestinal:</label>
					<select name="habitointestinal" id="habitointestinal"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Normal">Normal</option>
                        <option value="Constipante">Constipante</option>
                        <option value="Diarreico">Diarreico</option>
                        <option value="Variado">Variado</option>
					</select>
					<script type="text/javascript">
						$("#habitointestinal").val('<?=$habitointestinal;?>')
					</script>
                </div>
                <div class="col-12 col-md-4">
					<label for="frequenciaevacuacao">Frequência de Evacuação</label>
					<input type="text" name="frequenciaevacuacao" 
					class="form-control" value="<?=$frequenciaevacuacao;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
                <div class="col-12 col-md-4">
					<label for="usodelaxante">Faz uso de laxante?</label>
					<select name="usodelaxante" id="usodelaxante"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
                        <option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#usodelaxante").val('<?=$usodelaxante;?>')
					</script>
				</div>	
			</div>
			
			<br>
			<div class="row">
                <div class="col-12 col-md-4">
					<label for="sanguefezes">Há presença de sangue nas fezes?</label>
					<select name="sanguefezes" id="sanguefezes"
					class="form-control" required  data-parsley-required-message="Selecione uma Opção">
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
                        <option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#sanguefezes").val('<?=$sanguefezes;?>')
					</script>
				</div>
                <div class="col-12 col-md-8">
					<label for="temposaguefezes">Há quanto tempo?</label>
					<input type="text" name="temposaguefezes" 
					class="form-control" value="<?=$temposaguefezes;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>				
            </div>
            
            <br>
            <div class="row">
                <div class="col-12 col-md-4">
					<label for="habitourinario">Hábito Urinário?</label>
					<input type="text" name="habitourinario" 
					class="form-control" value="<?=$habitourinario;?>"
					required data-parsley-required-message="Preencha este campo" >
                </div>
                <div class="col-12 col-md-4">
					<label for="ingestaohidricadiaria">Ingestão Hídrica Diária</label>
					<input type="text" name="ingestaohidricadiaria" id="ingestaohidricadiaria"
					class="form-control" value="<?=$ingestaohidricadiaria;?>"
					required data-parsley-required-message="Preencha este campo" >
                </div>				
                <div class="col-12 col-md-4">
					<label for="corurina">Cor Urina</label>
					<input type="text" name="corurina" 
					class="form-control" value="<?=$corurina;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>				
            </div>
            
            <hr>
            <h4><strong>Mulheres</strong></h4>
            <hr>
			<div class="row">
                
                <div class="col-12 col-md-5">
					<label for="ultimamenstruacao">Última Menstrução</label>
					<input type="text" name="ultimamenstruacao" 
					class="form-control" value="<?=$ultimamenstruacao;?>">
				</div>	
                <div class="col-12 col-md-3">
					<label for="tpm">TPM</label>
					<select name="tpm" id="tpm"
					class="form-control" >
						<option value="">Selecione</option>
						<option value="Forte">Forte</option>
                        <option value="Média">Média</option>
                        <option value="Fraca">Fraca</option>
					</select>
					<script type="text/javascript">
						$("#tpm").val('<?=$tpm;?>')
					</script>
                </div>
                <div class="col-12 col-md-4">
					<label for="clicomenstrualregular">Ciclo Menstrual Regular?</label>
					<select name="clicomenstrualregular" id="clicomenstrualregular"
					class="form-control" >
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
                        <option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#clicomenstrualregular").val('<?=$clicomenstrualregular;?>')
					</script>
				</div>
            </div>
            
            <br>
            <div class="row">
                <div class="col-12 col-md-3">
					<label for="contraceptivo">Contraceptivo</label>
					<input type="text" name="contraceptivo" 
					class="form-control" value="<?=$contraceptivo;?>">
                </div>
                <div class="col-12 col-md-3">
					<label for="colica">Cólica?</label>
					<select name="colica" id="colica"
					class="form-control" >
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
                        <option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#colica").val('<?=$colica;?>')
					</script>
                </div>
                <div class="col-12 col-md-3">
					<label for="lactante">Lactante</label>
					<select name="lactante" id="lactante"
					class="form-control" >
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
                        <option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#lactante").val('<?=$lactante;?>')
					</script>
                </div>
                <div class="col-12 col-md-3">
					<label for="menopausa">Menopausa?</label>
					<select name="menopausa" id="menopausa"
					class="form-control" >
						<option value="">Selecione</option>
						<option value="Sim">Sim</option>
                        <option value="Não">Não</option>
					</select>
					<script type="text/javascript">
						$("#menopausa").val('<?=$menopausa;?>')
					</script>
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
		$("#ingestaohidricadiaria").maskMoney({
			thousands: ".",
			decimal: ","
		});
	})

	function selecionaPessoa(nome) {
				nome = nome.split(" - ");
				$("#idpessoa").val(nome[0])
			}
</script>

