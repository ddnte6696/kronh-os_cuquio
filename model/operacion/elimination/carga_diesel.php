<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo los datos enviados por el formulario
    $id=campo_limpiado($_POST['id'],2,0);
    $motivo=campo_limpiado($_POST['motivo'],0,1);
  //Defino la sentencia para actualizar el registro
    $sentencia="UPDATE cargas_diesel set estado=false, motivo='$motivo' where id=$id;";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
  //Evaluo si se realizo la sentencia
    if ($devuelto=="realizado") {
      registra_bitacora("ELIMINA LA CARGA DE DIESEL N° $id ($motivo)");
      echo "
        <script>
          alert('CARGA DE DIESEL ELIMINADA');
          busqueda_diesel();
        </script>
      ";
    }
?>