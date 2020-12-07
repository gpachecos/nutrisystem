<?php
	//verificar se existe uma variavel $pagina
	if ( !isset ( $pagina ) ) {
		header("Location: index.php");
	}

?>
<div class="row">
	<div class="card" style="width: 18rem;">
	<div class="card-body">
		<h5 class="card-title"><strong>Ficha Anamnese</strong></h5>
		
		<p class="card-text">Clique aqui para acessar diretamente a Ficha Anamnese.</p>
		<a href="cadastros/fichaanamnese" class="card-link">Ficha Anamnese</a>
	</div>
	</div>

	<div class="card" style="width: 18rem;">
	<div class="card-body">
		<h5 class="card-title"><strong>Avaliação Antropométrica</strong></h5>
		
		<p class="card-text">Clique aqui para acessar diretamente a Avaliação Antropométrica.</p>
		<a href="cadastros/avaliacaoantropometrica" class="card-link">Avaliação Antropométrica</a>
	</div>
	</div>

	<div class="card" style="width: 18rem;">
	<div class="card-body">
		<h5 class="card-title"><strong>Cardápio</strong></h5>
		
		<p class="card-text">Clique aqui para acessar diretamente o Cardápio.</p>
		<a href="cadastros/cardapio" class="card-link">Cardápio</a>
	</div>
	</div>
	<div class="card" style="width: 18rem;">
	<div class="card-body">
		<h5 class="card-title"><strong>Relatórios</strong></h5>
		
		<p class="card-text">Clique aqui para acessar diretamente os Relatórios desejados.</p>
		
		<a href="relatorios/telaRelFicha" class="card-link">Ficha Anamnese</a>
		<br>
		<br>
		<a href="relatorios/telaRelAvAntropometrica" class="card-link">Avaliação Antropométrica</a>
		<br>
		<br>
		<a href="relatorios/telaRelCardapio" class="card-link">Cardápio</a>
	</div>
	</div>
</div>  	


<!-- <div class="container"> -->
	<!-- <img src="images/nutrisystem_erro.png" alt="Sistema de Avaliação Nutricional" title="Sistema de Avaliação Nutricional"> -->
	<!-- <h1 class="text-center">
		Página não Encontrada!
	</h1>
	<p class="text-center">
		A página que está tentando acessar não existe!
	</p> -->
<!-- </div> -->