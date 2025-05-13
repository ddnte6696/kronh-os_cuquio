<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2);
  //Obtengo los datos del destino
    $id_carga=campo_limpiado($_POST['id_carga'],2);
  //Defino la sentencia
    $sentencia="DELETE FROM cargas_diesel WHERE id=$id_carga
      ;
    ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,True);
  //Evaluo si se realizo la sentencia
    if ($devuelto==True) {
      echo "
      <script>
        alert('CARGA ELIMINADA, RECARGA LA TABLA PARA VISUALIZAR LOS DATOS');
        $('#eliminar_carga_diesel').modal('hide');
      </script>";
    }
  //
?>