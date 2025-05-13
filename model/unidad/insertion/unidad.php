<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Se incluyen los archivos de conexión
	include_once A_CONNECTION;
	//Obtengo el ID del unidad activo
	$id_usr=campo_limpiado($_SESSION[UBI]['id'],2,0);
	//Recuperacion de los datos obtenidos del formulario
	$numero=campo_limpiado($_POST['numero'],0,0);
	$f_ingreso=campo_limpiado($_POST['f_ingreso'],0,0);
	$empresa=campo_limpiado($_POST['empresa'],2,0);
	$division=campo_limpiado($_POST['division'],2,0);
	$ap=md5(uniqid(mt_rand(),true));
	//Verifico que los datos enviados no sean vacios
	if(isset($numero) || isset($f_ingreso) || isset($empresa) || isset($division)){
		//Se define la sentencia de revision a ejecutar
	    $sentencia="SELECT count(id) as exist FROM unidades WHERE numero='$numero'";
	    //Trato de ejecutar la sentencia y sino arrojo el error
	    try {
			$search=$conn->prepare($sentencia);
			$search->execute();
			$tabla=$search->fetch(PDO::FETCH_ASSOC);
			$dto=$tabla['exist'];
			if ($dto<1) {
				$sentencia="
					INSERT INTO unidades (
						numero,
						f_ingreso,
						empresa,
						division
					) VALUES (
						'$numero',
						'$f_ingreso',
						$empresa,
						$division
					);
				";
				try {
					$sql=$conn->prepare($sentencia);
					$res=$sql->execute();
					echo "
						<script>
							alert('UNIDAD GENERADA.');
							registrar_unidad();
						</script>
				    ";
				} catch (PDOException $e) {
					//Almaceno el error en una variabLe
					$error=$e->getMessage();
					//Ubico el archivo desde donde se presenta el error
					$archivo=__FILE__;
					//Mando a escribir el mensaje
					escribir_log($error,$sentencia,$archivo);
					//Detengo el procedimiento
					die();
				}
			}else{
				echo "
					<script>
						alert('YA SE ENCUENTRA UNA UNIDAD CON ESTE NUMERO ECONOMICO.');
					</script>
			    ";
			}
		} catch (PDOException $e) {
			//Almaceno el error en una variabLe
			$error=$e->getMessage();
			//Ubico el archivo desde donde se presenta el error
			$archivo=__FILE__;
			//Mando a escribir el mensaje
			escribir_log($error,$sentencia,$archivo);
			//Detengo el procedimiento
			die();
		}
	}else{
		echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
	}
?>