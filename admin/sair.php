<?php
	//iniciar a sessao
	session_start();
	//apagar a sessao
	unset($_SESSION["nutrisystem"]);
	//redirecionar para o index
	header("Location: index.php");