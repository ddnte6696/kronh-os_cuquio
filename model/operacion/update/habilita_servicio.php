<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $usuario=$_SESSION['kronh-os']['id'];
  //Obtengo los datos del destino
    $id_corrida=campo_limpiado($_POST['id'],2,0);
  //Defino la sentencia
    $sentencia="UPDATE servicio SET estatus=true WHERE id=$id_corrida;";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,True);
  //Evaluo si se realizo la sentencia
    if ($devuelto=="realizado") {
      echo "
      <script>
        alert('SERVICIO HABILITADO');
      </script>";
    }
  //
?>