<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//obtengo los datos mandados por el formulario
		$id_corrida=campo_limpiado($_POST['id_corrida_datos'],2,0);
		$unidad=campo_limpiado($_POST['unidad'],2,1);
		$operador=campo_limpiado($_POST['operador'],2,1);
	//Obtengo los datos de la corrida
    $sentencia="SELECT * FROM corrida where id=$id_corrida;";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          $servicio=$tabla['servicio'];
          $fecha=$tabla['fecha'];
          $hora=$tabla['hora'];
          $punto_origen=$tabla['punto_origen'];
        }
      //
    }
	//Defino la sentencia para agregar el operador y la unidad a la corrida
		$sentencia="
			UPDATE 
				corrida 
			SET 
				operador='$operador', 
				unidad=$unidad 
			WHERE 
				id=$id_corrida
			;
		";
	//Realizo la ejecucion de la sentencia
    $devuelto1=ejecuta_sentencia_sistema($sentencia,true);
  //Defino la sentencia para agregar el operador y la unidad a los boletos
		$sentencia="
			UPDATE 
				boleto 
			SET 
				operador='$operador', 
				unidad=$unidad 
			WHERE 
				corrida='$servicio' and
				f_corrida='$fecha' and
				hora_corrida='$hora' and
				origen='$punto_origen'
			;
		";
	//Realizo la ejecucion de la sentencia
    $devuelto2=ejecuta_sentencia_sistema($sentencia,true);
  //Defino la sentencia para agregar el operador y la unidad a las paqueterias
		$sentencia="
			UPDATE 
				paquete 
			SET 
				operador='$operador', 
				unidad=$unidad 
			WHERE 
				corrida=$id_corrida
			;
		";
	//Realizo la ejecucion de la sentencia
    $devuelto3=ejecuta_sentencia_sistema($sentencia,true);
  //Si la insercion se realizo correctamente
    if (($devuelto1===true) && ($devuelto2===true) && ($devuelto3===true)) {
    	echo "
    		<script>
    			alert('OPERADOR Y UNIDAD ASIGNADOS CORRECTAMENTE');
    			$('#asignar_datos').modal('hide');
    			mostrar_corridas();
  			</script>";
    }
  //
?>