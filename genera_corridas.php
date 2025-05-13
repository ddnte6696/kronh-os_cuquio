<?php
	//Reviso si la sesion esta iniciada o no
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Reviso si la constante de sesion
		if ($_SESSION['ubi']=='') { $_SESSION['ubi']="kronh-os"; }
	//Realizo la inclusio del archivo de configuracion
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Realizo la ejecucion de la funcion
		$cantidad=genera_corridas(ahora(1),'');
	//cONDICIONAL PARA IMPRESION DE MENSAJE
		if ($cantidad==0) {
			echo "NO SE REALIZO NINGUN REGISTRO";
		}elseif ($cantidad==1) {
			echo "SE REALIZO SOLO UN REGISTRO";
		} else {
			echo "SE REALIZARON $cantidad REGISTROS";
		}
	//Detengo el proceso una vez que se ejecuta
		die();
	//
?>