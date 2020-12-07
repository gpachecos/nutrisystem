<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis de pessoa
	$idpessoa = $nome = $idfichaanamnese = $dataficha = "";

	//verificar o id - $p[2]
	if ( isset ( $p[2] ) ) {
		
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
		<h1 class="float-left">Ficha de Anamnese</h1>
		
		<div class="float-right">

			<!-- <a href="listar/pessoaFicha" class="btn btn-info">
				<i class="fas fa-search"></i> Consultar
			</a> -->

		</div>
			
		<div class="clearfix"></div>
		

		<form name="cadastro" method="post" action="salvar/exame" data-parsley-validate enctype="multipart/form-data">

			
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
			

<br>

<hr>

	<?php
		/********  TRAZER DADOS DA FICHA DE HABITOS COTIDIANOS  ********/

		//inicializar as variaveis dos habitos cotidianos
		$nomeexame = $dataexame = $referencia = $alteracao = $arqexame = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			// $sql = "select e.*,  date_format(e.dataexame,'%d/%m/%Y') as dataexame, 
			// 		from pessoa p
			// 		left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
			// 		left join exame e on (f.idfichaanamnese = e.idfichaanamnese) 
			// 		where f.idfichaanamnese = ? limit 1";
			// $consulta = $pdo->prepare( $sql );
			// $consulta->bindParam(1,$p[2]);
			// $consulta->execute();
			// //recuperar os dados
			// $dados = $consulta->fetch(PDO::FETCH_OBJ);
	
			// $nomeexame		    = $dados->nomeexame; 
            // $dataexame			= $dados->dataexame; 
            // $referencia		    = $dados->referencia; 
            // $alteracao			= $dados->alteracao;
			// $arqexame			= $dados->arqexame;
			// $dataexame1			= $dados->dataexame1; 
		}
	
	?>

	
		<h1 class="float-left">Exames</h1>

        <div class="clearfix"></div>
        <br>

			<div class="row">
                <div class="col-12 col-md-2">
                    <label for="dataexame">Data do Exame:</label>
                    <input type="text" name="dataexame" 
                    class="form-control" data-mask="99/99/9999" 
                    value="<?=$dataexame;?>">
                </div>
                <br>
                <div class="col-12 col-md-3">
					<label for="nomeexame">Nome do Exame:</label>
					<input type="text" name="nomeexame" 
					class="form-control" value="<?=$nomeexame;?>">
                </div>
                <br>
                <div class="col-12 col-md-3">
					<label for="referencia">Referência:</label>
					<input type="text" name="referencia" 
					class="form-control" value="<?=$referencia;?>">
                </div>
                <br>
                <div class="col-12 col-md-4">
					<label for="alteracao">Alteração :</label>
					<input type="text" name="alteracao" 
					class="form-control" value="<?=$alteracao;?>">
				</div>
            </div>
            <br>
            <div class="row">
                        
                <div class="col 12 col-md-12">
					<label for="arquivo">Anexar Exame:</label>
                    <input type="file" name="arquivo" class="form-control" accept=".pdf, .jpg">
				</div>
				
				<div class="col-lg-12" style="text-align: right">
					<button type="submit" class="btn btn-info">
					<i class='fas fa-plus'></i> Adicionar Exame
					</button>
				</div>

            </div>

			<br>			

		<!-- </form>

		<hr> -->

		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<td width="15%">Data do Exame</td>
					<td width="40%">Nome do Exame</td>
					<td width="20%">Referência</td>
					<td width="30%">Alteração</td>
					<td width="30%">PDF</td>
					<td>Excluir</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//selecionar os dados do editora
					$sql = "select e.*, date_format(e.dataexame,'%d/%m/%Y') as dataexame
							from pessoa p
							left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
							left join exame e on (f.idfichaanamnese = e.idfichaanamnese) 
							where f.idfichaanamnese = ?";
					$consulta = $pdo->prepare($sql);
					$consulta->bindParam(1,$p[2]);
					$consulta->execute();


					//laço de repetição para separar as linhas
					while ( $linha = $consulta->fetch(PDO::FETCH_OBJ)) {

						//separar os dados
						$idexame 			= $linha->idexame;
						$nomeexame		    = $linha->nomeexame; 
						$dataexame			= $linha->dataexame; 
						$referencia		    = $linha->referencia; 
						$alteracao			= $linha->alteracao;
						$arqexame			= $linha->arqexame;

						//montar as linhas e colunas da tabela
						if($idexame != NULL){
						echo "<tr>
							<td>$dataexame</td>
							<td>$nomeexame</td>
							<td>$referencia</td>
							<td>$alteracao</td>
							<td>$arqexame</td>
							<td>
								<a href='javascript:excluir($idexame)' class='btn btn-danger btn-sm'>
									<i class='fas fa-trash'></i>
								</a>
							</td>
						</tr>";
						}

					}

				?>
			</tbody>
		</table>

		<br>
		<br>
		<!-- <div class="col-lg-12" style="text-align: right"> -->
			<?php
			echo "
			<div class='d-flex flex-row-reverse bd-highlight'>
			<a href='cadastros/avaliacaoclinica/$idfichaanamnese' class='btn btn-success' role='button'>
				Salvar/Alterar
			</a>
			</div>"
			?>
		<!-- </div> -->


		</form>

		<hr>


	</div> <!--fim da pagina -->
</div> <!-- fim do container -->

<script type="text/javascript">
	//funcao em javascript para perguntar se quer mesmo excluir
	function excluir(idexame) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/exame/"+idexame;
		}
	}

	function selecionaPessoa(nome) {
				nome = nome.split(" - ");
				$("#idpessoa").val(nome[0])
			}

	function excluir(idexame) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/exame/"+idexame;
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

