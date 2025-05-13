<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Se obtienen los datos de los campos y se les da formato
		$id=campo_limpiado($_POST['opt_form'],2,0);
	//Se define la sentencia a ejecutar
		$sentencia="SELECT * FROM menu where id=$id";
	//Ejecuto la sentencia y obtengo los datos
		$datos_menu=retorna_datos_sistema($sentencia);
	//Evaluo que se regresara informacion
    if ($datos_menu['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $dato_menu = $datos_menu['data'];
      // Recorrer los datos y llenar las filas
        foreach ($dato_menu as $menu) {
          //extraigo los datos del registro
          	$directorio=$menu['directorio'];
						$sub_directorio=$menu['sub_directorio'];
						$archivo=$menu['archivo'];
					//Revio si el direcorio esta correcto
						if (($sub_directorio==Null)||($sub_directorio=='')) {
							$sub_directorio=Null;
						}else{
							$sub_directorio=$sub_directorio."/";
						}
					//Mando a llamar al archivo
						include_once A_MODEL.$directorio.'/'.$sub_directorio.$archivo.'.php';
					//
        }
      //
    }
  //
?>