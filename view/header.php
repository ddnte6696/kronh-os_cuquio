<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Cabeceras con redireccionamiento web
    echo "
      <!DOCTYPE html>
      <html lang='es'>
      <head>
        <meta charset='utf-8'>
        <title>".$_SESSION[$_SESSION['ubi']]['title']."</title>
        <link rel='icon' href='../img/logos/icon.png' type='image/ico' />
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css'>
        <link rel='stylesheet' href='css/select2.min.css'>
        <link rel='stylesheet' href='css/general.css'>
        <link rel='stylesheet' href='css/stiky-footer.css'>
        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous' >

        <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js'></script>
        
        <script src='js/select2.min.js'></script>
        <script src='js/funciones.js'></script>
      </head>
    ";
?>