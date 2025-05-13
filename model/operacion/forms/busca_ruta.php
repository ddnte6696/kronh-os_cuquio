<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el dato enviado
  $origen=campo_limpiado($_POST['puntero'],2,0);
?>
<div class="input-group mb-3 input-group-sm">
  <div class="input-group-prepend">
    <span class="input-group-text"><strong>RUTA</strong></span>
  </div>
  <select  name='ruta' id='ruta' class="custom-select" required="">
    <?php
      //Defino la sentencia a ejecutar
        $sentencia="SELECT * FROM ruta where punto_origen='$origen' group by punto_origen,nombre_ruta";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
              //Creo una variable especial
                $nombre_ruta=$tabla['nombre_ruta'];
                $identificador=$tabla['identificador'];
                $id_punto_inicial=$tabla['id_punto_inicial'];
                $punto_inicial=$tabla['punto_inicial'];
                $id_punto_origen=$tabla['id_punto_origen'];
                $punto_origen=$tabla['punto_origen'];
                $id_punto_final=$tabla['id_punto_final'];
                $punto_final=$tabla['punto_final'];
              //Creeo un dato especial del destino
                $dato=campo_limpiado(("$nombre_ruta||$identificador||$id_punto_inicial||$punto_inicial||$id_punto_origen||$punto_origen||$id_punto_final||$punto_final"),1,0);
              //Imprimo el campo
                echo "<option value='$dato'>$nombre_ruta</option>";
              //
            }
          //
        }
      //
    ?>
  </select>
</div>
<script>
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#ruta').select2();
      });
    });
  //
</script>