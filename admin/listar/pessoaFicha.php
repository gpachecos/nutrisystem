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
					<td width="5%">ID</td>
					<td width="30%">Nome da Pessoa</td>
					<td width="30%">Código Ficha Anamnese</td>
					<td width="30%">Data da Ficha</td>
					<td>Opções</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//selecionar os dados do editora
					$sql = "select p.idpessoa, p.nome , f.idfichaanamnese, date_format(f.dataficha,'%d/%m/%Y') as dataficha
						from pessoa p 
						left join fichaanamnese f on (p.idpessoa = f.idpessoa)
						order by nome";
					$consulta = $pdo->prepare($sql);
					$consulta->execute();

					//laço de repetição para separar as linhas
					while ( $linha = $consulta->fetch(PDO::FETCH_OBJ)) {

						//separar os dados
						$idpessoa 	        = $linha->idpessoa;
						$nome 	            = $linha->nome;
                        $idfichaanamnese 	= $linha->idfichaanamnese;
						$dataficha 	        = $linha->dataficha;

						//montar as linhas e colunas da tabela
						echo "<tr>
							<td>$idpessoa</td>
							<td>$nome</td>
							<td>$idfichaanamnese</td>
							<td>$dataficha</td>
							<td>
								<a href='cadastros/fichaanamnese/$idfichaanamnese' class='btn btn-success btn-sm'>
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