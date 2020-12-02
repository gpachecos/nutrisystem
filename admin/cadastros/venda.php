<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//adicionando vazio no id e no tipo
	$id = $cliente_id = $cliente = $status = "";
	$data = date("d/m/Y");

	$usuario_id = $_SESSION["hqs"]["id"];
	$usuario = $_SESSION["hqs"]["login"];

	//$p[1] -> index.php (id do registro)
	if ( isset ( $p[2] ) ) {

		//selecionar os dados conforme o id
		$sql = "select v.*, date_format(v.data,'%d/%m/%Y') data,
			u.nome usuario, c.id cid, c.nome cliente 
			from venda v
			inner join usuario u on (u.id = v.usuario_id)
			inner join cliente c on (c.id = v.cliente_id)
			where v.id = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		$id 			= $dados->id;
		$cliente_id 	= $dados->cliente_id;
		$cliente 		= $dados->cliente;
		$usuario_id  	= $dados->usuario_id;
		$usuario 		= $dados->usuario;
		$data  			= $dados->data;
		$status 		= $dados->status;

		$cliente = $cliente_id." - ".$cliente;
	}

?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cadastro de Vendas</h1>

		<div class="float-right">
			<a href="cadastros/venda" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/venda" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<form name="cadastro" method="post" action="salvar/venda" data-parsley-validate>

			<div class="row">
				<div class="col-12 col-md-3">

					<label for="id">ID:</label>
					<input type="text" name="id" 
					class="form-control" readonly
					value="<?=$id;?>">

				</div>
				<div class="col-12 col-md-2">

					<label for="data">Data da Venda:</label>
					<input type="text" name="data" required
					class="form-control" data-mask="99/99/9999"
					data-parsley-required-message="Preencha este campo"
					value="<?=$data;?>">

				</div>
				<div class="col-12 col-md-3">

					<label for="status">Selecione o Status:</label>
					<select name="status" id="status" class="form-control" required data-parsley-required-message="Selecione um Status">
						<option value=""></option>
						<option value="Aberto">Aberto</option>
						<option value="Cancelado">Cancelado</option>
						<option value="Pago">Pago</option>
						<option value="Devolvido">Devolvido</option>
					</select>

					<script>
						//selecionar o status salvo
						$("#status").val("<?=$status;?>");
					</script>
				</div>

				<div class="col-12 col-md-4">

					<label for="usuario_id">Usuário:</label>
					
					<input type="hidden" name="usuario_id" 
					class="form-control" readonly
					value="<?=$usuario_id;?>">

					<input type="text" 
					class="form-control" readonly
					value="<?=$usuario;?>">

				</div>

			</div>
			<br>
			<div class="row">
				<div class="col-4 col-md-2">
					<label for="cliente_id">Cliente ID:</label>
					<input type="text" name="cliente_id" id="cliente_id" readonly required data-parsley-required-message="Selecione um cliente" class="form-control"
					value="<?=$cliente_id;?>">
				</div>
				<div class="col-8 col-md-10">
					<label for="cliente">Nome do Cliente:</label>
					<input list="clientes" id="cliente" required data-parsley-required-message="Selecione um cliente" class="form-control" onblur="selecionaCliente(this.value)"
					value="<?=$cliente;?>">


					<datalist id="clientes">
						<?php
							$sql = "select id, nome from cliente order by nome";
							$consulta = $pdo->prepare( $sql );
							$consulta->execute();
							//recuperar os dados
							while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {

								echo "<option>$dados->id - $dados->nome</option>";

							}

						?>
					</datalist>
				</div>
			</div>

			<br>
			<button type="submit" class="btn btn-success">
				<i class="fas fa-check"></i> Gravar
			</button>

			<?php
				if ( !empty ( $id )) {
					echo "<button type='button' class='btn btn-danger' data-toggle='modal' data-target='#insereQuadrinhos'>
						<i class='fas fa-plus'></i> Adicionar Quadrinhos
						</button>";
			?>
		</form>
					<!-- Modal -->
					<div class="modal fade" id="insereQuadrinhos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Adicionar Quadrinhos</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        
					      	<form name="quadrinho" method="post" action="salvarQuadrinho.php" target="iframe">

					      		<div class="row">
					      			<div class="col-1">
					      				<input type="hidden" name="venda" value="<?=$id;?>">

					      				<label for="produto">ID:</label>
					      				<input type="text" name="produto" id="produto" class="form-control" required readonly data-parsley-required-message="Preencha">
					      			</div>
					      			<div class="col-4">

			<label for="produtos">Selecione o Produto:</label>
			<input list="produtos" class="form-control" required onblur="selecionaProduto(this.value)"  data-parsley-required-message="Selecione um produto">

			<datalist id="produtos">
				<?php
				$sql = "select id, titulo from quadrinho order by titulo";
				$consulta = $pdo->prepare($sql);
				$consulta->execute();
				while ( $dados = $consulta->fetch(PDO::FETCH_OBJ ) ) {
					echo "<option value='$dados->id - $dados->titulo'></option>";
				}
				?>
			</datalist>


  			</div>
  			<div class="col-2">
  				<label for="valor">Valor:</label>
  				<input type="text" name="valor" id="valor" class="form-control" required data-parsley-required-message="Preencha">
  			</div>
  			<div class="col-2">
  				<label for="quantidade">Quantidade:</label>
  				<input type="text" name="quantidade" class="form-control" required data-parsley-required-message="Preencha" data-mask="9?9">
  			</div>
  			<div class="col-3">
  				<button type="submit" class="btn btn-success">
  					OK
  				</button>
  				<button type="reset" class="btn btn-success" 
  				id="reset">
  					Novo
  				</button>
  			</div>
  		</div>

					      	</form>
					      	<iframe name="iframe" id="iframe" width="100%" height="200px" src="salvarQuadrinho.php?venda=<?=$id;?>"
					      		class="img-thumbnail"></iframe>

					      </div>
					      <div class="modal-footer">

					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					        
					      </div>
					    </div>
					  </div>
					</div>

					<?php

				}
			?>

		</form>

		<script type="text/javascript">
			function selecionaCliente(cliente) {
				cliente = cliente.split(" - ");
				$("#cliente_id").val(cliente[0])
			}

			function selecionaProduto(produto){
				produto = produto.split(" - ");
				$("#produto").val(produto[0]);

				$.get("buscavalor.php",
					{produto:produto[0]},
					function(dados){
						//jogar o valor no valor do produto
						$("#valor").val(dados);

				})
				// $.get ou $.post ("nome do arquivo")
				// {variaveis a serem enviadas}
				// function( retornar a resposta )
			}

			//aplica a mascara de valor no campo
			$("#valor").maskMoney({
				thousands: ".",
				decimal: ","
			});
		</script>

</div>