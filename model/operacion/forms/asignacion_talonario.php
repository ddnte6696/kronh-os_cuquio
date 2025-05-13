<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
  //Obtengo el dato enviado por el formulario
    $asignacion = campo_limpiado($_POST['puntero'],2,0);
  //Reviso que tipo de asignacion es la quue se esta enviando
    switch ($asignacion) {
      case '1':
        //Asignacion para taquilla
          echo "
            <div class='row'>
              <div class='col-md-6'>
                <div class='input-group mb-3 input-group-sm'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>
                      <strong>TAQUILLA</strong>
                    </span>
                  </div>
                  <select name='taquilla' id='taquilla' class='custom-select' style='color:black' required=''>";
                    //Defino la sentencia a ejecutar
                      $sentencia="SELECT * FROM destinos where punto=true";
                    //Ejecuto la sentencia y almaceno lo obtenido en una variable
                      $resultado_sentencia=retorna_datos_sistema($sentencia);
                    //Identifico si el reultado no es vacio
                      if ($resultado_sentencia['rowCount'] > 0) {
                        //Almaceno los datos obtenidos
                          $resultado = $resultado_sentencia['data'];
                        // Recorrer los datos y llenar las filas
                          foreach ($resultado as $tabla) {
                            $id=campo_limpiado($tabla['id'],1,0);
                            $destino=$tabla['destino'];
                            echo "<option value='$id'>$destino</option>";
                          }
                        //
                      }
                    //
                    echo "
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='input-group mb-3 input-group-sm'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>
                      <strong>USUARIO</strong>
                    </span>
                  </div>
                  <select name='usuario' id='usuario' class='custom-select' style='color:black' required=''>
                    <option value='".campo_limpiado('*',1,0)."'>CUALQUIERA</option>";
                    //Defino la sentencia a ejecutar
                      $sentencia="SELECT * FROM usuarios where puesto=2 and visual=1";
                    //Ejecuto la sentencia y almaceno lo obtenido en una variable
                      $resultado_sentencia=retorna_datos_sistema($sentencia);
                    //Identifico si el reultado no es vacio
                      if ($resultado_sentencia['rowCount'] > 0) {
                        //Almaceno los datos obtenidos
                          $resultado = $resultado_sentencia['data'];
                        // Recorrer los datos y llenar las filas
                          foreach ($resultado as $tabla) {
                            $id=campo_limpiado($tabla['clave'],1,0);
                            $nombre=$tabla['nombre'];
                            $apellido=$tabla['apellido'];
                            echo "<option value='$id'>$nombre $apellido</option>";
                          }
                        //
                      }
                    //
                    echo "
                  </select>
                </div>
              </div>
            </div>
          ";
        //
      break;
      case '2':
        //Asignacion para operador
          echo "
            <div class='row'>
              <div class='col-md-12'>
                <div class='input-group mb-3 input-group-sm'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>
                      <strong>OPERADOR</strong>
                    </span>
                  </div>
                  <select name='operador' id='operador' class='custom-select' style='color:black' required=''>
                    <option value='".campo_limpiado('',1,0)."'>SELECCIONA UN OPERADOR</option>";    
                    //Defino la sentencia a ejecutar
                      $sentencia="SELECT * FROM operadores where (division=3 or division=1) and visual=1";
                    //Ejecuto la sentencia y almaceno lo obtenido en una variable
                      $resultado_sentencia=retorna_datos_sistema($sentencia);
                    //Identifico si el reultado no es vacio
                      if ($resultado_sentencia['rowCount'] > 0) {
                        //Almaceno los datos obtenidos
                          $resultado = $resultado_sentencia['data'];
                        // Recorrer los datos y llenar las filas
                          foreach ($resultado as $tabla) {
                            $id=campo_limpiado($tabla['clave'],1,0);
                            $nombre=$tabla['nombre'];
                            $apellido=$tabla['apellido'];
                            $clave=$tabla['clave'];
                            echo "<option value='$id'>$clave $nombre $apellido</option>";
                          }
                        //
                      }
                    //
                    echo "
                  </select>
                </div>
              </div>
            </div>
          ";
        //
      break;
    }
  //
?>