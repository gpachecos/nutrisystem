<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis de pessoa
	$idpessoa = $nome = "";

	//verificar o id - $p[2]
	if ( isset ( $p[2] ) ) {
		
		//selecionar os dados conforme o id
		$sql = "select idpessoa, nome from pessoa 
			where idpessoa = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

        $idpessoa 		= $dados->idpessoa;
        $nome 			= $dados->nome;
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
							class="form-control" readonly value="<?=$idpessoa;?>">
						</div>
						<div class="col-12 col-md-5">
							<label for="nome">Nome da Pessoa:</label>
							<input type="text" name="nome" 
							class="form-control" value="<?=$nome;?>"
							required data-parsley-required-message="Preencha este campo" >
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="coluna">
		<h1 class="float-left">Cadastro de Alimentos</h1>

		<div class="float-right">
			<a href="cadastros/alimento" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/alimento" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>

		</div>


		<div class="clearfix"></div>
		<hr>
		<div class="alert alert-warning" role="alert">
			<strong>Atenção:</strong>
			" Cadastre os valores correspondentes a 100 gramas do alimento."
		</div>

		<form name="cadastro" method="post" action="salvar/alimento" enctype="multipart/form-data"
		data-parsley-validate>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="idalimento">ID Alimento:</label>
					<input type="text" name="idalimento" 
					class="form-control" readonly value="<?=$idalimento;?>">
				</div>
				<div class="col-12 col-md-10">
					<label for="nomealimento">Nome do Alimento:</label>
					<input type="text" name="nomealimento" 
					class="form-control" value="<?=$nomealimento;?>"
					required data-parsley-required-message="Preencha este campo" >
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-12 col-md-3">
					<label for="energia">Energia:</label>
					<input type="text" name="energia" 
					class="form-control" value="<?=$energia;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-3">
					<label for="proteina">Proteina:</label>
					<input type="text" name="proteina" 
					class="form-control" value="<?=$proteina;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-3">
					<label for="lipideo">Lipídeo:</label>
					<input type="text" name="lipideo" 
					class="form-control" value="<?=$lipideo;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
				<div class="col-12 col-md-3">
					<label for="carboidrato">Carboidrato:</label>
					<input type="text" name="carboidrato" 
					class="form-control" value="<?=$carboidrato;?>"
					required data-parsley-required-message="Preencha este campo">
				</div>
			</div>

			<br>
			<label for="idcategoriaalimento">Categoria Alimento:</label>
			<select name="idcategoriaalimento" id="idcategoriaalimento" 
			class="form-control" required 
			data-parsley-required-message="Selecione uma opção">
				<option value="">Selecione</option>
				<?php
					$tabela =	"categoriaalimentos";
					$id 	= 	"idcategoriaalimento";
					$campo	= 	"nomecategoria";	 
					carregarOpcoes($tabela,$id,$campo,$pdo);
				?>
			</select>

			<br>			

			<button type="submit" class="btn btn-success">
				Salvar/Alterar
			</button>

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

		//selecionar o id da categoria do alimento
		$("#idcategoriaalimento").val(<?=$idcategoriaalimento;?>);
	})
</script>

