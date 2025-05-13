<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
  //Obtengo los dato enviado por el formulario
  $origen=campo_limpiado($_POST['origen'],0,1);
  $ruta=explode("||",campo_limpiado($_POST['ruta'],0,1));
  $identificador=$ruta[0];
  $nombre=$ruta[1];
  $hora=campo_limpiado($_POST['hora'],0,0);
  //Busco que no exista la corrida
  $sentencia="SELECT count(id) as exist FROM corridas WHERE identificador='$identificador' and origen='$origen' and hora='$hora'";
  //Trato de ejecutar la sentencia y sino arrojo el error
  try {
  	$search=$conn->prepare($sentencia);
  	$search->execute();
  	$tabla=$search->fetch(PDO::FETCH_ASSOC);
  	if ($tabla['exist']<1) {
  		$sentencia="INSERT INTO corridas (nombre,identificador,origen,hora) VALUES ('$nombre','$identificador','$origen','$hora');";
  		try {
	  		$sql=$conn->prepare($sentencia);
	  		$res=$sql->execute();
				echo "
					<script>
						alert('¡AÑADIDO!, CORRIDA REGISTRADA');
						
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
			echo "<script>alert('¡ATENCION!, YA SE ENCUENTRA REGISTRADA ESTA CORRIDA');</script>";
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
?>