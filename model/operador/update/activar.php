<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluyen los archivos de conexión
  include_once A_CONNECTION;
  //Obtengo el id del usuario a dar de baja
  $id=campo_limpiado($_POST['form_act'],2,0);
  //Se define la sentencia de revision a ejecutar
  $sentencia="UPDATE operadores SET visual=1 WHERE id=$id;";
  //Trato de ejecutar la sentencia y sino arrojo el error
  try {
    $sql=$conn->prepare($sentencia);
    $res=$sql->execute();
    echo "
      <script>
        alert('OPERADOR REACTIVADO');
        inhabilitados();
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
?>