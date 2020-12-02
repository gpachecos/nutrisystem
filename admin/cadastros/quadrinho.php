<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis
	$id = $titulo = $numero = $data = $capa = $resumo = $valor = $editora_id = $tipo_id = "";

	//verificar o id - $p[2]
	if ( isset ( $p[2] ) ) {
		//se enviado um id
		$id = $p[2];

		//select para selecionar o registro
		$sql = "select *, 
			date_format(data, '%d/%m/%Y') data 
			from quadrinho 
			where id = ? limit 1";
		$consulta = $pdo->prepare($sql);
		$consulta->bindParam(1, $id);
		$consulta->execute();

		//separar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

		$titulo		= $dados->titulo;
		$numero		= $dados->numero;
		$data 		= $dados->data;
		$capa 		= $dados->capa;
		$resumo 	= $dados->resumo;
		$valor 		= $dados->valor;
		$editora_id = $dados->editora_id;
		$tipo_id 	= $dados->tipo_id;

		$valor = number_format($valor, 2, ",", ".");
	}

?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cadastro de Quadrinhos</h1>

		<div class="float-right">
			<a href="cadastros/quadrinho" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/quadrinho" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>

			<?php
				//verificar se esta editando - id não esta vazio
				if ( !empty ( $id ) ) {

					echo "<a href='#personagem' 
					class='btn btn-info'>
							<i class='fas fa-user-plus'></i> Adicionar Personagem
						</a>";

				}
			?>

		</div>

		<div class="clearfix"></div>

		<form name="cadastro" method="post" action="salvar/quadrinho" enctype="multipart/form-data"
		data-parsley-validate>
			<label for="id">ID:</label>
			<input type="text" name="id" 
			class="form-control" readonly value="<?=$id;?>">

			<br>
			<label for="titulo">Título do Quadrinho:</label>
			<input type="text" name="titulo" 
			class="form-control" value="<?=$titulo;?>"
			required data-parsley-required-message="Preencha este campo" maxlength="100">

			<br>
			<label for="numero">Número:</label>
			<input type="text" name="numero" 
			class="form-control" value="<?=$numero;?>"
			required data-parsley-required-message="Preencha este campo" data-mask="9?99">

			<br>
			<label for="data">Data:</label>
			<input type="text" name="data"
			class="form-control" value="<?=$data;?>"
			required data-parsley-required-message="Preencha este campo" data-mask="99/99/9999">

			<br>

			<?php
				$r = " required data-parsley-required-message=\"Selecione um arquivo\" ";

				//se o id nao esta vazio esta editando
				if ( !empty ( $id ) ) {
					//zerar o r para o campo não ser requerido
					$r = "";

					//montar um input com o numero da foto
					echo "<input type='hidden' name='capa' value='$capa'>";
				}
			?>

			<label for="capa">Foto da Capa (JPG):</label>
			<input type="file" name="capa"
			class="form-control"
			<?=$r;?>
			accept=".jpg">

			<?php
				//mostrar a foto se estiver editando
				if ( !empty ( $id ) ) {
					// 12345 -> ../fotos/12345p.jpg
					//muda o nome do arquivo
					$capa = "../fotos/".$capa."p.jpg";
					//mostra o arquivo dentro do img
					echo "<br><img src='$capa' width='80px'><br>";
				}
			?>

			<br>
			<label for="resumo">Resumo:</label>
			<textarea name="resumo" id="resumo"
			class="form-control"
			required data-parsley-required-message="Preencha este campo"
			row="5"><?=$resumo;?></textarea>

			<br>
			<label for="valor">Valor:</label>
			<input type="text" name="valor" id="valor"
			class="form-control"
			required data-parsley-required-message="Preencha este campo" value="<?=$valor;?>">

			<br>
			<label for="editora_id">Editora:</label>
			<select name="editora_id" id="editora_id" 
			class="form-control" required 
			data-parsley-required-message="Selecione uma opção">
				<option value="">Selecione</option>
				<?php
					$tabela =	"editora";
					$campo	= 	"nome";	 
					carregarOpcoes($tabela,$campo,$pdo);
				?>
			</select>

			<br>
			<label for="tipo_id">Tipo:</label>
			<select name="tipo_id" id="tipo_id"
			class="form-control"
			required 
			data-parsley-required-message="Selecione uma opção">
				<option value="">Selecione</option>
				<?php
					//tabela para selecionar os dados
					$tabela = "tipo";
					//nome do campo que será carregado
					$campo = "tipo";
					//função para buscar os tipos
					carregarOpcoes($tabela,$campo,$pdo);
					//$pdo - conexao com o banco de dados
				?>
			</select>
			<br>
			

			<button type="submit" class="btn btn-success">
				Salvar/Alterar
			</button>

		</form>

			<?php
				//verificar se o id nao esta vazio
				if ( !empty ( $id ) ) {
			?>
			<hr>
			<div id="personagem">
				<h2>Adicionar personagem ao quadrinho:</h2>
				<form name="formadd" method="post" action="paginas/salvarpersonagem.php" data-parsley-validate 
				target="iframe">

					<!-- id do quadrinho -->
					<input type="hidden" name="id" value="<?=$id;?>">

					<div class="row">
						<div class="col-8">

							<label for="personagem_id">Selecione o Personagem:</label>
							<select name="personagem_id" class="form-control" required data-parsley-required-message="Selecione um personagem">
								<option value=""></option>
								<?php
									//chamar função para mostrar as opções
									$tabela ="personagem";
									$campo = "nome";
									carregarOpcoes($tabela,$campo,$pdo);
								?>
							</select>
						</div>
						<div class="col-4">
							<button type="submit" class="btn btn-success">Inserir</button>
						</div>
					</div>
				</form>

				<iframe name="iframe" width="100%" height="300px" src="paginas/salvarpersonagem.php?id=<?=$id;?>"></iframe>
			</div>
			<?php
				//fechando o id
				}
			?>

	</div> <!--fim da pagina -->
</div> <!-- fim do container -->

<script type="text/javascript">
	$(document).ready(function(){
		//aplicar o summernote
		$("#resumo").summernote({
			placeholder:"Digite o resumo",
			height: 200,
			lang: 'pt-BR'
		});

		//aplica a mascara de valor no campo
		$("#valor").maskMoney({
			thousands: ".",
			decimal: ","
		});

		//selecionar o id da editora
		$("#editora_id").val(<?=$editora_id;?>);
		$("#tipo_id").val(<?=$tipo_id;?>);
	})
</script>

