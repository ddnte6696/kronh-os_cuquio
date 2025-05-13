<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
  	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Obtengo el usuario que esta logueado
		$usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
	//Obtengo los datos enviados por el formulario
		$nota=campo_limpiado($_POST['nota'],0,0,1);
		$producto=campo_limpiado(elimina_especiales($_POST['producto']),0,1,1);
		$proveedor=campo_limpiado($_POST['proveedor'],0,1,1);
		$cantidad=campo_limpiado($_POST['cantidad'],0,1,1);
		$precio=campo_limpiado($_POST['precio'],0,1,1);
		$ubicacion=campo_limpiado($_POST['ubicacion'],0,1,1);
		$observacion=campo_limpiado($_POST['observacion'],0,1,1);
		$f_ingreso=campo_limpiado($_POST['f_ingreso'],0,0,1);
	//Defino variables de uso
		$referencia=referencia_temporal();
		$fecha_actual = ahora(1);
		$hora_actual = ahora(2);
	//Defino la sentencia para insertar en la BD
		$sentencia="
			INSERT INTO almacen (
				proveedor,
				producto,
				cantidad,
				precio,
				observaciones,
				ubicacion,
				referencia
			) VALUES (
				'$proveedor',
				'$producto',
				'$cantidad',
				'$precio',
				'$observacion',
				'$ubicacion',
				'$referencia'
			);
		";
	//Ejecuto la sentencia
		$resultado=ejecuta_sentencia_sistema($sentencia,true);
	//Evaluo el resultado
		if ($resultado===TRUE) {
			//Busco el producto en base a la referencia
				$id_producto=busca_existencia("SELECT id AS exist FROM almacen WHERE referencia='$referencia'");
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
						'$ubicacion',
						'$observacion',
						'$f_ingreso',
						'$fecha_actual',
						'$hora_actual',
						1,
						'$usuario'
					);
				";
			//Ejecuto la sentencia
				$resultado2=ejecuta_sentencia_sistema($sentencia2,true);
			//Evaluo el resultado
				if ($resultado2===TRUE) {
				echo "
					<script>
						alert('ADQUISICION REGISTRADA');
					</script>
				";
				}
			//Imprimo un mensaje de confirmacion
				echo "
				<script>
					alert('PRODUCTO AGREGADO AL INVENTARIO EXITOSAMENTE');
					registrar_material();
				</script>
			";
		//
		}
	//
?>