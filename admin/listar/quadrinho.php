<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

?>
<div class="container">
	<div class="coluna">
		<h1>Listagem Quadrinhos</h1>
		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<td width="10%">ID</td>
					<td width="10%">Capa</td>
					<td width="30%">Título do Quadrinho</td>
					<td width="10%">Número</td>
					<td width="10%">Data</td>
					<td width="15%">Valor</td>
					<td>Opções</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//buscar os quadrinhos
					$sql = "select id, capa, titulo, numero, 
						date_format(data,'%d/%m/%Y') data,
						valor from quadrinho
						order by titulo";
					$consulta = $pdo->prepare($sql);
					$consulta->execute();

					while ( $dados = $consulta->fetch( PDO::FETCH_OBJ ) ) {
						//recuperar os dados do banco
						$id 	= $dados->id;
						$titulo = $dados->titulo;
						$numero	= $dados->numero;
						$capa 	= $dados->capa;
						$data 	= $dados->data;
						$valor 	= $dados->valor;
						//formatar o valor
						$valor = number_format($valor,
							2,
							',',
							'.');

						//imagem de capa
						//1234 -> ../fotos/1234p.jpg
						$capa = "../fotos/".$capa."p.jpg";

						//mostrar linhas com os dados na tela
						echo "<tr>
							<td>$id</td>
							<td><img src='$capa' width='80px'></td>
							<td>$titulo</td>
							<td>$numero</td>
							<td>$data</td>
							<td class='text-right'>
								R$ $valor
							</td>
							<td>
								<a href='cadastros/quadrinho/$id' class='btn btn-success btn-sm'>
									<i class='fas fa-edit'></i>
								</a>

								<a href='javascript:excluir($id)' class='btn btn-danger btn-sm'>
									<i class='fas fa-trash'></i>
								</a>
							</td>
						</tr>";

					}
				?>
			</tbody>
		</table>
	</div> <!-- fim do coluna -->
</div> <!-- fim do container -->

<script type="text/javascript">
	//funcao em javascript para perguntar se quer mesmo excluir
	function excluir(id) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/quadrinho/"+id;
		}
	}

	$(document).ready( function () {
	    $('.table').DataTable( {
        "language": {
            "lengthMenu": "Mostrar _MENU_ resultados por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtrando de _MAX_ em um total de registros)",
            "search":"Buscar",
            "Previous":"Anterior"
        }
    });
	} );
</script>