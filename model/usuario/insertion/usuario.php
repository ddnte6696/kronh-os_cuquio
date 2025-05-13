<?php
	error_reporting(E_ALL);
  ini_set("display_errors", 1);
	//Se revisa si la sesión esta iniciada y sino se inicia
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Obtengo el ID del usuario activo
		$id_usr=campo_limpiado($_SESSION[UBI]['id'],2,0,1);
	//Obtengo los datos enviados por el formulario
		$nombre=campo_limpiado($_POST['name'],0,1,1);
		$apellidos=campo_limpiado($_POST['lastname'],0,1,1);
		$telefono=campo_limpiado($_POST['phone']);
		$correo=campo_limpiado($_POST['mail']);
		$f_ingreso=campo_limpiado($_POST['f_ingreso'],0,0,1);

		$clave=campo_limpiado($_POST['user'],0,0,1);
		$password=md5(campo_limpiado($_POST['password'],0,0,1));

		$puesto=campo_limpiado($_POST['puesto'],2,0,1);
		$empresa=campo_limpiado($_POST['empresa'],2,0,1);
		$division=campo_limpiado($_POST['division'],2,0,1);
		$ap=md5(uniqid(mt_rand(),true));
	//Verifico si existe este usuario
		$sentencia="SELECT count(id) as exist FROM usuarios WHERE clave='$clave'";
    //Trato de ejecutar la sentencia y sino arrojo el error
	    $existencia=busca_existencia($sentencia);
        if ($existencia<1) {
        	//Procedo a insertar los datos
				$sentencia="
					INSERT INTO usuarios (
						nombre,
						apellido,
						correo,
						clave,
						password,
						telefono,
						puesto,
						empresa,
						division,
						f_ingreso,
						token,
						photo,
						permisos,
						visual,
						f_registro,
						usuario_registra
					) VALUES (
						'$nombre',
						'$apellidos',
						'$correo',
						'$clave',
						'$password',
						'$telefono',
						$puesto,
						$empresa,
						$division,
						'$f_ingreso',
						'$ap',
						'common.png',
						'0||||0',
						1,
						'".ahora(1)."',
						'$id_usr'
					);
				";
			//Realizo la ejecucion de la sentencia
                $devuelto=ejecuta_sentencia_sistema($sentencia,true);
        	//Si la insercion se realizo correctamente
                if ($devuelto==true) {
					echo "
						<script>
							alert('USUARIO GENERADO.');
							registrar_usuario();
						</script>
				    ";
                }
            //
        }else{
			echo "
				<script>
					alert('YA SE ENCUENTRA UNA CUENTA CON ESTA CLAVE.');
				</script>
		    ";
		}
	//
?>