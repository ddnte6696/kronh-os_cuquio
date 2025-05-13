<?php
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Constante del sistema a usar
		if ($_SESSION['ubi']=='') { $_SESSION['ubi']="kronh-os"; }
		define('UBI', $_SESSION['ubi'] );
	//Defino la zona horaria
		date_default_timezone_set('America/Monterrey');
	//Obtngo y asigno los datos de la DB
	//Direccionamiento de archivos
		define( 'A_RAIZ', $_SERVER['DOCUMENT_ROOT'].'/'.UBI.'/' );
		define( 'A_LIB', A_RAIZ.'lib/' );
		define( 'A_JS', A_RAIZ.'js/' );
		define( 'A_MODEL', A_RAIZ.'model/' );
		define( 'A_DOCS', A_RAIZ.'docs/' );
		define( 'A_LOGS', A_RAIZ.'logs/' );
		define( 'A_VIEW', A_RAIZ.'view/' );
		define( 'A_CSS', A_RAIZ.'css/' );
		define( 'A_IMG', 'img/' );
		define( 'A_CONNECTION', A_LIB.'connection.sql.db.php' );
		define( 'TITLE', 'Sistema Interno de Control Operativo' );
		//
	//Defino constantes de imagenes
		define( 'LOGO_KORNHOS', A_IMG.'logos/KRONHOS.png' );
		define( 'LOGO_YAHUALICA',A_IMG.'logos/logo_yahua.png' );
	//Constantes para conexion a base de datos
		//conexion a la base de datos del sistema (SANDBOX)
			define('USER_DB','root');
			define('PASSWRD_DB','');
			define('HOST_DB','localhost');
			define('NAME_DB','kronh-os');
			define('PORT_DB','3306');
		/*//conexion a la base de datos del sistema (PRODUCCION)
			define('USER_DB','omnibusg_oyg');
			define('PASSWRD_DB','ZzjWr1ZaX1RJJz2');
			define('HOST_DB','localhost');
			define('NAME_DB','omnibusg_kronh-os');
			define('PORT_DB','3306');
		//*/
	//Librerias de PHPMailer
    	require A_LIB.'PHPMailer/PHPMailer.php';
		require A_LIB.'PHPMailer/Exception.php';
		require A_LIB.'PHPMailer/SMTP.php';
	//Librerias de encriptado y limpieza
		include_once A_LIB.'self/self_lmpz.php';
    	include_once A_LIB.'self/self_form_sender.php';
    	include_once A_LIB.'self/self_ncrptcn.php';
    //Archivo de funciones varias
    	include_once A_LIB.'funciones.php';
	//
?>