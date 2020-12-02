<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//adicionando vazio no id e no tipo
	$id = $tipo = "";

	//$p[1] -> index.php (id do registro)
	if ( isset ( $p[2] ) ) {

		//selecionar os dados conforme o id
		$sql = "select * from tipo 
			where id = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		$id 	= $dados->id;
		$tipo 	= $dados->tipo;

	}

?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cadastro de Tipo de Quadrinhos</h1>

		<div class="float-right">
			<a href="cadastros/tipo-quadrinho" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/tipo-quadrinho" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<form name="cadastro" method="post" action="salvar/tipo-quadrinho" data-parsley-validate>

			<label for="id">ID:</label>
			<input type="text" name="id" 
			class="form-control" readonly
			value="<?=$id;?>">

			<br>
			<label for="tipo">Tipo de quadrinho:</label>
			<input type="text" name="tipo" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$tipo;?>">

			<br>
			<button type="submit" class="btn btn-success">
				<i class="fas fa-check"></i> Gravar
			</button>

		</form>

	</div>
</div>