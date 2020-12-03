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
		

		<form name="cadastro" method="post" action="salvar/exames" data-parsley-validate enctype="multipart/form-data">

			
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
		$nomeexame = $dataexame = $referencia = $alteracao = $arqexame = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			$sql = "select e.*
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join exame e on (f.idfichaanamnese = e.idfichaanamnese) 
					where f.idfichaanamnese = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
			$nomeexame		    = $dados->nomeexame; 
            $dataexame			= $dados->dataexame; 
            $referencia		    = $dados->referencia; 
            $alteracao			= $dados->alteracao;
            $arqexame			= $dados->arqexame;
		}
	
	?>

	
		<h1 class="float-left">Hábitos Alimentares</h1>

        <div class="clearfix"></div>
        <br>

			<div class="row">
                <div class="col-12 col-md-2">
                    <label for="dataexame">Data do Exame:</label>
                    <input type="text" name="dataexame" required
                    class="form-control" data-mask="99/99/9999" 
                    value="<?=$dataexame;?>">
                </div>
                <br>
                <div class="col-12 col-md-3">
					<label for="nomeexame">Nome do Exame:</label>
					<input type="text" name="nomeexame" 
					class="form-control" value="<?=$nomeexame;?>"
					required data-parsley-required-message="Preencha este campo" >
                </div>
                <br>
                <div class="col-12 col-md-3">
					<label for="referencia">Referência:</label>
					<input type="text" name="referencia" 
					class="form-control" value="<?=$referencia;?>"
					required data-parsley-required-message="Preencha este campo" >
                </div>
                <br>
                <div class="col-12 col-md-4">
					<label for="alteracao">Alteração :</label>
					<input type="text" name="alteracao" 
					class="form-control" value="<?=$alteracao;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
            </div>
            <br>
            <div class="row">
                
                <?php //if ($msg != false) echo "<div class=\"alert $class\" role=\"alert\">$msg</div>"; ?>
        
                <div class="col 12 col-md-auto">
                    <input type="hidden" name="enviou" value="1">
                    Arquivo PDF:<br>
                    <input type="file" name="arquivo" class="form-control">
                    <?php //if ($msg != false) echo "<div class=\"alert $class\" role=\"alert\">$msg</div>"; ?>
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

