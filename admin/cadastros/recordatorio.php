<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
        include "../verificalogin.php";

?>

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

			<a href="listar/pessoaFicha" class="btn btn-info">
				<i class="fas fa-search"></i> Consultar
			</a>

		</div>
			
		<div class="clearfix"></div>
		

		<form name="cadastro" method="post" action="cadastros/recordatorio" data-parsley-validate enctype="multipart/form-data" id="form_recordatorio">

			
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
							<input type="text" id="idfichaanamnese" name="idfichaanamnese" 
							class="form-control" readonly
							value="<?=$idfichaanamnese;?>">
						</div>
						<div class="col-12 col-md-2">
							<label for="dataficha">Data da Ficha:</label>
							<input type="text" id="dataficha" name="dataficha" required
							class="form-control" data-mask="99/99/9999" readonly
							value="<?=$dataficha;?>">
						</div>
					</div>
			

<br>

<hr>

	<?php
		/********  TRAZER DADOS DA FICHA DE HABITOS COTIDIANOS  ********/

		//inicializar as variaveis dos habitos cotidianos
		$datarecordatorio = $tipodia = $horario = $alimento = $quantidade = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			$sql = "select r.*, date_format(r.datarecordatorio,'%d/%m/%Y') as datarecordatorio, rh.*
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join recordatorio r on (f.idfichaanamnese = r.idfichaanamnese) 
					left join recordatoriohora rh on (r.idrecordatorio = rh.idrecordatorio)  
					where f.idfichaanamnese = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
			$idrecordatorio     = $dados->idrecordatorio; 
			$datarecordatorio   = $dados->datarecordatorio; 
			$tipodia			= $dados->tipodia;
			$horario			= $dados->horario;
			$alimento			= $dados->alimento;
			$quantidade			= $dados->quantidade;
		}
	
	?>
		
		<h1 class="float-left">Recordatório</h1>

        <div class="clearfix"></div>
        <br>

			<div class="row">
                <div class="col-12 col-md-2">
                    <label for="datarecordatorio">Data:</label>
                    <input id="datarecordatorio" type="text" name="datarecordatorio" 
                    class="form-control" data-mask="99/99/9999" 
                    value="<?=$datarecordatorio;?>">
                </div>
                <br>
				<div class="col-12 col-md-3">
					<label for="tipodia">Tipo do Dia?</label>
					<select name="tipodia" id="tipodia"
					class="form-control" required  data-parsley-required-message="Selecione um Tipo">
						<option value="">Selecione</option>
						<option value="Típico">Típico</option>
						<option value="Atípico">Atípico</option>
					</select>
					<script type="text/javascript">
						$("#tipodia").val('<?=$tipodia;?>')
					</script>
				</div>
				<!-- <div class="col-12 col-md-2"> -->
					<button type="button" class="btn btn-info" onclick="salvarRecordatorio()">
						Incluir
					</button>
				<!-- </div> -->
			</div>
			<hr>
		<!-- </form>


		<form name="cadastro" method="post" action="salvar/recordatorioHora" data-parsley-validate enctype="multipart/form-data"> -->

			<?php
			
			//selecionar os dados conforme o id
			$sql = "select r.*, date_format(r.datarecordatorio,'%d/%m/%Y') as datarecordatorio
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join recordatorio r on (f.idfichaanamnese = r.idfichaanamnese)
					left join recordatoriohora rh on (r.idrecordatorio = rh.idrecordatorio)
					where f.idfichaanamnese = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
			$idrecordatorio     = $dados->idrecordatorio; 
			// var_dump($dados);
			// exit;
			
			?>
			<input id="idrecordatorio" type="hidden" value="<?=$idrecordatorio;?>">

			<br>
            <div class="row">
				<br>
                <div class="col-12 col-md-4">
					<label for="horario">Horário:</label>
					<input type="time" name="horario" id="horario"
					class="form-control" value="<?=$horario;?>">
                </div>
                <br>
                <div class="col-12 col-md-4">
					<label for="alimento">Alimento :</label>
					<input type="text" name="alimento" id="alimento"
					class="form-control" value="<?=$alimento;?>">
				</div>
            	<br>        
                <div class="col-12 col-md-3">
					<label for="quantidade">Quantidade :</label>
					<input type="text" name="quantidade" id="quantidade"
					class="form-control" value="<?=$quantidade;?>">
				</div>
				
				<div class="col-12 col-md-1">
					<button type="button" class="btn btn-info" onclick="salvarRecordatorioHora()">
					<i class='fas fa-plus'></i>
					</button>
				</div>

            </div>

			<br>			

		<!-- </form>

		<hr> -->

		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<td width="15%">Horário</td>
					<td width="40%">Alimento</td>
					<td width="30%">Quantidade</td>
					<td>Excluir</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//selecionar os dados do editora
					$sql = "select r.*, date_format(r.datarecordatorio,'%d/%m/%Y') as datarecordatorio, rh.*
							from pessoa p
							left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
							left join recordatorio r on (f.idfichaanamnese = r.idfichaanamnese) 
							left join recordatoriohora rh on (r.idrecordatorio = rh.idrecordatorio)  
							where f.idfichaanamnese = ? order by horario";
					$consulta = $pdo->prepare($sql);
					$consulta->bindParam(1,$p[2]);
					$consulta->execute();


					//laço de repetição para separar as linhas
					while ( $linha = $consulta->fetch(PDO::FETCH_OBJ)) {

						//separar os dados
						$idrecordatoriohora 	= $linha->idrecordatoriohora;
						$horario		    	= $linha->horario; 
						$alimento				= $linha->alimento; 
						$quantidade		    	= $linha->quantidade; 

						//montar as linhas e colunas da tabela
						if($idrecordatoriohora != NULL){
						echo "<tr>
							<td>$horario</td>
							<td>$alimento</td>
							<td>$quantidade</td>
							<td>
								<a href='javascript:excluir($idrecordatoriohora)' class='btn btn-danger btn-sm'>
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
		<!-- window.location.href -->
		
		<div class="row">
			<div class="col-4 col-md-10">
				<button onclick="window.location.href='cadastros/habitosalimentares/<?=$idfichaanamnese?>'" type="button" class="btn btn-info">
						<i class='fas fa-plus'>Anterior</i>
						</button>
						<!-- <button onclick="window.location.href='/page2'">Continue</button> -->
			</div>
			<div class="col col-md-2">
				<button type="button" class="btn btn-info">
						<i class='fas fa-plus'>Próximo</i>
						</button></div>
		</div>

		<!-- <div class="col-lg-12" style="text-align: right"> -->
			<?php
			echo "
			<a href='cadastros/avaliacaoclinica/$idfichaanamnese' class='btn btn-success' role='button'>
				Salvar/Alterar
			</a>"
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

	function excluir(idrecordatoriohora) {
		//perguntar
		if ( confirm("Deseja mesmo excluir? Tem certeza?" ) ) {
			//chamar a tela de exclusão
			location.href = "excluir/recordatorioHora/"+idrecordatoriohora;
		}
	}


	function salvarRecordatorio(){
		const param = {
			datarecordatorio:document.getElementById('datarecordatorio').value
			,tipodia:document.getElementById('tipodia').value
			,idfichaanamnese:document.getElementById('idfichaanamnese').value
			,dataficha:document.getElementById('dataficha').value
		}
		$.ajax({ 
			type: "POST", 
			url: "salvar/recordatorio.php",				     
			dataType: 'json',
			data: param,
			success: function(result){					
				if(result['message']) {
					alert(result['message']);
				}
												
			},
			fail:function(err){
				alert("Erro ao inserir recordatório!");
			}
		});
	}

	function salvarRecordatorioHora(){
		const param = {
			horario:document.getElementById('horario').value
			,alimento:document.getElementById('alimento').value
			,quantidade:document.getElementById('quantidade').value
			,idrecordatorio: document.getElementById('idrecordatorio').value
			,idfichaanamnese:document.getElementById('idfichaanamnese').value
		}
		$.ajax({ 
			type: "POST", 
			url: "salvar/recordatorioHora.php",				     
			dataType: 'json',
			data: param,
			success: function(result){					
				if(result['message']) {
					alert(result['message']);
				}
												
			},
			fail:function(err){
				alert("Erro ao inserir recordatório!");
			}
		});
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


