<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";
?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cardápio</h1>

		<div class="float-right">
			<a href="cadastros/quadrinho" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/quadrinho" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<h5>Cardápio</h5>
		<form name="relatorio" method="post" action="relatorios/relCardapio.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="dataInicial">Data Inicial Avaliação:</label>
					<input type="text" name="dataInicial" required class="form-control" data-mask="99/99/9999">
				</div>
				<div class="col-4">
					<label for="dataFinal">Data Final Avaliação:</label>
					<input type="text" name="dataFinal" required class="form-control" data-mask="99/99/9999">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
					Cardápio
					</button>
				</div>
			</div>
		</form>


		<div class="clearfix"></div>
	</div>
</div>

<script type="text/javascript">
	
	$(document).ready(function(){
		//aplica a mascara de valor no campo
		$(".valor").maskMoney({
			thousands: ".",
			decimal: ","
		});
	})
</script>