<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";
?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Relatórios da Ficha de Anamnese</h1>

		<div class="float-right">
			<a href="cadastros/quadrinho" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/quadrinho" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<h5>Hábitos Cotidianos</h5>
		<form name="relatorio" method="post" action="relatorios/relHabitosCotidianos.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						Hábitos Cotidianos
					</button>
				</div>
			</div>
		</form>

		<br>
		<h5>Patologias</h5>
		<form name="relatorio" method="post" action="relatorios/relPatologias.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						Patologias
					</button>
				</div>
			</div>
		</form>

		<br>
		<h5>Histórico Médico</h5>
		<form name="relatorio" method="post" action="relatorios/relHistoricoMedico.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						HIstóricos Médicos
					</button>
				</div>
			</div>
		</form>

		<br>
		<h5>Exames</h5>
		<form name="relatorio" method="post" action="relatorios/relExames.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						Exames
					</button>
				</div>
			</div>
		</form>

		<br>
		<h5>Avaliacao Clínica</h5>
		<form name="relatorio" method="post" action="relatorios/relAvaliacaoClinica.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						Avaliação Clínica
					</button>
				</div>
			</div>
		</form>

		<br>
		<h5>Hábitos Alimentares</h5>
		<form name="relatorio" method="post" action="relatorios/relHabitosAlimentares.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						Hábitos Alimentares
					</button>
				</div>
			</div>
		</form>

		<br>
		<h5>Recordatório</h5>
		<form name="relatorio" method="post" action="relatorios/relRecordatorio.php" target="_blank">
			<div class="row">
			<div class="col-4">
					<label for="valorInicial">Valor Ficha Inicial:</label>
					<input type="text" name="valorInicial" required class="form-control">
				</div>
				<div class="col-4">
					<label for="valorFinal">Valor Ficha Final:</label>
					<input type="text" name="valorFinal" required class="form-control">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-success">
						Recordatório
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