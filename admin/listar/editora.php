<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";
?>
<div class="container">
	<div class="coluna">

		<h1>Listagem de Editoras</h1>


		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<td width="10%">ID</td>
					<td width="40%">Nome da Editora</td>
					<td width="40%">Site da Editora</td>
					<td>Opções</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//selecionar os dados do editora
					$sql = "select * from editora
						order by nome";
					$consulta = $pdo->prepare($sql);
					$consulta->execute();

					//laço de repetição para separar as linhas
					while ( $linha = $consulta->fetch(PDO::FETCH_OBJ)) {

						//separar os dados
						$id 	= $linha->id;
						$nome 	= $linha->nome;
						$site 	= $linha->site;

						//montar as linhas e colunas da tabela
						echo "<tr>
							<td>$id</td>
							<td>$nome</td>
							<td>$site</td>
							<td>
								<a href='cadastros/editora/$id' class='btn btn-success btn-sm'>
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
	</div>
</div>
<script type="text/javascript">
	//funcao em javascript para perguntar se quer mesmo excluir
	function excluir(id) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/editora/"+id;
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