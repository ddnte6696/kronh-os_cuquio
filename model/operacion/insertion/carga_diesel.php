<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  	if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  	$clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos del formulario
		$fecha=campo_limpiado($_POST['fecha'],0,0);
		$unidad=campo_limpiado($_POST['unidad'],2,0);
		$operador=campo_limpiado($_POST['operador'],2,0);
		$kilometraje=campo_limpiado($_POST['kilometraje'],0,0);

		$folio_contado=campo_limpiado($_POST['folio_contado'],0,0);
		$diesel_contado=campo_limpiado($_POST['diesel_contado'],0,0);
		$importe_diesel_contado=campo_limpiado($_POST['importe_diesel_contado'],0,0);
		$adblue_contado=campo_limpiado($_POST['adblue_contado'],0,0);
		$importe_adblue_contado=campo_limpiado($_POST['importe_adblue_contado'],0,0);

		$folio_credito=campo_limpiado($_POST['folio_credito'],0,0);
		$diesel_credito=campo_limpiado($_POST['diesel_credito'],0,0);
		$importe_diesel_credito=campo_limpiado($_POST['importe_diesel_credito'],0,0);
		$adblue_credito=campo_limpiado($_POST['adblue_credito'],0,0);
		$importe_adblue_credito=campo_limpiado($_POST['importe_adblue_credito'],0,0);
	//Verifico el dato de la clave del operador
		if ($operador=="vacio") {
			$operador='Null';
		}else{
			$operador="'$operador'";
		}
  //Defino la sentencia de busqueda de la existencia
		$existencia=busca_existencia("SELECT id as exist from cargas_diesel where fecha='$fecha' and unidad=$unidad and operador=$operador");
	//Evaluo la existencia
		if ($existencia=='') {
			$sentencia = "
				INSERT INTO cargas_diesel (
					fecha,
					unidad,
					operador,
					kilometraje,
					folio_contado,
					diesel_contado,
					importe_diesel_contado,
					adblue_contado,
					importe_adblue_contado,
					folio_credito,
					diesel_credito,
					importe_diesel_credito,
					adblue_credito,
					importe_adblue_credito,
					usuario_registra,
					fecha_registra
				) VALUES (
					'$fecha',
					$unidad,
					$operador,
					$kilometraje,
					$folio_contado,
					$diesel_contado,
					$importe_diesel_contado,
					$adblue_contado,
					$importe_adblue_contado,
					$folio_credito,
					$diesel_credito,
					$importe_diesel_credito,
					$adblue_credito,
					$importe_adblue_credito,
					'$clave',
					'".ahora(1)."'
				);
			";
			$resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
			if($resultado === TRUE){
				echo "
					<script>
						alert('Carga registrada');
						formulario_diesel();
					</script>
				";
			}
		}else{
			echo "
					<script>
						alert('Ya existe una carga registrada con este folio y ticket');
					</script>
				";
		}
?>