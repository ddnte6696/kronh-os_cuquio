<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
	//Obtengo el usuario que esta logueado
		$usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
	//Obtengo los datos enviados por el formulario
		$id_producto = campo_limpiado($_POST['datos_producto'],2,0,1);
		$nota = campo_limpiado($_POST['nota'],0,1);
		$cantidad = campo_limpiado($_POST['cantidad'],0,1,1);
		$destino= campo_limpiado($_POST['destino'],0,1,1);
		$observacion = campo_limpiado($_POST['observacion'],0,1);
		$f_salida = campo_limpiado($_POST['f_salida'],0,0,1);
	//Defino variables de uso
		$fecha_actual = ahora(1);
		$hora_actual = ahora(2);
	//Busco los datos del producto
		$sentencia = "SELECT * FROM almacen WHERE id=$id_producto";
	//Ejecuto la sentencia y almaceno lo obtenido en una variable
		$resultado_sentencia = retorna_datos_sistema($sentencia);
	//Identifico si el reultado no es vacio
		if ($resultado_sentencia['rowCount'] > 0) {
			//Almaceno los datos obtenidos
				$resultado = $resultado_sentencia['data'];
			// Recorrer los datos y llenar las filas
				foreach ($resultado as $tabla) {
					//Almceno los datos en una variable
						$ubicacion = $tabla['ubicacion'];
						$existencia = $tabla['cantidad'];
						$precio = number_format($tabla['precio'], 2);
					//
				}
			//
		}
	//Evaluo si la cantidad de salida es mayor a la existencia
		if ($cantidad > $existencia) {
			//Imprimo un mensaje de confirmacion
				echo "
					<script>
						alert('LA CANTIDAD DE SALIDA ES MAYOR A LA EXISTENCIA');
					</script>
				";
			//Salgo del script
				exit();
			//
		}
	//Defino la sentencia para actualizar el inventario
		$sentencia = "UPDATE almacen SET cantidad=cantidad-$cantidad where id=$id_producto;";
	//Ejecuto la sentencia
		$resultado = ejecuta_sentencia_sistema($sentencia, true);
	//Evaluo el resultado
		if ($resultado === TRUE) {
			//Realizo la insercion en la tabla de compras
				$sentencia2="
					INSERT INTO movimientos_almacen (
						id_producto,
						nota,
						cantidad,
						precio,
						destino,
						observacion,
						f_movimiento,
						f_registro,
						h_registro,
						tipo_movimiento,
						usuario
					) VALUES (
						'$id_producto',
						'$nota',
						'$cantidad',
						'$precio',
						'$destino',
						'$observacion',
						'$f_salida',
						'$fecha_actual',
						'$hora_actual',
						2,
						'$usuario'
					);
				";
			//Ejecuto la sentencia
				$resultado2 = ejecuta_sentencia_sistema($sentencia2, true);
			//Evaluo el resultado
				if ($resultado2 === TRUE) {
					echo "
						<script>
							alert('SALIDA REGISTRADA');
						</script>
					";
				}
			//Imprimo un mensaje de confirmacion
				echo "
					<script>
						alert('INVENTARIO ACTUALIZADO EXITOSAMENTE');
						$('#reducir_existencia').modal('hide');
						materiales();
					</script>
				";
			//
		}
	//
?>