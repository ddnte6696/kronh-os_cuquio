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
		$id_corrida=campo_limpiado($_POST['corrida'],2,0);
  //Verifico si alguno de los campos contiene datos y sino, Imprimo un mensaje de error
    if( isset($id) || isset($fecha) || isset($origen) || isset($nombre_envia) || isset($destino) || isset($nombre_recibe) || isset($id_corrida) ){
    	//Obtengo el identificador de punto
    		$punto=busca_existencia("SELECT punto AS exist FROM destinos WHERE destino='$destino'");
    	//Evaluo y obtengo el estado final
    		if ($punto==true) { $estado=2; }else{ $estado="4, fecha_recepcion='".ahora(1)."', fecha_entrega='".ahora(1)."'"; }
      //Verifico si existe una unidad y un operador asignados a la corrida
        $unidad=busca_existencia("SELECT unidad as exist from corrida where id=$id_corrida");
        if ($unidad!='') { $apuntador_unidad=", unidad=$unidad"; }else{ $apuntador_unidad=Null; }
        $operador=busca_existencia("SELECT operador as exist from corrida where id=$id_corrida");
        if ($operador!='') { $apuntador_operador=", operador='$operador'"; }else{ $apuntador_operador=Null; }
    	//Defino la sentencia para agregar el operador y la unidad a la corrida
    		$sentencia="
    			UPDATE paquete SET corrida=$id_corrida, usuario_asigna='$clave',estado=$estado, fecha_envio='".ahora(1)."'$apuntador_unidad $apuntador_operador WHERE id=$id ;
  			";
  		//Realizo la ejecucion de la sentencia
        $devuelto=ejecuta_sentencia_sistema($sentencia,true);
      //Si la insercion se realizo correctamente
        if ($devuelto==true) {
        	//Obtengo la corrida y hora en que fue enviado
        		$hora=campo_limpiado(transforma_hora(busca_existencia("SELECT hora as exist FROM corrida where id=$id_corrida;")),0,1);
        		$servicio=busca_existencia("SELECT servicio as exist FROM corrida where id=$id_corrida;");
        	echo "
        		<script>
        			alert('EL PAQUETE FOLIO N° $id DE $origen A $destino FUE ASIGNADO A LA CORRIDA DE $hora - $servicio');
        			$('#asignar_corrida_paqueteria').modal('hide');
        			pendientes_enviar();
      			</script>";
        }
      //
    }else{
      echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
      die();
    }
  //
?>