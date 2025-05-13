<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<script>
  //Funcion para cargar las paginas de liquidacion
    function liquidacion(dato){
      var url="model/liquidacion/forms/paginas_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Muertra la tabla de las liquidaciones en la fecha y taquilla especificas
    function buscar_fecha_liquidacion(){
      var fecha=$("#fecha").val();
      var dto_taquilla=$("#taquilla").val();
      var url="model/liquidacion/queries/liquidaciones_taq_rl_fecha.php"
      $.ajax({
        type: "POST",
        url:url,
         data:{
          fecha:fecha,
          dto_taquilla:dto_taquilla
        },
        beforeSend: function(){
          $("#respuesta_buscar_fecha_liquidacion").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_buscar_fecha_liquidacion').html(datos);
        }
      });
    }
  //Genera la liquidacion para la fecha y taquilla especificada
    function generar_fecha_liquidacion(){
      var fecha=$("#fecha").val();
      var vendedor=$("#vendedor").val();
      var dto_taquilla=$("#taquilla").val();
      var url="model/liquidacion/insert/generar_liquidacion_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          fecha:fecha,
          vendedor:vendedor,
          dto_taquilla:dto_taquilla
        },
        beforeSend: function(){
          $("#respuesta_buscar_fecha_liquidacion").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_buscar_fecha_liquidacion').html(datos);
        }
      });
    }
  //Recalcula todos los cortes registrados en la fecha especificada
    function recalcular_fecha_liquidacion(){
      var fecha=$("#fecha").val();
      var url="model/liquidacion/update/recalcular_liquidacion_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          fecha:fecha
        },
        beforeSend: function(){
          $("#respuesta_buscar_fecha_liquidacion").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_buscar_fecha_liquidacion').html(datos);
        }
      });
    }
</script>

<div class="card">
  <div class="card-header"><h5>REGISTRO DE LIQUIDACION DE TAQUILLA DE RUTA LARGA</h5></div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-4">
        <div class="input-group mb-3 input-group-sm">
          <div class="input-group-prepend">
            <span class="input-group-text"><strong>FECHA DE TRABAJO</strong></span>
          </div>
          <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo ahora(1) ?>" max="<?php echo ahora(1) ?>" required="">
        </div>
      </div> 
      <div class="col-lg-4">
        <div class="input-group mb-3 input-group-sm">
          <div class="input-group-prepend">
            <span class="input-group-text"><strong>VENDEDOR</strong></span>
          </div>
          <select  name='vendedor' id='vendedor' class="custom-select text-body" required="">
            <option value=''>SELECCIONA UN VENDEDOR</option>
            <?php
              //Defino la sentencia a ejecutar
                $sentencia="SELECT * FROM usuarios where visual=1 and permisos like '%12%'";
              //Ejecuto la sentencia y almaceno lo obtenido en una variable
                $resultado_sentencia=retorna_datos_sistema($sentencia);
              //Identifico si el reultado no es vacio
                if ($resultado_sentencia['rowCount'] > 0) {
                  //Almaceno los datos obtenidos
                    $resultado = $resultado_sentencia['data'];
                  // Recorrer los datos y llenar las filas
                    foreach ($resultado as $tabla) {
                      //Creo una variable especial
                        $id=$tabla['clave'];
                        $nombre=$tabla['nombre']." ".$tabla['apellido'];
                      //Creeo un dato especial del destino
                        $dato=campo_limpiado($id,1,0);
                      //Imprimo el campo
                        echo "<option value='$dato'>$nombre</option>";
                      //
                    }
                  //
                }
              //
            ?>
          </select>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="input-group mb-3 input-group-sm">
          <div class="input-group-prepend">
              <span class="input-group-text"><strong>TAQUILLA</strong></span>
          </div>
          <select  name="taquilla" id="taquilla" class="custom-select" required="">
            <?php
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
                        $dato=campo_limpiado(("$id_origen||$origen"),1,0);
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
      <div class="col-lg-4">
        <a onclick="recalcular_fecha_liquidacion();" class="btn btn-sm btn-block btn-dark text-light" title="Recalcula todos los cortes registrados en la fecha especificada">
          <strong>RECALCULAR TODAS LAS LIQUIDACIONES</strong>
        </a>
      </div>
      <div class="col-lg-4">
        <a onclick="generar_fecha_liquidacion();" class="btn btn-sm btn-block btn-success text-light" title="Genera la liquidacion para el vendedor, fecha y taquilla especificada">
          <strong>GENERAR  LIQUIDACION</strong>
        </a>
      </div>
      <div class="col-lg-4">
        <a onclick="buscar_fecha_liquidacion();" class="btn btn-sm btn-block btn-primary text-light" title="Muertra todos los cortes en la fecha y taquilla especificados">
          <strong>BUSCAR LIQUIDACIONES</strong>
        </a>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <div id="respuesta_buscar_fecha_liquidacion"></div>
  </div>
</div>
<script type="text/javascript">
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#vendedor').select2();
        $('#taquilla').select2();
      });
    });
  //
</script>