<?php
	//verificar se o arquivo existe
	if ( file_exists ( "verificalogin.php" ) )
		include "verificalogin.php";
	else
		include "../verificalogin.php";

    $idpessoa = $nome = $sexo = $datanascimento = $idade = $email =$estadocivil =
    $ocupacao = $cpf = $rg = $telefone = $cep = $endereco = $complemento = $bairro = $idcidade = 
    $cidade = $estado = "";

	// $idpessoa = $idusuario = $nome = $datanascimento = $email = $cpf = $cep = 
	// $endereco = $complemento = $bairro = $cidade_id = 
	// $cidade = $estado = $foto = $telefone = $celular = "";

	//$p[1] -> index.php (id do registro)
	if ( isset ( $p[2] ) ) {

		//selecionar os dados conforme o id
		$sql = "select p.*, date_format(p.datanasc,'%d/%m/%Y') as datanascimento,
			ci.cidade, ci.estado 
			from pessoa p 
			inner join cidade ci on (ci.idcidade = p.idcidade)
			where p.idpessoa = ? limit 1";
		$consulta = $pdo->prepare( $sql );
		$consulta->bindParam(1,$p[2]);
		$consulta->execute();
		//recuperar os dados
		$dados = $consulta->fetch(PDO::FETCH_OBJ);

        $idpessoa 		= $dados->idpessoa;
        $nome 			= $dados->nome;
        $sexo           = $dados->sexo;
        $datanascimento = $dados->datanascimento;
        $idade          = $dados->idade;
        $email 			= $dados->email;
        $estadocivil    = $dados->estadocivil;
        $ocupacao       = $dados->ocupacao;
        $cpf 			= $dados->cpf;
        $rg             = $dados->rg;
		$telefone 		= $dados->telefone;
        $cep 			= $dados->cep;
		$endereco 		= $dados->endereco;
		$complemento 	= $dados->complemento;
		$bairro 		= $dados->bairro;
		$cidade 		= $dados->cidade;
		$estado 		= $dados->estado;
		$idcidade 		= $dados->idcidade;

	}
