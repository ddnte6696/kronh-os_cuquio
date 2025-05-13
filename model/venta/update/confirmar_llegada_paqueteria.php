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
  //Verifico si alguno de los campos contiene datos y sino, Imprimo un mensaje de error
    if( isset($id) || isset($fecha) || isset($origen) || isset($nombre_envia) || isset($destino) || isset($nombre_recibe)){
    	//Defino la sentencia para agregar el operador y la unidad a la corrida
    		$sentencia="
    			UPDATE paquete SET estado=3,fecha_recepcion='".ahora(1)."' WHERE id=$id ;
  			";
  		//Realizo la ejecucion de la sentencia
        $devuelto=ejecuta_sentencia_sistema($sentencia,true);
      //Si la insercion se realizo correctamente
        if ($devuelto==true) {
        	echo "
        		<script>
        			alert('EL PAQUETE FOLIO N° $id DE $origen A $destino FUE MARCADO COMO RECIBIDO');
        			$('#confirmar_llegada_paqueteria').modal('hide');
        			pendientes_recibir();
      			</script>";
        }
      //
    }else{
      echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
      die();
    }
  //
?>