<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2);
  //Obtengo los datos del destino
    $id_boleto=campo_limpiado($_POST['id_boleto'],2);
    $motivo=campo_limpiado($_POST['motivo'],0,1);
  //Reviso el estado de los comentarios
    if ($motivo=='') {
      $motivo='NO SE DIO MOTIVO DE LA CANCELACION';
    }else{
      $motivo="MOTIVO DE LA CANCELACION: $motivo";
    }
  //Defino la sentencia
    $sentencia="
      UPDATE 
        boleto 
      SET 
        estado=3, 
        motivo='$motivo', 
        usuario_cancela='$clave', 
        fecha_cancela='".ahora(1)."' 
      WHERE 
        id=$id_boleto
      ;
    ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,True);
  //Evaluo si se realizo la sentencia
    if ($devuelto==True) {
      echo "
      <script>
        alert('BOLETO CANCELADO, RECARGA LA TABLA PARA VISUALIZAR LOS DATOS');
        $('#cancelar_boleto').modal('hide');
      </script>";
    }
  //
?>