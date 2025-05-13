<?php
 //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
    $importe=campo_limpiado($_POST['importe'],0,0);
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
    $clave=$pre[3];
    $enviado=campo_limpiado("$id_unidad||$unidad||$fecha_trabajo||$clave",1,0);
  //Obtengo el ID de la liquidacion
    $id_liquidacion=busca_existencia("SELECT id AS exist FROM liq_op_rl where unidad=$id_unidad and operador='$clave' and fecha='$fecha_trabajo'");
  //Realizo la actualizacion de la liquidacion
    $sentencia="UPDATE liq_op_rl SET boletos_talonario=$importe where id=$id_liquidacion;";
  //Ejecuto la sentencia
    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
  //Evaluo el resulado
    if ($resultado === true) {
      //Escribo en la bitacora
        //registra_bitacora("SE AGREGA UN IMPORTE DE $importe EN LOS BOLETOS DE TAQUILLA MANUALES EN LA LIQUIDACION N° $id_liquidacion");
      //Recalculo los importes y la comision
        recalcular_liq_op_rl($id_liquidacion,TRUE);
      //Imprimo un mensaje de confirmacion y continuo con el proceso
        echo "
          <script>
            alert('IMPORTE DE BOLETOS FISICOS AGREGADO');
          </script>
        ";
      //
    }
?>