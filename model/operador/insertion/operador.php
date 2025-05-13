<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Obtengo el ID del operador activo
		$id_usr=campo_limpiado($_SESSION[UBI]['id'],2,0);

		$nombre=campo_limpiado($_POST['name'],0,1);
		$apellidos=campo_limpiado($_POST['lastname'],0,1);
		$telefono=campo_limpiado($_POST['phone'],0,0);
		$f_ingreso=campo_limpiado($_POST['f_ingreso'],0,0);
		$empresa=campo_limpiado($_POST['empresa'],2,0);
		$direccion=campo_limpiado($_POST['address'],0,1);
		if ($_POST['address']=='') {
			$apuntador="direccion,";
			$direccion="'".campo_limpiado($_POST['address'],0,1)."',";
		}else{
			$apuntador=Null;
			$direccion=Null;
		}

		$datos_division=explode('##', campo_limpiado($_POST['division'],0,0));
		$division=campo_limpiado($datos_division[0],2,0);
		$prefijo=campo_limpiado($datos_division[1],2,0);
	//Verifico si hay algun campo que este vacio
		if(isset($clave) || isset($nombre) || isset($apellidos) || isset($f_ingreso) || isset($empresa) || isset($division)){
			//Se define la sentencia de revision a ejecutar
		    $sentencia="SELECT id as exist FROM operadores WHERE nombre='$nombre' and apellido='$apellidos'";
		  //Reviso si el dato ya existe
        $dto=busca_existencia($sentencia);
		  //Si los datos no existen, se crea un nuevo registro en la base de datos
        if ($dto<1) {
        	//Se define la sentencia a ejecutar
						$sentencia="
							INSERT INTO operadores (
								nombre,
								apellido,
								empresa,
								division,
								f_ingreso,
								$apuntador
								telefono
							) VALUES (
								'$nombre',
								'$apellidos',
								$empresa,
								$division,
								'$f_ingreso',
								$direccion
								'$telefono'
							)
						";
					//Trato de ejecutar la sentencia y sino arrojo el error
            $devuelto=ejecuta_sentencia_sistema($sentencia,TRUE);
          //Evaluo si se realizo la sentencia
            if ($devuelto==TRUE) {
            	//Obtengo el id del operador ya registrado
            		$sentencia="SELECT id as exist FROM operadores WHERE nombre='$nombre' and apellido='$apellidos'";
            	//Obtengo el id
            		$id = busca_existencia($sentencia);
            	//Concateno prefijo e ID para crear la clave
        				$clave="$prefijo-$id";
        			//Realizo la actualizacion de la clave del operador
        				$sentencia="UPDATE operadores set clave='$clave' where id=$id";
        			//Trato de ejecutar la sentencia y sino arrojo el error
		            $devuelto=ejecuta_sentencia_sistema($sentencia,TRUE);
		          //Defino la clave como contraseña y el token
		            $password=md5(campo_limpiado($clave,0,0,1));
		            $ap=md5(uniqid(mt_rand(),true));
		          //Creeo el usuario del operador
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
										taquilla,
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
										'N/A',
										'$clave',
										'$password',
										'$telefono',
										5,
										$empresa,
										$division,
										'GUADALAJARA',
										'$f_ingreso',
										'$ap',
										'common.png',
										'18||18||0',
										1,
										'".ahora(1)."',
										'$id_usr'
									);
								";
        			//Trato de ejecutar la sentencia y sino arrojo el error
		            $devuelto2=ejecuta_sentencia_sistema($sentencia,TRUE);
		          //Evaluo si se realizo la sentencia
		            if (($devuelto==TRUE) && ($devuelto2==TRUE)) {
		            	echo "
										<script>
											alert('OPERADOR REGISTRADO');
											registrar_operador();
										</script>
							    ";
		            }
		          //
            }
          //
				}else{
					echo "
						<script>
							alert('YA SE ENCUENTRA UN OPERADOR CON ESTA CLAVE');
						</script>
			    ";
				}
			//
		}else{
			echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
		}
	//
?>