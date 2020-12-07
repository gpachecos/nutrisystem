<?php
	
	if ( !isset ( $pagina ) ) {
		header("Location: index.php");
	}

?>

<header>
	<nav class="navbar navbar-expand-lg fixed-top">
	  <a class="navbar-brand" href="#">
	  	<img src="images/schqs.png" alt="Sistema de Avaliação Nutricional" title="Sistema de Avaliação Nutricional">
	  </a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="menu">
	    <ul class="navbar-nav ml-auto">
	      <!-- <li class="nav-item">
	        <a class="nav-link" href="paginas/home">
	        	<i class="fas fa-tachometer-alt"></i> Dashboard
	        </a>
	      </li> -->

	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="fas fa-edit"></i> Cadastros
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
			  <a class="dropdown-item" href="cadastros/pessoa">Pessoa</a>
	          <a class="dropdown-item" href="cadastros/cidade">Cidade</a>
	        </div>
	      </li>

	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="fas fa-clipboard-list"></i> Ficha Anamnese
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item" href="cadastros/fichaanamnese">Ficha</a>
	        </div>
		  </li>
		  
		  <li class="nav-item">
	        <a class="nav-link" href="cadastros/avaliacaoantropometrica">
	        	<i class="fas fa-utensils"></i> Avaliação Antropométrica
	        </a>
	      </li>

	      <li class="nav-item">
	        <a class="nav-link" href="cadastros/alimento">
	        	<i class="fas fa-utensils"></i> Alimento
	        </a>
	      </li>

	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="fas fa-chart-line"></i> Relatórios
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item" href="relatorios/telaRelFicha">Ficha Anamnese</a>
	          <a class="dropdown-item" href="relatorios/telaAvAntropometrica">Avaliação Antropométrica</a>
	          <a class="dropdown-item" href="relatorios/telaRelCardapio">Cardápio</a>
	        </div>
	      </li>
	    
	      <li class="nav-item menu dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <!-- <img src="../fotos/<?=$_SESSION["hqs"]["foto"];?>p.jpg" class="rounded-circle border" title="<?=$_SESSION["hqs"]["nome"];?>"> -->
	          Olá <?=$_SESSION["nutrisystem"]["login"];?>
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item" href="paginas/dados">Seus dados</a>
	          <a class="dropdown-item" href="paginas/senha">Mudar senha</a>
	          <a class="dropdown-item" href="sair.php">Sair do Sistema</a>
	        </div>
	      </li>
	    </ul>
	    
	  </div>
	</nav>
</header>
<main>

	<div class="container">
		<?php

			if ( file_exists ( $pagina ) ) include $pagina;
			else include "paginas/erro.php";

		?>
	</div>

	<footer>
		<hr>
		<div class="row">
			<div class="col-6 col-md-6 col-lg-6">
				<p><strong>NutriSystem</strong> Sistema de Avaliação Nutricional</p>
			</div>
			<div class="col-6 col-md-6 col-lg-6 text-right">
				<p>Todos os direitos reservados</p>
			</div>
		</div>
	</footer>
</main>