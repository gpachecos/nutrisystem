<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	$idcidade = $cidade = $estado = "";

	//$p[1] -> index.php (id do registro)
	if ( isset ( $p[2] ) ) {

		//selecionar os dados conforme o id
		$sql = "select * from cidade 
			where idcidade = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		$idcidade 		= $dados->idcidade;
		$cidade 	= $dados->cidade;
		$estado 	= $dados->estado;

	}
?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cadastro de Cidades</h1>

		<div class="float-right">
			<a href="cadastros/cidade" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/cidade" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<form name="cadastro" method="post" action="salvar/cidade" data-parsley-validate>

			<label for="idcidade">ID:</label>
			<input type="text" name="idcidade" class="form-control" readonly	value="<?=$idcidade;?>">

			<br>
			<label for="cidade">Nome da Cidade:</label>
			<input type="text" name="cidade" required class="form-control" data-parsley-required-message="Preencha este campo" value="<?=$cidade;?>">

			<br>
			<label for="estado">Estado:</label>
			<select name="estado" id="estado" required class="form-control" data-parsley-required-message="Selecione uma opção">
				<option value=""></option>
				<option value="AC">AC</option>
				<option value="AL">AL</option>
				<option value="AM">AM</option>
				<option value="AP">AP</option>
				<option value="BA">BA</option>
				<option value="CE">CE</option>
				<option value="DF">DF</option>
				<option value="ES">ES</option>
				<option value="GO">GO</option>
				<option value="MA">MA</option>
				<option value="MG">MG</option>
				<option value="MS">MS</option>
				<option value="MT">MT</option>
				<option value="PA">PA</option>
				<option value="PB">PB</option>
				<option value="PE">PE</option>
				<option value="PI">PI</option>
				<option value="PR">PR</option>
				<option value="RJ">RJ</option>
				<option value="RN">RN</option>
				<option value="RO">RO</option>
				<option value="RR">RR</option>
				<option value="RS">RS</option>
				<option value="SC">SC</option>
				<option value="SE">SE</option>
			</select>

			<br>
			<button type="submit" class="btn btn-success">
				<i class="fas fa-check"></i> Gravar
			</button>
		</form>

		<script type="text/javascript">
			$("#estado").val('<?=$estado;?>');
		</script>
	</div>
</div>