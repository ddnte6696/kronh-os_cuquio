<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $usuario=$_SESSION['kronh-os']['id'];
  //OObtengo el dato enviado y lo separo
    $dato=explode("||", campo_limpiado($_POST['dato'],2,0));
  //Almaceno cada dato por separado en una variable
    $id_destino=$dato[0];
    $identificador=$dato[1];
    $origen=$dato[2];
    
  //Defino l sentencia para insertar el destuno en la ruta
    $sentencia="DELETE FROM ruta where id_destino='$id_destino' and identificador='$identificador' and punto_origen='$origen';
  ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
  //Evaluo si se realizo la sentencia
    if ($devuelto=="realizado") {
      echo "
      <script>
        tabla_destinos_asignados()
        tabla_destinos_disponibles()
      </script>";
    }
  //
?>