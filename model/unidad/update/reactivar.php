<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluyen los archivos de conexión
  include_once A_CONNECTION;
  //Obtengo el ID del unidad activo
  $id_usr=campo_limpiado($_SESSION[UBI]['id'],2,0);
  //Recuperacion de los datos obtenidos del formulario
  $id_unidad=campo_limpiado($_POST['id'],2,0);
  $fecha_fin=campo_limpiado($_POST['fecha'],0,0);
  $h_fin=campo_limpiado($_POST['hora'],0,0);
  //Verifico que los datos enviados no sean vacios
  if(isset($id_unidad) || isset($fecha_fin) || isset($h_fin)){
    //Se define la sentencia de revision a ejecutar
    $sentencia="SELECT * FROM deshabilitacion where id_unidad=$id_unidad and fecha_fin is NULL;";
    //Trato de ejecutar la sentencia y sino arrojo el error
    try {
      $p1s=$conn->prepare($sentencia);
      $p1s->execute();
      $p1=$p1s->fetch(PDO::FETCH_ASSOC);
      $id=$p1['id'];
      $dto=$p1['visual'];
      if ($dto==0) {
        $sentencia="
          UPDATE deshabilitacion set fecha_fin='$fecha_fin', hora_fin='$h_fin' where id=$id;
          UPDATE unidades set visual=1 where id=$id_unidad;
        ";
        try {
          $sql=$conn->prepare($sentencia);
          $res=$sql->execute();
          echo "
            <script>
              alert('UNIDAD HABILITADA');
              eliminadas();
              $('#reactivar').modal('hide');
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
        echo "<script>alert('ESTA UNIDAD YA SE ENCUENTRA HABILITADA');</script>";
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
  }else{
    echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
  }
?>