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

		$idfichaanamnese = $p[2];
		
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
		

		<form name="cadastro" method="post" action="cadastros/historicomedico" data-parsley-validate enctype="multipart/form-data">

			
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

		</form>

			<?php
				//verificar se o id nao esta vazio
				if ( !empty ( $idfichaanamnese ) ) {
			?>
			<hr>
			<div id="patologia">
				<h3>Patologias:</h3>
				<form name="formadd" method="post" action="salvar/patologias.php" data-parsley-validate 
				target="iframe">

					<!-- id do quadrinho -->
					<input type="hidden" name="idfichaanamnese" value="<?=$idfichaanamnese;?>">

					<div class="row">
						<div class="col-8">

							<label for="idpatologia">Selecione a Patologia:</label>
							<select name="idpatologia" class="form-control" required data-parsley-required-message="Selecione um personagem">
								<option value="">Selecione</option>
								<?php
									//chamar função para mostrar as opções
									$tabela ="patologia";
									$id 	= "idpatologia";
									$campo  = "nomepatologia";
									carregarOpcoes($tabela,$id,$campo,$pdo);
								?>
							</select>
						</div>
						<div class="col-4">
							<button type="submit" class="btn btn-success">Inserir</button>
						</div>
					</div>
				</form>

				<iframe name="iframe" width="100%" height="300px" src="salvar/patologias.php?idfichaanamnese=<?=$idfichaanamnese;?>"></iframe>
			</div>
			<?php
				//fechando o id
				}
			?>

			<hr>		

			<button onclick="window.location.href='cadastros/habitosalimentares/<?=$idfichaanamnese?>'" type="button" class="btn btn-success">
				Salvar/Alterar
			</button>

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

		//selecionar o id da editora
		$("#editora_id").val(<?=$editora_id;?>);
		$("#tipo_id").val(<?=$tipo_id;?>);
	});
</script>

