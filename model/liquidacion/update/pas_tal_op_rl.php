<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
    $folio=campo_limpiado($_POST['folio'],0,0);
    $precio=campo_limpiado($_POST['precio'],0,0);
    $id_talonario=campo_limpiado($_POST['tal'],0,0);
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
    $clave=$pre[3];
  //Obtengo el ID de la liquidacion
    $id_liquidacion=busca_existencia("SELECT id AS exist FROM liq_op_rl where unidad=$id_unidad and operador='$clave' and fecha='$fecha_trabajo'");
  //Verifico si este folio ya se encuentra liquidado
    $existencia=busca_existencia("
    	SELECT 
    		count(id) as exist 
			FROM 
				talonarios_liquidados 
			WHERE 
				fecha='$fecha_trabajo' AND
				talonario=$id_talonario AND
				folio=$folio AND
				usuario='$clave' AND
				unidad=$id_unidad
			;
		");
  //Evaluo el resultado
    if ($existencia==0) {
    	//Inserto el boleto en la liquidacion
    		$sentencia="
    			INSERT INTO talonarios_liquidados (
  					fecha,
						talonario,
						folio,
						importe,
						usuario,
						unidad,
						fecha_liquida,
						usuario_liquida
    			) VALUES(
    				'$fecha_trabajo',
    				$id_talonario,
    				'$folio',
    				'$precio',
    				'$clave',
    				$id_unidad,
    				'".ahora(1)."',
    				'$usuario'
    			);
  			";
  		//Ejecuto la sentencia
  			$resultado=ejecuta_sentencia_sistema($sentencia,true);
  		//Evaluo el resultado
  			if ($resultado===true) {
  				//Realizo la actualizacion de la liquidacion
				    $sentencia="UPDATE liq_op_rl SET talonario=talonario+$precio where id=$id_liquidacion;";
				  //Ejecuto la sentencia
				    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
				  //aumento en 1 el folio actual
				    $sentencia="UPDATE talonario SET actual=actual+1, restantes=restantes-1 where id=$id_talonario;";
				  //Ejecuto la sentencia
				    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
				  //Evaluo el resulado
				    if ($resultado === true) {
				      //Recalculo los importes y la comision
				        recalcular_liq_op_rl($id_liquidacion,FALSE);
				      //
				    }
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