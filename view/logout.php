<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Destruyo la sesion y sus datos
		session_destroy();
	//Redirijo la pagina a la de intranet
		header("Location: ../index.php");
	//
?>