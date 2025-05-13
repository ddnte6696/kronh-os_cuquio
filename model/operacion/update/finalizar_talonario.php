<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2);
  //Obtengo los datos del destino
    $id=campo_limpiado($_POST['id_talonario_finalizar'],2);
  //Defino la sentencia
    $sentencia="
      UPDATE 
        talonario 
      SET 
        restantes=0, 
        actual=final
      WHERE 
        id=$id
      ;
    ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,True);
  //Evaluo si se realizo la sentencia
    if ($devuelto==True) {
      echo "
      <script>
        alert('TALONARIO FINALIZADO, RECARGA LA TABLA PARA VISUALIZAR LOS CAMBIOS');
        $('#finalizar_talonario').modal('hide');
      </script>";
    }
  //
?>