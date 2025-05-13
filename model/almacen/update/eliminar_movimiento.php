<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
	//Obtengo el usuario que esta logueado
		$usuario=campo_limpiado($_SESSION[UBI]['nombre'],2,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],2,0);
	//Obtengo los datos enviados por el formulario
    $id = campo_limpiado($_POST['datos'],2,0,1);
    $comentario = campo_limpiado($_POST['observacion'],0,1);
  //Defino variables de uso
		$fecha_actual = campo_limpiado(transforma_fecha(ahora(1),1, " de "),0,1);
	//Busco el dato del movimiento
		$sentencia = "SELECT * FROM movimientos_almacen WHERE id=$id";
	//Ejecuto la sentencia y almaceno lo obtenido en una variable
		$resultado_sentencia = retorna_datos_sistema($sentencia);
	//Identifico si el reultado no es vacio
		if ($resultado_sentencia['rowCount'] > 0) {
			//Almaceno los datos obtenidos
				$resultado = $resultado_sentencia['data'];
			// Recorrer los datos y llenar las filas
				foreach ($resultado as $tabla) {
					//Almceno los datos en una variable
						$id_producto = $tabla['id_producto'];
						$cantidad = $tabla['cantidad'];
						$observacion = $tabla['observacion'];
						$tipo_movimiento = $tabla['tipo_movimiento'];
					//
				}
			//
		}
  //Identifico si se integro una observacion en el formulario
    if ($comentario==""){
      $detalle=" NO SE DETERMINO EL MOTIVO DE LA CANCELACION";
    }else{
      $detalle=" MOTIVO DE LA CANCELACION: $comentario";
    }
  //Evaluo si el comentario es vacio o tiene datos
    if ($observacion == "") {
        $nuevo_comentario="MOVIMIENTO CANCELADO POR $usuario EL $fecha_actual, $detalle";
    }else{
      $nuevo_comentario="$observacion; MOVIMIENTO CANCELADO POR $usuario EL $fecha_actual, $detalle";
    }
  //En base al tipo de movimiento, identifico la operacion a ejecutar
    if ($tipo_movimiento == 1) {
      //Defino la sentencia para actualizar el inventario
      $sentencia = "UPDATE almacen SET cantidad=cantidad-$cantidad where id=$id_producto;";
    }else{
      //Defino la sentencia para actualizar el inventario
        $sentencia = "UPDATE almacen SET cantidad=cantidad+$cantidad where id=$id_producto;";
    }
	//Ejecuto la sentencia
		$resultado = ejecuta_sentencia_sistema($sentencia, true);
	//Evaluo el resultado
		if ($resultado === TRUE) {
			//Realizo la modificacion del estado del movimiento
				$sentencia2="UPDATE movimientos_almacen SET estado=0, observacion='$nuevo_comentario' WHERE id=$id;";
			//Ejecuto la sentencia
				$resultado2 = ejecuta_sentencia_sistema($sentencia2, true);
			//Evaluo el resultado
				if ($resultado2 === TRUE) {
					echo "
						<script>
							alert('MOVIMIENTO CANCELADO EXITOSAMENTE');
						</script>
					";
				}
			//Imprimo un mensaje de confirmacion
				echo "
					<script>
						alert('INVENTARIO ACTUALIZADO EXITOSAMENTE');
						$('#eliminar_movimiento').modal('hide');
						ver_movimientos()
					</script>
				";
			//
		}
	//
?>