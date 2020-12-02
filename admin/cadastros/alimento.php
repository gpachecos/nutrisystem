<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis
	$idalimento = $idcategoriaalimento = $nomealimento = $energia = $proteina = $lipideo = $carboidrato = "";

	//verificar o id - $p[2]
	if ( isset ( $p[2] ) ) {
		//se enviado um id
		$idalimento = $p[2];

		//select para selecionar o registro
		$sql = "select * from alimento 
			where idalimento = ? limit 1";
		$consulta = $pdo->prepare($sql);
		$consulta->bindParam(1, $p[2]);
		$consulta->execute();

		//separar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		$nomealimento			= $dados->nomealimento;
		$energia				= $dados->energia;
		$proteina 				= $dados->proteina;
		$lipideo 				= $dados->lipideo;
		$carboidrato 			= $dados->carboidrato;
		$idcategoriaalimento 	= $dados->idcategoriaalimento;

		// $valor = number_format($valor, 2, ",", ".");
	}

?>
<div class="container">
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

