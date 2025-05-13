<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<script>
  //Funcion para cargar la pagina de liquidacion
    function liquidacion(dato){
      var url="model/liquidacion/forms/paginas_op_rl.php"
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
  //Funcion para cargar el formulario de liquidacion especifico
    function buscar_fecha_liquidacion(){
      var fecha=$("#fecha").val();
      var url="model/liquidacion/queries/liquidaciones_rl_fecha.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{fecha:fecha},
        beforeSend: function(){
          $("#respuesta_buscar_fecha_liquidacion").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_buscar_fecha_liquidacion').html(datos);
        }
      });
    }
  //Funcion para cargar el formulario de liquidacion especifico
    function generar_fecha_liquidacion(){
      var fecha=$("#fecha").val();
      var url="model/liquidacion/insert/generar_liquidacion_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{fecha:fecha},
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
  <div class="card-header"><h5>REGISTRO DE LIQUIDACION DE OPERADOR DE RUTA LARGA</h5></div>
  <div class="card-body">

    <div class="input-group mb-3 input-group-sm">
      <div class="input-group-prepend">
        <span class="input-group-text"><strong>FECHA DE TRABAJO</strong></span>
      </div>
      <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo ahora(1) ?>" max="<?php echo ahora(1) ?>" required="">
    </div>
    <div class="row">
      <div class="col-md-6">
        <a onclick="generar_fecha_liquidacion();" class="btn btn-sm btn-block btn-success text-light">GENERAR  LIQUIDACIONES</a>
      </div>
      <div class="col-md-6">
        <a onclick="buscar_fecha_liquidacion();" class="btn btn-sm btn-block btn-primary text-light">BUSCAR LIQUIDACIONES</a>
      </div>
    </div>

  </div>
  <div class="card-footer">
    <div id="respuesta_buscar_fecha_liquidacion"></div>
  </div>
</div>
<script type="text/javascript">
</script>