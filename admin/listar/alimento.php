<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

?>
<div class="container">
	<div class="coluna">
        <h1>Listagem de Alimentos</h1>
		<!-- <h5>Composição para 100 gramas</h5> -->
		<div class="alert alert-success" role="alert">
			Composição para 100 gramas
		</div>
		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<!-- <td width="10%">ID</td> -->
					<td width="40%">Nome do Alimento</td>
					<td width="10%">Energia</td>
					<td width="10%">Proteínas</td>
					<td width="10%">Lipídeos</td>
					<td width="10%">Carboidratos</td>
					<td>Opções</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//buscar os alimentos
					$sql = "select idalimento, idcategoriaalimento, nomealimento, 
                        energia, proteina, lipideo, carboidrato
                        from alimento
						order by nomealimento";
					$consulta = $pdo->prepare($sql);
					$consulta->execute();

					while ( $dados = $consulta->fetch( PDO::FETCH_OBJ ) ) {
						//recuperar os dados do banco
                        $idalimento 	        = $dados->idalimento;
                        $idcategoriaalimento 	= $dados->idalimento;
						$nomealimento           = $dados->nomealimento;
						$energia	            = $dados->energia;
						$proteina 	            = $dados->proteina;
						$lipideo 	            = $dados->lipideo;
						$carboidrato 	        = $dados->carboidrato;
						//formatar o valor
						// $valor = number_format($valor,
						// 	2,
						// 	',',
						// 	'.');

						//imagem de capa
						//1234 -> ../fotos/1234p.jpg
						// $capa = "../fotos/".$capa."p.jpg";

						//mostrar linhas com os dados na tela
						echo "<tr>
							<td>$nomealimento</td>
							<td class='text-center'>$energia</td>
							<td class='text-center'>$proteina</td>
							<td class='text-center'>$lipideo</td>
							<td class='text-center'>
								$carboidrato
							</td>
							<td class='text-center'>
								<a href='cadastros/alimento/$idalimento' class='btn btn-success btn-sm'>
									<i class='fas fa-edit'></i>
								</a>

								<a href='javascript:excluir($idalimento)' class='btn btn-danger btn-sm'>
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
	function excluir(idalimento) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/alimento/"+idalimento;
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