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
		$recibe=campo_limpiado($_POST['recibe'],0,1);
  //Verifico si alguno de los campos contiene datos y sino, Imprimo un mensaje de error
    if( isset($id) || isset($fecha) || isset($origen) || isset($nombre_envia) || isset($destino) || isset($recibe)){
    	//Defino la sentencia para agregar el operador y la unidad a la corrida
    		$sentencia="
    			UPDATE paquete SET recibe='$recibe' ,estado=4, fecha_entrega='".ahora(1)."' WHERE id=$id ;
  			";
  		//Realizo la ejecucion de la sentencia
        $devuelto=ejecuta_sentencia_sistema($sentencia,true);
      //Si la insercion se realizo correctamente
        if ($devuelto==true) {
        	echo "
        		<script>
        			alert('EL PAQUETE FOLIO N° $id DE $origen A $destino FUE ENTREGADO A $recibe');
        			$('#marcar_entrega_paqueteria').modal('hide');
        			pendientes_entregar();
      			</script>";
        }
      //
    }else{
      echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
      die();
    }
  //
?>