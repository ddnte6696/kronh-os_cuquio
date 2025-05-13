<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
  //Obtengo el dato enviado por el formulario
  $puesto=campo_limpiado($_POST['puesto'],0,1);
  //Busco que no exista el puesto
  $sentencia="SELECT count(id) as exist FROM puestos WHERE puesto='$puesto'";
  //Trato de ejecutar la sentencia y sino arrojo el error
  try {
  	$search=$conn->prepare($sentencia);
  	$search->execute();
  	$tabla=$search->fetch(PDO::FETCH_ASSOC);
  	if ($tabla['exist']<1) {
  		$sentencia="INSERT INTO puestos (puesto) VALUES ('$puesto');";
  		try {
	  		$sql=$conn->prepare($sentencia);
	  		$res=$sql->execute();
				echo "
					<script>
						alert('¡AÑADIDO!, PUESTO REGISTRADO');
						tabla_puestos();
						$('#puesto').val();
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
			echo "<script>alert('¡ATENCION!, YA SE ENCUENTRA REGISTRADO ESTE PUESTO');</script>";
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