<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Incluyo el archivo de conexion
    include_once A_CONNECTION;
  //Obtengo el usuario actual
    $usuario=$_SESSION['kronh-os_cuquio']['id'];
  //Obtengo los datos del destino
    $id_destino=campo_limpiado($_POST['id_destino'],2,0);
    $nombre_destino=campo_limpiado($_POST['nombre_destino'],0,0);
  //Obtengo y separo los datos de la ruta
    $dato=explode("||", campo_limpiado($_POST['dato'],2,0));
    $nombre_ruta=$dato[0];
    $identificador=$dato[1];
    $origen=$dato[2];
  //Obtengo el id del destino
    $sentencia="SELECT id AS exist from destinos where destino='$origen'";
    $id_origen=busca_existencia($sentencia);
  //Obtengo los datos de los precios
    $precio_normal=campo_limpiado($_POST['precio_normal'],0,0);
    $precio_medio=campo_limpiado($_POST['precio_medio'],0,0);
  //Defino l sentencia para insertar el destuno en la ruta
    $sentencia="UPDATE rutas SET precio_normal='$precio_normal', precio_medio='$precio_medio' WHERE id_destino='$id_destino' and nombre='$nombre_ruta' and identificador='$identificador' and origen='$origen'; ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
  //Evaluo si se realizo la sentencia
    if ($devuelto=="realizado") {
      echo "
      <script>
        $('#editar_destino').modal('hide');
        tabla_destinos_asignados()
        tabla_destinos_disponibles()
      </script>";
    }
  //
?>