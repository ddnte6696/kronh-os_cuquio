<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_liquidacion=campo_limpiado($_POST['dato'],2,0);
  //Realizo la actualizacion de la liquidacion
    $sentencia="UPDATE liq_op_rl SET estado=2 where id=$id_liquidacion;";
  //Ejecuto la sentencia
    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
  //Evaluo el resulado
    if ($resultado === true) {
      //Escribo en la bitacora
        //registra_bitacora("SE CIERRA LA LIQUIDACION N° $id_liquidacion");
      //Imprimo un mensaje de confirmacion y continuo con el proceso
        echo "
          <script>
            alert('LIQUIDACION CERRADA');
            buscar_fecha_liquidacion();
          </script>
        ";
      //
    }
?>