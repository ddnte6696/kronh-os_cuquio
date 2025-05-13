<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $usuario=$_SESSION['kronh-os']['id'];
  //Obtengo los datos del destino
    $id_destino=campo_limpiado($_POST['id_destino'],2,0);
    $destino=campo_limpiado($_POST['nombre_destino'],0,0);
  //Obtengo y separo los datos de la ruta
    $dato=explode("||", campo_limpiado($_POST['dato'],2,0));
    $nombre_ruta=$dato[0];
    $identificador=$dato[1];
    $punto_origen=$dato[2];
  //Obtengo el id del destino
    $sentencia="SELECT id AS exist from destinos where destino='$punto_origen'";
    $id_punto_origen=busca_existencia($sentencia);
  //Obtengo datos de la ruta
   $sentencia="
      SELECT DISTINCT id_punto_inicial ,punto_inicial ,id_punto_final ,punto_final FROM ruta where nombre_ruta='$nombre_ruta' and identificador='$identificador' and punto_origen='$punto_origen' ;
    ";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      //Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Creeo las variables del identificador
            $id_punto_inicial=$tabla['id_punto_inicial'];
            $punto_inicial=$tabla['punto_inicial'];
            $id_punto_final=$tabla['id_punto_final'];
            $punto_final=$tabla['punto_final'];
          //
        }
      //
    }
  //Obtengo los datos de los precios
    $precio_normal=campo_limpiado($_POST['precio_normal']);
    $precio_medio=campo_limpiado($_POST['precio_medio']);
  //Defino l sentencia para insertar el destuno en la ruta
    $sentencia="
      INSERT INTO ruta (
        nombre_ruta,
        identificador,
        id_punto_inicial,
        punto_inicial,
        id_punto_origen,
        punto_origen,
        id_punto_final,
        punto_final,
        id_destino,
        destino,
        precio_normal,
        precio_medio
      ) values (
        '$nombre_ruta',
        '$identificador',
        '$id_punto_inicial',
        '$punto_inicial',
        '$id_punto_origen',
        '$punto_origen',
        '$id_punto_final',
        '$punto_final',
        '$id_destino',
        '$destino',
        '$precio_normal',
        '$precio_medio'
      );
    ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,True);
  //Evaluo si se realizo la sentencia
    if ($devuelto==True) {
      echo "
      <script>
        $('#agregar_destino').modal('hide');
        tabla_destinos_asignados()
        tabla_destinos_disponibles()
      </script>";
    }
  //
?>