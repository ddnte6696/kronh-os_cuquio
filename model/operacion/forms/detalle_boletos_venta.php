<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo datos de la sesion
    $taquilla_actual=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo el id de la taquilla
    $sentencia="SELECT id as exist FROM destinos WHERE destino='$taquilla_actual';";
    $id_taquilla_actual=busca_existencia($sentencia);
    $dato_actual=campo_limpiado($taquilla_actual,1,0);
  //
?>
<div class="card text-center">
  <div class="card-header"><h3>DETALLE DE BOLETOS</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_boletos" id="frm_boletos">
      <div class="row text-center">

        <div class="col-md-3">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>FILTRO</strong></span>
            </div>
            <select  name='filtro' id="filtro" class="custom-select"  style="color:black" required="">
              <?php
                //Defino algunos valores por defecto
                  echo "<option value='".campo_limpiado('f_venta',1,0)."'>FECHA DE VENTA</option>";
                  echo "<option value='".campo_limpiado('f_corrida',1,0)."'>FECHA DE VIAJE</option>";
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
                echo "<option value='$dato_actual'>$taquilla_actual</option>";
                //Defino la sentencia a ejecutar
                  $sentencia="SELECT * FROM destinos where punto=true and id<>$id_taquilla_actual";
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
    <input type="submit" value="VER BOLETOS" class="btn btn-sm btn-primary btn-block" onclick="busca_boletos();">
  </div>
  <div class="card-footer"><div id="respuesta_boletos"></div></div>
</div>
<script>
  function busca_boletos(){
    $.ajax({
      type: "POST",
      url: "model/operacion/queries/detalle_boletos_venta.php",
      data: $("#frm_boletos").serialize(),
      beforeSend: function(){$("#respuesta_boletos").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_boletos").html(data);},
    });
    return false;
  }
</script>