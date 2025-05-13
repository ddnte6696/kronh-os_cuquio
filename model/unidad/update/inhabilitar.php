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
  $motivo=campo_limpiado($_POST['motivo'],0,1);
  $f_inicio=campo_limpiado($_POST['fecha'],0,0);
  $h_inicio=campo_limpiado($_POST['hora'],0,0);
  $token=md5(uniqid(mt_rand(),true));
  //Verifico que los datos enviados no sean vacios
  if(isset($id_unidad) || isset($motivo) || isset($f_inicio) || isset($h_inicio)){
    //Se define la sentencia de revision a ejecutar
    $sentencia="SELECT visual,numero FROM unidades WHERE id=$id_unidad";
    //Trato de ejecutar la sentencia y sino arrojo el error
    try {
      $search=$conn->prepare($sentencia);
      $search->execute();
      $tabla=$search->fetch(PDO::FETCH_ASSOC);
      $dto=$tabla['visual'];
      $numero=$tabla['numero'];
      if ($dto!=2) {
        $sentencia="
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
            '$f_inicio',
            '$motivo',
            '$token',
            '".ahora(1)."',
            '".ahora(2)."',
            1,
            '$h_inicio'
          );
          UPDATE unidades set visual=2 where id=$id_unidad;
        ";
        try {
          $sql=$conn->prepare($sentencia);
          $res=$sql->execute();
          echo "
            <script>
              alert('UNIDAD INHABILITADA');
              habilitadas();
              $('#inhabilitar').modal('hide');
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
        echo "<script>alert('ESTA UNIDAD YA SE ENCUENTRA INHABILITADA');</script>";
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