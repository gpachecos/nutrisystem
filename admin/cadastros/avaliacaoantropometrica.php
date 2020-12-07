<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

	//inicializar as variaveis de pessoa
	$idpessoa = $nome = $idfichaanamnese = $dataficha = "";
    
    var_dump($p[3]);

	if ( isset($p[2]) ){
	//verificar o id - $p[2]
	if ( isset ( $p[2] ) && ($p[2] == "") ) {

        $msg = "Não existe Ficha de Anamnese vinculada à esta pessoa, favor criar uma ficha para continuar com a avaliação!";
		mensagem( $msg );
	} else {
		
	 	//var_dump($p[2]);
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
        $dataavaliacao = $altura = $pesoatual = $pesoideal = $bracodireitorelaxado = $bracodireitocontraido = $antebracodireito = 
        $punhodireito = $ombro = $torax = $cintura = $abdomen = $quadril = $panturrilhadireita = $coxadireita = $coxaproximaldireita = 
        $biceps = $triceps = $subescapular = $axilarmedia = $toraxica = $suprailiaca = $abdominal = $coxa = $panturrilhamedia = "";

		//verificar o id - $p[2]
		if ( isset ( $p[2] ) ) {
			
			//selecionar os dados conforme o id
			$sql = "select aa.*, date_format(aa.dataavaliacao,'%d/%m/%Y') as dataavaliacao
					from pessoa p
					left join fichaanamnese f  on (p.idpessoa = f.idpessoa)
					left join avaliacaoantropometrica aa on (f.idfichaanamnese = aa.idfichaanamnese) 
					where f.idfichaanamnese = ? and aa.dataavaliacao = ? limit 1";
			$consulta = $pdo->prepare( $sql );
			$consulta->bindParam(1,$p[2]);
			$consulta->bindParam(2,$p[3]);
			$consulta->execute();
			//recuperar os dados
			$dados = $consulta->fetch(PDO::FETCH_OBJ);
	
            $dataavaliacao				= $dados->dataavaliacao;
            $altura						= $dados->altura;
            $pesoatual					= $dados->pesoatual;
            $pesoideal					= $dados->pesoideal;
            $bracodireitorelaxado		= $dados->bracodireitorelaxado;
            $bracodireitocontraido		= $dados->bracodireitocontraido;
            $antebracodireito			= $dados->antebracodireito;
            $punhodireito				= $dados->punhodireito;
            $ombro						= $dados->ombro;
            $torax						= $dados->torax;
            $cintura					= $dados->cintura;
            $abdomen					= $dados->abdomen;
            $quadril					= $dados->quadril;
            $panturrilhadireita			= $dados->panturrilhadireita;
            $coxadireita				= $dados->coxadireita;
            $coxaproximaldireita		= $dados->coxaproximaldireita;
            $biceps						= $dados->biceps;
            $triceps					= $dados->triceps;
            $subescapular				= $dados->subescapular;
            $axilarmedia				= $dados->axilarmedia;
            $toraxica					= $dados->toraxica;
            $suprailiaca				= $dados->suprailiaca;
            $abdominal					= $dados->abdominal;
            $coxa						= $dados->coxa;
            $panturrilhamedia			= $dados->panturrilhamedia;

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
                <div class="col-12 col-md-4">
					<label for="altura">Altura:</label>
					<input type="number" min="0" step="0.01" name="altura" 
					class="form-control" value="<?=$altura;?>">
				</div>
                <div class="col-12 col-md-4">
					<label for="pesoatual">Peso Atual:</label>
					<input type="number" name="pesoatual" 
					class="form-control" value="<?=$pesoatual;?>">
				</div>
                <div class="col-12 col-md-4">
					<label for="pesoideal">Peso Ideal:</label>
					<input type="number" min="0" step="0.01" name="pesoideal" 
					class="form-control" value="<?=$pesoideal;?>">
				</div>

            </div>
            
            <br>
            <hr>
            <h4 class="text-center"><strong>Circunferência</strong></h4>
            <hr>
            <div class="row">
                <div class="col-12 col-md-3">
					<label for="bracodireitorelaxado">Braço Direito Relaxado:</label>
					<input type="number" name="bracodireitorelaxado" 
					class="form-control" value="<?=$bracodireitorelaxado;?>">
				</div>
                <div class="col-12 col-md-3">
					<label for="bracodireitocontraido">Braço Direito Contraído:</label>
					<input type="number" name="bracodireitocontraido" 
					class="form-control" value="<?=$bracodireitocontraido;?>">
				</div>
                <div class="col-12 col-md-3">
					<label for="antebracodireito">Antebraço Direito:</label>
					<input type="number" name="antebracodireito" 
					class="form-control" value="<?=$antebracodireito;?>">
				</div>
                <div class="col-12 col-md-3">
					<label for="punhodireito">Punho Direito:</label>
					<input type="number" name="punhodireito" 
					class="form-control" value="<?=$punhodireito;?>">
				</div>
            </div>
			
            <hr>
            <h4 class="text-center"><strong>Tronco</strong></h4>
            <hr>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-fill bd-highlight">
					<label for="ombro">Ombro:</label>
					<input type="number" name="ombro" 
					class="form-control" value="<?=$ombro;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="torax">Torax:</label>
					<input type="number" name="torax" 
					class="form-control" value="<?=$torax;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="cintura">Cintura:</label>
					<input type="number" name="cintura" 
					class="form-control" value="<?=$cintura;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="abdomen">Abdômen:</label>
					<input type="number" name="abdomen" 
					class="form-control" value="<?=$abdomen;?>">
                </div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="quadril">Quadril:</label>
					<input type="number" name="quadril" 
					class="form-control" value="<?=$quadril;?>">
				</div>
            </div>
            
            <hr>
            <h4 class="text-center"><strong>Membros Inferiores</strong></h4>
            <hr>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-fill bd-highlight">
					<label for="panturrilhadireita">Panturilha Direita:</label>
					<input type="number" name="panturrilhadireita" 
					class="form-control" value="<?=$panturrilhadireita;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="coxadireita">Coxa Direita:</label>
					<input type="number" name="coxadireita" 
					class="form-control" value="<?=$coxadireita;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="coxaproximaldireita">Coxa Proximal Direita:</label>
					<input type="number" name="coxaproximaldireita" 
					class="form-control" value="<?=$coxaproximaldireita;?>">
				</div>
            </div>
            
            <hr>
            <h4 class="text-center"><strong>Pregas Cutâneas</strong></h4>
            <hr>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-fill bd-highlight">
					<label for="biceps">Bíceps:</label>
					<input type="number" name="biceps" 
					class="form-control" value="<?=$biceps;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="triceps">Tríceps:</label>
					<input type="number" name="triceps" 
					class="form-control" value="<?=$triceps;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="subescapular">Subescapular:</label>
					<input type="number" name="subescapular" 
					class="form-control" value="<?=$subescapular;?>">
                </div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="axilarmedia">Axilar Média:</label>
					<input type="number" name="axilarmedia" 
					class="form-control" value="<?=$axilarmedia;?>">
				</div>
            </div>

            <br>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-fill bd-highlight">
					<label for="toraxica">Tóraxica:</label>
					<input type="number" name="toraxica" 
					class="form-control" value="<?=$toraxica;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="suprailiaca">Supra-ilíaca:</label>
					<input type="number" name="suprailiaca" 
					class="form-control" value="<?=$suprailiaca;?>">
				</div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="abdominal">Abdominal:</label>
					<input type="number" name="abdominal" 
					class="form-control" value="<?=$abdominal;?>">
                </div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="coxa">Coxa:</label>
					<input type="number" name="coxa" 
					class="form-control" value="<?=$coxa;?>">
                </div>
                <div class="p-2 flex-fill bd-highlight">
					<label for="panturrilhamedia">Panturilha Média:</label>
					<input type="number" name="panturrilhamedia" 
					class="form-control" value="<?=$panturrilhamedia;?>">
				</div>
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

