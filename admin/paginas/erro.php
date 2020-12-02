<?php
	//verificar se existe uma variavel $pagina
	if ( !isset ( $pagina ) ) {
		header("Location: index.php");
	}

?>

<div class="container">
	<h1 class="text-center">
		Página não Encontrada!
	</h1>
	<p class="text-center">
		A página que está tentando acessar não existe!
	</p>
</div>