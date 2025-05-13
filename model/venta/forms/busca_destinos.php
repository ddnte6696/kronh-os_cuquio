<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
?>
<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Recibo y le doy formato a lo enviado por el formulario
  $dat_origen=explode("||",campo_limpiado($_POST['puntero'],2,0));
  $id_origen=$dat_origen[0];
  $origen=$dat_origen[1];
?>
<div class="input-group mb-3 input-group-sm">
  <div class="input-group-prepend">
    <span class="input-group-text"><strong>DESTINO</strong></span>
  </div>
  <select  name='destino' id='destino' class="custom-select" required="">
    <?php
      //Defino la sentencia a ejecutar
        $sentencia="SELECT DISTINCT id_destino, destino,precio_normal,precio_medio FROM ruta where punto_origen='$origen' group by destino order by id_destino";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
              //Creo Variables de concatenacion
                $destino=$tabla['destino'];
                $id_destino=$tabla['id_destino'];
                $destino=$tabla['destino'];
                $precio_normal=$tabla['precio_normal'];
                $precio_medio=$tabla['precio_medio'];
              //Creeo un dato especial del destino
                $dato=campo_limpiado(("$id_destino||$destino|| $precio_normal"),1,0);
              //Imprimo el campo
                echo "<option value='$dato'>$destino ($ $precio_normal - $ $precio_medio)</option>";
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
        $('#destino').select2();
      });
    });
  //
</script>