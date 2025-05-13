<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $folio=campo_limpiado($_POST['folio'],0,0);
    $precio=campo_limpiado($_POST['precio'],0,0);
    $id_talonario=campo_limpiado($_POST['tal'],0,0);
  	$id_liquidacion=campo_limpiado($_POST['dato'],2,0);
  //Defino la sentencia de busqueda
    $sentencia="SELECT * FROM corte where id=$id_liquidacion";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          $usuario=$tabla['usuario'];
          $fecha=$tabla['fecha'];
        }
      //
    }
  //Verifico si este folio ya se encuentra liquidado
    $existencia=busca_existencia("
    	SELECT 
    		count(id) as exist 
			FROM 
				talonarios_liquidados 
			WHERE 
				fecha='$fecha' AND
				talonario=$id_talonario AND
				folio=$folio AND
				usuario='$usuario'
			;
		");
  //Evaluo el resultado
    if ($existencia==0) {
    	//Inserto el paquete en la liquidacion
    		$sentencia="
    			INSERT INTO talonarios_liquidados (
  					fecha,
						talonario,
						folio,
						importe,
						usuario,
						fecha_liquida,
						usuario_liquida
    			) VALUES(
    				'$fecha',
    				$id_talonario,
    				'$folio',
    				'$precio',
    				'$usuario',
    				'".ahora(1)."',
    				'$clave'
    			);
  			";
  		//Ejecuto la sentencia
  			$resultado=ejecuta_sentencia_sistema($sentencia,true);
  		//Evaluo el resultado
  			if ($resultado===true) {
  				//Realizo la actualizacion de la liquidacion
				    $sentencia="UPDATE corte SET importe_paquetes_talonario=importe_paquetes_talonario+$precio, cantidad_paquetes_talonario=cantidad_paquetes_talonario+1 where id=$id_liquidacion;";
				  //Ejecuto la sentencia
				    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
				  //aumento en 1 el folio actual
				    $sentencia="UPDATE talonario SET actual=actual+1, restantes=restantes-1 where id=$id_talonario;";
				  //Ejecuto la sentencia
				    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
				  //
  			}
  		//
    }else{
    	echo "
    		<script>
    			alert('ESTE FOLIO YA SE ENCUENTRA LIQUIDADO');
    		</script>
  		";
    }
?>