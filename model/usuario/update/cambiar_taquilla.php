<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //recupero tanto el usuario como la contareña
    $taquilla=campo_limpiado($_POST['taquilla'],0,0);
    $_SESSION[UBI]['taquilla']=$taquilla;
    echo "<script>location.reload();</script>";
  //
?>