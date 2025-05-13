<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Se incluye el archivo de conexión
	include_once A_CONNECTION;
	//Obtengo el id del usuario actual
	$usuario=$_SESSION['kronh-os_cuquio']['id'];
	//Se obtienen los datos de los campos
	$fecha=$_POST['fecha'];
	//Se definen variables
	$linea = 0;
	$linea2 = 0;
	$contador = 0;
	// Verificar si se ha enviado un archivo y si es de tipo CSV
	if (isset($_FILES['imagen']) && $_FILES['imagen']['type'] === 'text/csv') {
		$nombre_archivo = $_FILES['imagen']['name'];
		$nombre_temporal = $_FILES['imagen']['tmp_name'];

		$filas = file($nombre_temporal);
		$ultima_linea = count($filas);
		// Abrir el archivo CSV
		if (($archivo = fopen($nombre_temporal, "r")) !== FALSE) {
			$linea = 0;
			while (! feof($archivo)) {
				// Recorrer el archivo línea por línea
				while (($datos = fgetcsv($archivo, 1000, ",")) !== FALSE) {
					$linea++;
					// Ignorar las primeras líneas que parecen contener solo valores vacíos y seguir hasta la ultima linea
					if (($linea > 1)&&($linea<=$ultima_linea)) {
						// Procesar los datos de cada línea aquí
						$unidad=campo_limpiado($datos[0],0,0);
						$ticket=campo_limpiado($datos[1],0,0);
						$folio=campo_limpiado($datos[2],0,0);
						$diesel=campo_limpiado($datos[3],0,0);
						$adblue=campo_limpiado($datos[4],0,0);
						$operador=campo_limpiado($datos[6],0,0);
						//busco el numero de la unidad
						$sentencia="SELECT id FROM unidades where numero=$unidad";
						try {
							$r3=$conn->prepare($sentencia);
							$r3->execute();
							$searc3=$r3->fetch(PDO::FETCH_ASSOC);
							$id_uni=$searc3['id'];
						} catch (PDOException $e) {
							//Almaceno el error en una variabLe
							$error=$e->getMessage();
							//Creo la sentencia que se va a escribir en el log de errores
							$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia]-[ARCHIVO: ".__FILE__."].";
							//Mando a escribir el mensaje
							escribir_log($mensaje);
							//Imprimo un mensaje
							echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
							//Detengo el procedimiento
							die();
						}
						//Identifico la fecha de la ultima carga de esa unidad
						$sentencia1="SELECT max(fecha) as fecha_ant FROM cargas_diesel where fecha<'$fecha' and id_unidad=$id_uni";
						try {
							$query2=$conn->prepare($sentencia1);
							$query2->execute();
							while ($tabla2=$query2->fetch(PDO::FETCH_ASSOC)) {
								$fecha_ant=$tabla2['fecha_ant'];
							}
						} catch (PDOException $e) {
							//Almaceno el error en una variabLe
							$error=$e->getMessage();
							//Creo la sentencia que se va a escribir en el log de errores
							$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia1]-[ARCHIVO: ".__FILE__."].";
							//Mando a escribir el mensaje
							escribir_log($mensaje);
							//Imprimo un mensaje
							echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
							//Detengo el procedimiento
							die();
						}
						//Si encuentra alguna carga anterior a la fecha a cargar
						if ($fecha_ant!=null) {
							//Busco el kilometraje anterior en base a la ultima fecha de carga y el ID de la unidad
							$sentencia2="SELECT kilometros as km_ant FROM cargas_diesel where fecha='$fecha_ant' and id_unidad=$id_uni";
							try {
								$query2=$conn->prepare($sentencia2);
								$query2->execute();
								while ($tabla2=$query2->fetch(PDO::FETCH_ASSOC)) {
									//Extraigo el kilometraje anterior y le sumo la cantidad de kilometros recorridos
									$kilometros=$tabla2['km_ant']+$datos[5];
								}
							} catch (PDOException $e) {
								//Almaceno el error en una variabLe
								$error=$e->getMessage();
								//Creo la sentencia que se va a escribir en el log de errores
								$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia2]-[ARCHIVO: ".__FILE__."].";
								//Mando a escribir el mensaje
								escribir_log($mensaje);
								//Imprimo un mensaje
								echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
								//Detengo el procedimiento
								die();
							}
						}else{
							//Solo le asigno los kilometros recorridos
							$kilometros=$datos[5];
						}
						//Si el operador enviado es diferente de N/A
						if ($operador=='N/A') {
							$id_op='Null';
						}else{
							$sentencia3="SELECT id FROM operadores where clave='$operador'";
							try {
								$r1=$conn->prepare($sentencia3);
								$r1->execute();
								$search=$r1->fetch(PDO::FETCH_ASSOC);
								$id_op=$search['id'];
							} catch (PDOException $e) {
								//Almaceno el error en una variabLe
								$error=$e->getMessage();
								//Creo la sentencia que se va a escribir en el log de errores
								$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia3]-[ARCHIVO: ".__FILE__."].";
								//Mando a escribir el mensaje
								escribir_log($mensaje);
								//Imprimo un mensaje
								echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
								//Detengo el procedimiento
								$id_op='Null';
							}
						}
						//extraigo los precios del diesel y del adblue actuales
						$sentencia4="SELECT precio_diesel, precio_adblue FROM constantes";
						try {
							$search=$conn->prepare($sentencia4);
							$search->execute();
							while ($tabla=$search->fetch(PDO::FETCH_ASSOC)) {
								$precio_adblue=$tabla['precio_adblue'];
								$precio_diesel=$tabla['precio_diesel'];
							}
						} catch (PDOException $e) {
							//Almaceno el error en una variabLe
							$error=$e->getMessage();
							//Creo la sentencia que se va a escribir en el log de errores
							$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia4]-[ARCHIVO: ".__FILE__."].";
							//Mando a escribir el mensaje
							escribir_log($mensaje);
							//Imprimo un mensaje
							echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
							//Detengo el procedimiento
							die();
						}
						$sentencia="SELECT count(id) as exist from cargas_diesel where ticket='$ticket' and folio='$folio'";
						try {
							$search=$conn->prepare($sentencia);
							$search->execute();
							$tabla=$search->fetch(PDO::FETCH_ASSOC);
							if (($tabla['exist']<1)||(($folio==0)&&($ticket==0))) {
								//Hago la insecion en la base de datos
								$sentencia = "
									INSERT INTO cargas_diesel ( 
										id_usuario, 
										id_unidad, 
										ticket, 
										folio, 
										fecha, 
										hora, 
										litros, 
										kilometros, 
										id_operador, 
										adblue, 
										precio_diesel, 
										precio_adblue 
									) VALUES ( 
										$usuario, 
										$id_uni, 
										'$ticket', 
										'$folio', 
										'$fecha', 
										'".hora_actual()."', 
										'$diesel', 
										'$kilometros', 
										$id_op, 
										'$adblue', 
										$precio_adblue, 
										$precio_diesel 
									)
								";
								try {
									$sql=$conn->prepare($sentencia);
									$res=$sql->execute();
									$contador++;
								} catch (PDOException $e) {
									//Almaceno el error en una variabLe
									$error=$e->getMessage();
									//Creo la sentencia que se va a escribir en el log de errores
									$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia]-[ARCHIVO: ".__FILE__."].";
									//Mando a escribir el mensaje
									escribir_log($mensaje);
									//Imprimo un mensaje
									echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
									//Detengo el procedimiento
									die();
								}
							}else{
								echo "
								<div class='alert alert-info'>
								<button class='close' data-dismiss='alert'>×</button>
								<strong>Ya existe una carga registrada con este folio y ticket ($folio, $ticket)</strong>
								</div>
								";
							}
						} catch (PDOException $e) {
							//Almaceno el error en una variabLe
							$error=$e->getMessage();
							//Creo la sentencia que se va a escribir en el log de errores
							$mensaje=referencia_horaria()."-[USUARIO:".campo_limpiado($_SESSION[UBI]['nombre'],0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],0,0)."]-[DETALLE DEL ERROR: $error]-[SENTENCIA: $sentencia]-[ARCHIVO: ".__FILE__."].";
							//Mando a escribir el mensaje
							escribir_log($mensaje);
							//Imprimo un mensaje
							echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (".referencia_horaria().")');</script>";
							//Detengo el procedimiento
							die();
						}
					}
				}
			}
			fclose($archivo);
			$total=$ultima_linea-1;
			echo "
				<script>
					alert('¡Procesamiento del archivo completado!, Se han cargado $contador registros de $total');
				</script>
			";
			echo "
				<div class='alert alert-success'>
					<button class='close' data-dismiss='alert'>×</button>
					<strong>¡Procesamiento del archivo completado!</strong>
					Se han cargado $contador registros de $total
				</div>
			";
		} else {
			echo "
				<script>
					alert('¡No se pudo abrir el archivo CSV!');
				</script>
			";
		}
	} else {
		echo "
			<script>
				alert('Por favor, selecciona un archivo CSV válido');
			</script>
		";
	}
?>
