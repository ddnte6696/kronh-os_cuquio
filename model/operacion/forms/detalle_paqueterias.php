<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card text-center">
  <div class="card-header"><h3>DETALLE DE PAQUETERIAS</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_paqueterias" id="frm_paqueterias">
      <div class="row text-center">

        <div class="col-md-3">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>FILTRO</strong></span>
            </div>
            <select  name='filtro' id="filtro" class="custom-select"  style="color:black" required="">
              <?php
                //Defino algunos valores por defecto
                  echo "<option value='".campo_limpiado('fecha',1,0)."'>FECHA DE VENTA</option>";
                  echo "<option value='".campo_limpiado('fecha_envio',1,0)."'>FECHA DE ENVIO</option>";
                  echo "<option value='".campo_limpiado('fecha_recepcion',1,0)."'>FECHA DE RECEPCION</option>";
                  echo "<option value='".campo_limpiado('fecha_entrega',1,0)."'>FECHA DE ENTREGA</option>";
                  echo "<option value='".campo_limpiado('fecha_liquidacion',1,0)."'>FECHA DE LIQUIDACION</option>";
                //
              ?>
            </select>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>ORIGEN</strong></span>
            </div>
            <select  name='origen' id="origen" class="custom-select"  style="color:black" required="">
              <?php
                //Defino algunos valores por defecto
                  echo "<option value='".campo_limpiado('TODAS',1,0)."'>TODAS LAS TAQUILLAS</option>";
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
                        //Creo una variable especial
                          $id_origen=$tabla['id'];
                          $origen=$tabla['destino'];
                        //Creeo un dato especial del destino
                          $dato=campo_limpiado($origen,1,0);
                        //Imprimo el campo
                          echo "<option value='$dato'>$origen</option>";
                        //
                      }
                    //
                  }
                //
              ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE INICIO</strong></span>
            </div>
            <input type='date' name='fecha_inicio' name='fecha_inicio' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
          </div>
        </div>

        <div class="col-md-3">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE FIN</strong></span>
            </div>
            <input type='date' name='fecha_fin' name='fecha_fin' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
          </div>
        </div>

      </div>
    </form>
    <input type="submit" value="VER PAQUETERIAS" class="btn btn-sm btn-primary btn-block" onclick="busca_paqueterias( );">
  </div>
  <div class="card-footer"><div id="respuesta_paqueterias"></div></div>
</div>
<?php
  include_once A_MODEL.'operacion/modal/retorna_estado_paqueteria.php';
  include_once A_MODEL.'operacion/modal/cambia_corrida.php';
?>
<script>
  function busca_paqueterias(){
    $.ajax({
      type: "POST",
      url: "model/operacion/queries/detalle_paqueterias.php",
      data: $("#frm_paqueterias").serialize(),
      beforeSend: function(){$("#respuesta_paqueterias").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_paqueterias").html(data);},
    });
    return false;
  }
</script>