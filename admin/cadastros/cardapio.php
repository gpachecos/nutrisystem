<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis de pessoa
	$idpessoa = $nome = $idfichaanamnese = $dataficha = $datacardapio = "";
    
    //var_dump($p[2]);

	//verificar o id - $p[2]
	if ( isset ( $p[2] ) && ($p[2] == "") ) {

        $msg = "Não existe Ficha de Anamnese vinculada à esta pessoa, favor criar uma ficha para continuar com a avaliação!";
		mensagem( $msg );
	} else {

	// 	var_dump($p[2]);
		//selecionar os dados conforme o id
		$sql = "
            select p.idpessoa, p.nome , f.idfichaanamnese, date_format(f.dataficha,'%d/%m/%Y') as dataficha
			from pessoa p 
            left join fichaanamnese f on (p.idpessoa = f.idpessoa)
			where f.idfichaanamnese = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

        $idpessoa 			= $dados->idpessoa;
		$nome 				= $dados->nome;
		$idfichaanamnese 	= $dados->idfichaanamnese;
        $dataficha 			= $dados->dataficha;

     }

?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cardápio</h1>
		
		<div class="float-right">

			<a href="listar/fichaanamneseAvAntro" class="btn btn-info">
				<i class="fas fa-search"></i> Consultar
			</a>

		</div>
			
		<div class="clearfix"></div>
		

		<form name="cadastro" method="post" action="salvar/avaliacaoantropometrica" data-parsley-validate enctype="multipart/form-data">

			
			<div class="row">
				<div class="col-12 col-md-2">
						<label for="idpessoa">ID Pessoa:</label>  
					<input type="text" name="idpessoa" id="idpessoa" readonly
					class="form-control" value="<?=$idpessoa;?>">
				</div>
				<div class="col-12 col-md-5">
					<label for="nome">Nome da Pessoa:</label>
					<input list="nomes" id="nome" readonly
					class="form-control" onblur="selecionaPessoa(this.value)" value="<?=$nome;?>">

					<datalist id="nomes">
						<?php
							$sql = "select idpessoa, nome from pessoa order by nome";
							$consulta = $pdo->prepare( $sql );
							$consulta->execute();
							//recuperar os dados
							while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {

								echo "<option>$dados->idpessoa - $dados->nome</option>";

							}

						?>
					</datalist>

				</div>
				<div class="col-12 col-md-2">
					<label for="idfichaanamnese">Registro:</label>
					<input type="text" name="idfichaanamnese" 
					class="form-control" readonly
					value="<?=$idfichaanamnese;?>">
				</div>
				<div class="col-12 col-md-2">
					<label for="dataficha">Data da Ficha:</label>
					<input type="text" name="dataficha" required
					class="form-control" data-mask="99/99/9999" readonly
					value="<?=$dataficha;?>">
				</div>
			</div>
			

		</form>

			<?php
				//verificar se o id nao esta vazio
				if ( !empty ( $idfichaanamnese ) ) {
			?>
			<hr>
			<br>
			<h1>Café da Manhã</h1>
			<hr>
			<div id="cardapio">
				<form name="formadd" method="post" action="salvar/cafedamanha.php" data-parsley-validate 
				target="iframe">

				
				<br>
					<!-- id do quadrinho -->
					<input type="hidden" name="idfichaanamnese" value="<?=$idfichaanamnese;?>">
					<input type="hidden" name="dataficha" value="<?=$dataficha;?>">

					<div class="form-row">
						<div class="col-12 col-md-8">

							<label for="idalimento">Selecione o Alimento:</label>
							<select name="idalimento" id="idalimento" class="form-control" 
							required data-parsley-required-message="Selecione um alimento">
								<option value="">Selecione</option>
								<?php
									//chamar função para mostrar as opções
									$tabela = "alimento";
									$id 	= "idalimento";
									$campo  = "nomealimento";
									carregarOpcoes($tabela,$id,$campo,$pdo);
								?>
							</select>
						</div>

						<div class="col-12 col-md-2">
							<input type="number" min="0" step="0.01" class="form-control" 
							id="peso" name="peso" value="<?=$peso;?>" placeholder="Peso(gr)" 
							required data-parsley-required-message="Informe um peso">
						</div>

						<div class="col-12 col-md-2">
							<button type="submit" class="btn btn-success">Inserir</button>
						</div>
					</div>
				</form>

				<iframe name="iframe" width="100%" height="300px" src="salvar/cafedamanha.php?idfichaanamnese=<?=$idfichaanamnese;?>"></iframe>
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

		$("#idalimento").select2();
	})

	function selecionaPessoa(nome) {
				nome = nome.split(" - ");
				$("#idpessoa").val(nome[0])
			}
</script>

