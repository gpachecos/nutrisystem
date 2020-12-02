<?php

	$senha = "Tidinha2019";

	//criptografar
	$senhac = password_hash($senha, PASSWORD_DEFAULT);

	echo "<p>Senha: $senha <br>
	Senha criptografada: $senhac </p>"; 

	$nova = $_GET["nova"];

	echo "<p>Senha informada: $nova</p>";

	if ( password_verify ( $nova, $senhac ) ) {
		echo "<p>A senha $nova é válida</p>";
	} else {
		echo "<p>A senha $nova é inválida</p>";
	}