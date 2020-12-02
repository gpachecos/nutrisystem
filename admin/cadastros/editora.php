<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//adicionando vazio no id e no tipo
	$id = $nome = $site = "";

	//$p[1] -> index.php (id do registro)
	if ( isset ( $p[2] ) ) {

		//selecionar os dados conforme o id
		$sql = "select * from editora 
			where id = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		$id 	= $dados->id;
		$nome 	= $dados->nome;
		$site 	= $dados->site;

	}

?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cadastro de Editoras</h1>

		<div class="float-right">
			<a href="cadastros/editora" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/editora" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<form name="cadastro" method="post" action="salvar/editora" data-parsley-validate>

			<label for="id">ID:</label>
			<input type="text" name="id" 
			class="form-control" readonly
			value="<?=$id;?>">

			<br>
			<label for="nome">Editora:</label>
			<input type="text" name="nome" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$nome;?>">

			<br>
			<label for="site">Site:</label>
			<input type="text" name="site" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$site;?>">

			<br>
			<button type="submit" class="btn btn-success">
				<i class="fas fa-check"></i> Gravar
			</button>

		</form>

	</div>
</div>