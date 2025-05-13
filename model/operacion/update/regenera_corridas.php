<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Defino la fecha
    $fecha=ahora(1);
  //Defino una variable para contar los registros realizados
    $contador_creadas=0;
    $contador_eliminadas=0;
  //Defino la sentencia para obtener todas las corridas activas
    $sentencia="SELECT * FROM servicio";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla_servicio) {
          //Creo una variable especial
            $id_servicio=$tabla_servicio['id'];
            $nombre_ruta=$tabla_servicio['nombre_ruta'];
            $identificador=$tabla_servicio['identificador'];
            $id_punto_inicial=$tabla_servicio['id_punto_inicial'];
            $punto_inicial=$tabla_servicio['punto_inicial'];
            $id_punto_origen=$tabla_servicio['id_punto_origen'];
            $punto_origen=$tabla_servicio['punto_origen'];
            $id_punto_final=$tabla_servicio['id_punto_final'];
            $punto_final=$tabla_servicio['punto_final'];
            $hora=$tabla_servicio['hora'];
            $estado=$tabla_servicio['estatus'];
          //Reviso su existencia en la tabla de servicios
            $sentencia="
              SELECT sum(id) as exist FROM corrida WHERE 
              identificador='$identificador' and 
              id_servicio=$id_servicio and 
              servicio='$nombre_ruta' and 
              fecha='$fecha' and 
              hora='$hora' and 
              id_punto_origen=$id_punto_origen and 
              punto_origen='$punto_origen';
            ";
            $existencia=busca_existencia($sentencia);
            if ($existencia<1) {
              //Verifico si el estado de la corrida es verdadero o falso
                if ($estado==True) {
                  //Defino la creacion del servicio
                    $sentencia="
                      INSERT INTO corrida (
                        identificador,
                        id_servicio,
                        servicio,
                        fecha,
                        hora,
                        id_punto_origen,
                        punto_origen,
                        estatus
                      ) VALUES (
                        '$identificador',
                        $id_servicio,
                        '$nombre_ruta',
                        '$fecha',
                        '$hora',
                        $id_punto_origen,
                        '$punto_origen',
                        1
                      );
                    ";
                  //Realizo la ejecucion de la sentencia
                    $devuelto=ejecuta_sentencia_sistema($sentencia,true);
                  //Si la insercion se realizo correctamente
                    if ($devuelto==true) { $contador_creadas++; }
                  //
                }
              //
            }else{
              if ($estado==False) {
                //Defino la creacion del servicio
                    $sentencia="DELETE FROM corrida where id=$existencia";
                  //Realizo la ejecucion de la sentencia
                    $devuelto=ejecuta_sentencia_sistema($sentencia,true);
                  //Si la insercion se realizo correctamente
                    if ($devuelto==true) { $contador_eliminadas++; }
                  //
              }
            }
          //
        }
      //
    }
  //
    echo "
      <script>
        alert('CORRIDAS CREADAS:$contador_creadas , CORRIDAS ELIMINADAS:$contador_eliminadas');
      </script>
    ";
  //
?>