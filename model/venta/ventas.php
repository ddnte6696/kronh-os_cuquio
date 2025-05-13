<!--Administrador>>Control de venta-->
<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
?>
<script type="text/javascript">
  function venta_boletos(opcion){
    var url="model/venta/forms/boleto.php"
    $.ajax({
      type: "POST",
      url:url,
      beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
        },
      success: function(datos){
        $('#muestra').html(datos);
      }
    });
  }
  function venta_paqueteria(opcion){
    var url="model/venta/forms/paqueteria.php"
    $.ajax({
      type: "POST",
      url:url,
      beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
        },
      success: function(datos){
        $('#muestra').html(datos);
      }
    });
  }
  function ver_corridas(opcion){
    var url="model/venta/forms/corridas.php"
    $.ajax({
      type: "POST",
      url:url,
      beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
        },
      success: function(datos){
        $('#muestra').html(datos);
      }
    });
  }
  function corte(opcion){
    var url="model/venta/forms/corte.php"
    $.ajax({
      type: "POST",
      url:url,
      beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
        },
      success: function(datos){
        $('#muestra').html(datos);
      }
    });
  }
  window.onload(venta_boletos());
</script>
<div class="card text-center">
  <div class="card-header">
    <h1>MODULOS DE VENTA Y OPERACION</h1>
  </div>
  <div class="card-body">
    <div>
      <a class="btn btn-sm btn-primary" onclick="venta_boletos();">BOLETOS</a>
      <a class="btn btn-sm btn-primary" onclick="venta_paqueteria();">PAQUETERIA</a>
      <a class="btn btn-sm btn-primary" onclick="ver_corridas();">CORRIDAS</a>
      <a class="btn btn-sm btn-primary" onclick="corte();">CORTE</a>
    </div>
    <div id="respuesta_control_venta">
    </div>
  </div>
  <div class="card-footer">
    <div id="muestra"></div>
  </div>
</div>
