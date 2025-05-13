<?php
	//
		if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del usuario en turno
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
	//obtengo los datos mandados por el formulario
		$datos=explode('||',campo_limpiado($_POST['datos'],2,0));
		$id=$datos[0];
		$fecha=$datos[1];
		$origen=$datos[2];
		$nombre_envia=$datos[3];
		$destino=$datos[4];
		$nombre_recibe=$datos[5];
  //Obtengo el estado actual de la paqueteria
    switch ($datos[6]) {
      case '1':
        $estado_actual="VENTA REALIZADA";
      break;
      case '2':
        $estado_actual="EN RUTA";
      break;
      case '3':
        $estado_actual="RECIBIDA";
      break;
      case '4':
        $estado_actual="ENTREGADA";
      break;
      case '5':
        $estado_actual="CANCELADA";
      break;
    }
		$estado=campo_limpiado($_POST['estado'],2,0);
  //Obtengo el estado nuevo de la paquetera
    switch ($estado) {
      case '1':
        $estado_nuevo="VENTA REALIZADA";
      break;
      case '2':
        $estado_nuevo="EN RUTA";
      break;
      case '3':
        $estado_nuevo="RECIBIDA";
      break;
      case '4':
        $estado_nuevo="ENTREGADA";
      break;
      case '5':
        $estado_nuevo="CANCELADA";
      break;
    }
  //Verifico si alguno de los campos contiene datos y sino, Imprimo un mensaje de error
    if( isset($id) || isset($fecha) || isset($origen) || isset($nombre_envia) || isset($destino) || isset($nombre_recibe) || isset($estado) ){
    	//Defino la sentencia para agregar el operador y la unidad a la corrida
    		$sentencia="
    			UPDATE paquete SET estado=$estado WHERE id=$id ;
  			";
  		//Realizo la ejecucion de la sentencia
        $devuelto=ejecuta_sentencia_sistema($sentencia,true);
      //Si la insercion se realizo correctamente
        if ($devuelto==true) {
        	echo "
        		<script>
        			alert('EL PAQUETE FOLIO N° $id CAMBIA DEL ESTADO  $estado_actual A $estado_nuevo');
        			$('#retorna_estado_paqueteria').modal('hide');
              busca_paqueterias();
      			</script>";
        }
      //
    }else{
      echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
      die();
    }
  //
?>