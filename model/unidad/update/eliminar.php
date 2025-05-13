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
  $token=md5(uniqid(mt_rand(),true));
  //Verifico que los datos enviados no sean vacios
  if(isset($id_unidad)){
    //Se define la sentencia de revision a ejecutar
    $sentencia="SELECT *FROM unidades WHERE id=$id_unidad";
    //Trato de ejecutar la sentencia y sino arrojo el error
    try {
      $search=$conn->prepare($sentencia);
      $search->execute();
      $tabla=$search->fetch(PDO::FETCH_ASSOC);
      $dto=$tabla['visual'];
      if ($dto!=0) {
        $sentencia="
          UPDATE unidades set visual=0, f_baja='".ahora(1)."' where id=$id_unidad;
          INSERT INTO deshabilitacion (
            id_usuario,
            id_unidad,
            fecha_inicio,
            motivo,
            token,
            fecha_registro,
            hora_registro,
            visual,
            hora_inicio
          ) VALUES (
            $id_usr,
            $id_unidad,
            '".ahora(1)."',
            'BAJA DE LA UNIDAD',
            '$token',
            '".ahora(1)."',
            '".ahora(2)."',
            0,
            '".ahora(2)."'
          );
        ";
        try {
          $sql=$conn->prepare($sentencia);
          $res=$sql->execute();
          echo "
            <script>
              alert('UNIDAD ELIMINADA');
              habilitadas();
              $('#eliminar').modal('hide');
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
        echo "<script>alert('ESTA UNIDAD YA SE ENCUENTRA ELIMINADA');</script>";
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