<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";
?>
<div class="container">
	<div class="coluna">

		<h1>Listagem de Pessoas</h1>


		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<td width="10%">ID</td>
					<td width="40%">Nome da Pessoa</td>
					<td width="30%">Telefone/Celular</td>
					<td>Opções</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//selecionar os dados do editora
					$sql = "select * from pessoa
						order by nome";
					$consulta = $pdo->prepare($sql);
					$consulta->execute();

					//laço de repetição para separar as linhas
					while ( $linha = $consulta->fetch(PDO::FETCH_OBJ)) {

						//separar os dados
						$idpessoa 	= $linha->idpessoa;
						$nome 	    = $linha->nome;
						$telefone 	= $linha->telefone;

						//montar as linhas e colunas da tabela
						echo "<tr>
							<td>$idpessoa</td>
							<td>$nome</td>
							<td>$telefone</td>
							<td>
								<a href='cadastros/fichaanamnese/$idpessoa' class='btn btn-success btn-sm'>
									<i class='fas fa-edit'></i>
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
	function excluir(idpessoa) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/pessoa/"+idpessoa;
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