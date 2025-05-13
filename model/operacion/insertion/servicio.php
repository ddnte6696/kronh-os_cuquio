<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  	if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  	$clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos del formulario
		$origen=campo_limpiado($_POST['origen'],2,0);
		$dto_ruta=explode('||',campo_limpiado($_POST['ruta'],2,0));
		$nombre_ruta=$dto_ruta[0];
		$identificador=$dto_ruta[1];
		$id_punto_inicial=$dto_ruta[2];
		$punto_inicial=$dto_ruta[3];
		$id_punto_origen=$dto_ruta[4];
		$punto_origen=$dto_ruta[5];
		$id_punto_final=$dto_ruta[6];
		$punto_final=$dto_ruta[7];
		$hora=campo_limpiado($_POST['hora'],0,0);
  //Defino la sentencia de busqueda de la existencia
		$sentencia="SELECT id as exist from servicio where hora='$hora' and nombre_ruta='$nombre_ruta' and identificador='$identificador' and id_punto_inicial='$id_punto_inicial' and punto_inicial='$punto_inicial' and id_punto_origen='$id_punto_origen' and punto_origen='$punto_origen' and id_punto_final='$id_punto_final' and punto_final='$punto_final'";
		$existencia=busca_existencia($sentencia);
	//Evaluo la existencia
		if ($existencia=='') {
			$sentencia = "
				INSERT INTO servicio(
					nombre_ruta,
					identificador,
					id_punto_inicial,
					punto_inicial,
					id_punto_origen,
					punto_origen,
					id_punto_final,
					punto_final,
					hora,
					estatus
				) VALUES (
					'$nombre_ruta',
					'$identificador',
					'$id_punto_inicial',
					'$punto_inicial',
					'$id_punto_origen',
					'$punto_origen',
					'$id_punto_final',
					'$punto_final',
					'$hora',
					true
				);
			";
			$resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
			if($resultado === TRUE){
				echo "
					<script>
						alert('SERVICIO REGISTRADO');
						registrar_servicio();
					</script>
				";
			}
		}else{
			echo "
					<script>
						alert('ESTE SERVICIO YA SE ENCUENTRA REGISTRADO');
					</script>
				";
		}
?>