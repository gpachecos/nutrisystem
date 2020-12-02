<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";
?>
<div class="container">
	<div class="coluna">

		<h1>Listagem de Vendas</h1>


		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<td width="10%">ID</td>
					<td width="40%">Nome do Cliente</td>
					<td width="10%">Data</td>
					<td width="20%">Status</td>
					<td width="10%">Total</td>
					<td>Opções</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//selecionar os dados do editora
					$sql = "select v.id, c.nome, v.status, date_format(v.data,'%d/%m/%Y') data, ( select sum(vq.valor * vq.quantidade) from venda_quadrinho vq where vq.venda_id = v.id ) total from venda v 
						inner join cliente c on (c.id = v.cliente_id)
						order by v.data desc";
					$consulta = $pdo->prepare($sql);
					$consulta->execute();

					//laço de repetição para separar as linhas
					while ( $linha = $consulta->fetch(PDO::FETCH_OBJ)) {

						//separar os dados
						$id 	= $linha->id;
						$nome 	= $linha->nome;
						$data 	= $linha->data;
						$status = $linha->status;
						$total 	= number_format($linha->total,2,",",".");


						//montar as linhas e colunas da tabela
						echo "<tr>
							<td>$id</td>
							<td>$nome</td>
							<td>$data</td>
							<td>$status</td>
							<td>$total</td>
							<td>
								<a href='cadastros/venda/$id' class='btn btn-success btn-sm'>
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
			location.href = "excluir/venda/"+id;
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