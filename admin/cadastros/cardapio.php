<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis de pessoa
	$idpessoa = $nome = $idfichaanamnese = $dataficha = "";
    
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
		<h1 class="float-left">Ficha de Anamnese</h1>
		
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
			



<hr>

	<?php
		/********  TRAZER DADOS DA FICHA DE HABITOS COTIDIANOS  ********/

		//inicializar as variaveis dos habitos cotidianos
        $idrefeicao = $idalimento = $datacardapio = $peso = $energia = $proteina = $lipideo = $carboidrato = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			$sql = "select c.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join cardapio c on (f.idfichaanamnese = c.idfichaanamnese) 
					where f.idfichaanamnese = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
            $idrefeicao			= $dados->idrefeicao;
            $idalimento			= $dados->idalimento;
            $datacardapio		= $dados->datacardapio;
            $peso				= $dados->peso;
            $energia			= $dados->energia;
            $proteina			= $dados->proteina;
            $lipideo			= $dados->lipideo;
            $carboidrato		= $dados->carboidrato;

		}
	
	?>

        <div class="d-flex align-items-center bd-highlight">
            <h1 class="mr-auto p-2 bd-highlight">Avaliação Antropométrica</h1>
            
            <div class="p-1 bd-highlight col-md-2" >
                <label for="dataavaliacao">Data da Avaliação:</label>
                <input type="text" name="dataavaliacao" required
                class="form-control" data-mask="99/99/9999" 
                value="<?=$dataavaliacao;?>">
            </div>
        </div>
        <hr>

        <div class="clearfix"></div>
        <br>

			<div class="row">
                <label for="idcategoriaalimento">Categoria Alimento:</label>
                <select name="idcategoriaalimento" id="idcategoriaalimento" 
                class="form-control" required 
                data-parsley-required-message="Selecione uma opção">
                    <option value="">Selecione</option>
                    <?php
                        $tabela =	"categoriaalimentos";
                        $id 	= 	"idcategoriaalimento";
                        $campo	= 	"nomecategoria";	 
                        carregarOpcoes($tabela,$id,$campo,$pdo);
                    ?>
                </select>

            </div>


			<br>			

			<div class="col-lg-12" style="text-align: right">
				<button type="submit" class="btn btn-success">
					Salvar/Alterar
				</button>
			</div>

		</form>

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
	})

	function selecionaPessoa(nome) {
				nome = nome.split(" - ");
				$("#idpessoa").val(nome[0])
			}
</script>

