<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<script>
  //Genera la almacen para la fecha y taquilla especificada
    function buscar_fecha_almacen(){
      var fecha=$("#fecha").val();
      var url="model/reporte/queries/almacen_fecha.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{ fecha:fecha },
        beforeSend: function(){
          $("#respuesta_almacen_fecha").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_almacen_fecha').html(datos);
        }
      });
    }
  //
</script>

<div class="card">
  <div class="card-header"><h1>REPORTE DE ALMACEN</h1></div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-8">
        <div class="input-group mb-3 input-group-sm">
          <div class="input-group-prepend">
            <span class="input-group-text"><strong>FECHA DE TRABAJO</strong></span>
          </div>
          <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo ahora(1) ?>" max="<?php echo ahora(1) ?>" required="">
        </div>
      </div>
      <div class="col-lg-4">
        <a onclick="buscar_fecha_almacen();" class="btn btn-sm btn-block btn-primary text-light" title="Muertra todos los cortes en la fecha y taquilla especificados">
          <strong>VER REPORTE</strong>
        </a>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <div id="respuesta_almacen_fecha"></div>
  </div>
</div>