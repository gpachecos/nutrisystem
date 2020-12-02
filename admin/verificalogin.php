<?php
	//verifica se a sessao esta ativa
	//verifica se existe o id na sessao hqs
	//se não existir um dos dois mostra a mensagem e dá um exit na pagina
	if ( ( session_status() != PHP_SESSION_ACTIVE ) or ( !isset ( $_SESSION["hqs"]["id"] ) ) ) {
		echo "<p>Esta página não pode ser exibida, por favor efetuar login</p>";
		exit;
	}