?>
<div class="container">
	<div class="coluna">
		<h1 class="float-left">Cadastro de Pessoas</h1>

		<div class="float-right">
			<a href="cadastros/cliente" class="btn btn-success">
				<i class="fas fa-file"></i> Novo
			</a>

			<a href="listar/pessoa" class="btn btn-info">
				<i class="fas fa-search"></i> Listar
			</a>
		</div>

		<div class="clearfix"></div>

		<form name="cadastro" method="post" action="salvar/pessoa" data-parsley-validate enctype="multipart/form-data">

			<label for="idpessoa">ID:</label>
			<input type="text" name="idpessoa" 
			class="form-control" readonly
			value="<?=$idpessoa;?>">

			<br>
			<label for="nome">Nome da Pessoa:</label>
			<input type="text" name="nome" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
            value="<?=$nome;?>">
            
            <!-- <br>
			<label for="sexo">Sexo:</label>
			<input type="text" name="sexo" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
            value=""> -->
            
            <br>
			<label for="sexo">Sexo:</label>
            <select name="sexo" id="sexo"
            class="form-control" required  data-parsley-required-message="Selecione um Sexo">
                <option value=""></option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
                <option value="Outro">Outro</option>
            </select>
            <script type="text/javascript">
                $("#sexo").val('<?=$sexo;?>')
            </script>

            <br>
			<label for="datanascimento">Data de Nascimento:</label>
			<input type="text" name="datanascimento" required
			class="form-control" data-mask="99/99/9999"
			data-parsley-required-message="Preencha este campo"
            value="<?=$datanascimento;?>">
            
            <br>
			<label for="idade">Idade:</label>
			<input type="text" name="idade" id="idade" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
            value="<?=$idade;?>"
            onblur="calcularIdade(<?=$datanascimento;?>)">

            <br>
			<label for="email">E-mail:</label>
			<input type="email" name="email" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$email;?>">

			<?php

				$r = "";
				if ( empty ( $id ) ) {
					$r = " required data-parsley-required-message=\"Preencha este campo\" ";
				}

			?>

            <br>
			<label for="estadocivil">Estado Civil:</label>
			<select name="estadocivil" id="estadocivil"
            class="form-control" required  data-parsley-required-message="Selecione um Sexo">
                <option value=""></option>
                <option value="Solteiro(a)">Solteiro(a)</option>
                <option value="Casado(a)">Casado(a)</option>
                <option value="Divorciado(a)">Divorciado(a)</option>
            </select>
            <script type="text/javascript">
                $("#estadocivil").val('<?=$estadocivil;?>')
            </script>

            <br>
			<label for="ocupacao">Ocupacao:</label>
			<input type="text" name="ocupacao" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
            value="<?=$ocupacao;?>">

			<br>
			<label for="cpf">CPF:</label>
			<input type="text" name="cpf" id="cpf" required
			class="form-control" data-mask="999.999.999-99"
			data-parsley-required-message="Preencha este campo"
			value="<?=$cpf;?>"
			onblur="validaCpf(this.value)">

			<br>
			<label for="rg">RG:</label>
			<input type="text" name="rg" required
			class="form-control" 
			data-parsley-required-message="Preencha este campo"
			value="<?=$rg;?>">

            <br>
			<label for="telefone">Telefone/Celular:</label>
			<input type="text" name="telefone" required
			class="form-control" data-mask="(99) 999999999"
			data-parsley-required-message="Preencha este campo"
			value="<?=$telefone;?>">
			
			<br>
			<label for="cep">CEP:</label>
			<input type="text" name="cep" id="cep" 
			required data-mask="99999-999"
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$cep;?>"
			onblur="buscarEndereco(this.value)">

			<br>
			<label for="endereco">Endereço:</label>
			<input type="text" name="endereco" 
			id="endereco" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$endereco;?>">

			<br>
			<label for="complemento">Complemento:</label>
			<input type="text" name="complemento" id="complemento"
			class="form-control"
			value="<?=$complemento;?>">

			<br>
			<label for="bairro">Bairro:</label>
			<input type="text" name="bairro" id="bairro" required
			class="form-control"
			data-parsley-required-message="Preencha este campo"
			value="<?=$bairro;?>">

			<br>
			<div class="row">
				<div class="col-12 col-md-2">
					<label for="idcidade">ID Cidade:</label>
					<input type="text" name="idcidade" id="idcidade" class="form-control" required readonly value="<?=$idcidade;?>">
				</div>
				<div class="col-12 col-md-6">

					<label for="cidade">Cidade:</label>
					<input type="text" name="cidade" 
					id="cidade" class="form-control"
					required data-parsley-required-message="Preencha a cidade"
					value="<?=$cidade;?>">

				</div>
				<div class="col-12 col-md-4" id="cidades">

					<label for="estado">Estado:</label>
					<select name="estado" id="estado"
					class="form-control" required  data-parsley-required-message="Selecione um estado">
						<option value=""></option>
						<option value="AC">Acre</option>
						<option value="AL">Alagoas</option>
						<option value="AP">Amapá</option>
						<option value="AM">Amazonas</option>
						<option value="BA">Bahia</option>
						<option value="CE">Ceará</option>
						<option value="DF">Distrito Federal</option>
						<option value="ES">Espírito Santo</option>
						<option value="GO">Goiás</option>
						<option value="MA">Maranhão</option>
						<option value="MT">Mato Grosso</option>
						<option value="MS">Mato Grosso do Sul</option>
						<option value="MG">Minas Gerais</option>
						<option value="PA">Pará</option>
						<option value="PB">Paraíba</option>
						<option value="PR">Paraná</option>
						<option value="PE">Pernambuco</option>
						<option value="PI">Piaui</option>
						<option value="RJ">Rio de Janeiro</option>
						<option value="RN">Rio Grande do Norte</option>
						<option value="RS">Rio Grande do Sul</option>
						<option value="RO">Rondonia</option>
						<option value="RR">Roraima</option>
						<option value="SC">Santa Catarina</option>
						<option value="SP">São Paulo</option>
						<option value="SE">Sergipe</option>
						<option value="TO">Tocantins</option>
					</select>

					<script type="text/javascript">
						$("#estado").val('<?=$estado;?>')
					</script>
				</div>
			</div>

			<br>
			<button type="submit" class="btn btn-success">
				<i class="fas fa-check"></i> Gravar
			</button>

		</form>

	</div>
</div>

<script type="text/javascript">
	function validaCpf(cpf) {
		$.get("validacpf.php",{cpf:cpf},function(dados){

			if ( dados != 1 ) {
				//mensagem de erro
				alert(dados);
				//apagar o cpf
				$("#cpf").val("");
			}

		})
	}
	function buscarEndereco(cep) {
		$.getJSON("https://viacep.com.br/ws/"+cep+"/json", function(dados){

			$("#endereco").val(dados.logradouro);
			$("#bairro").val(dados.bairro);
			$("#estado").val(dados.uf);
			$("#cidade").val(dados.localidade);
			$("#complemento").val(dados.complemento);

			//buscar a cidade por ajax
			$.get("buscacidade.php",{cidade:dados.localidade,estado:dados.uf},function(data){

				if ( data == "Erro" ) {
					alert("Nenhuma cidade encontrada");
				} else {
					//jogar o id dentro do campo cidade_id
					$("#idcidade").val(data);
				}

			})

		})
	}
    function calcularIdade(datanascimento){
		$.get("calcularIdade.php",{idade:idade},function(dados){

            if ( dados != 1 ) {
                //mensagem de erro
                alert(dados);
                //apagar o cpf
                $("#idade").val("");
            }

        })
    }
</script>