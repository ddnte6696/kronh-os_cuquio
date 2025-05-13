<!--Administrador>>Control de venta-->
<script type="text/javascript">
  function pendientes_enviar(opcion){
    var url="model/venta/queries/pendientes_enviar.php"
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
  function pendientes_recibir(opcion){
    var url="model/venta/queries/pendientes_recibir.php"
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
  function pendientes_entregar(opcion){
    var url="model/venta/queries/pendientes_entregar.php"
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
  window.onload(pendientes_enviar());
</script>
<div class="card text-center">
  <div class="card-header">
    <h1>PAQUETERIA</h1>
  </div>
  <div class="card-body">
    <div>
      <a class="btn btn-sm btn-primary" onclick="pendientes_enviar();">
        PENDIENTES DE ENVAR
      </a>
      <a class="btn btn-sm btn-primary" onclick="pendientes_recibir();">
        PENDIENTES DE RECIBIR
      </a>
      <a class="btn btn-sm btn-primary" onclick="pendientes_entregar();">
        PENDIENTES DE ENTREGAR
      </a>
    </div>
    <div id="respuesta_control_paqueteria">
    </div>
  </div>
  <div class="card-footer">
    <div id="muestra"></div>
  </div>
</div>
<?php
  include_once A_MODEL.'venta/modal/asignar_corrida_paqueteria.php';
  include_once A_MODEL.'venta/modal/confirmar_llegada_paqueteria.php';
  include_once A_MODEL.'venta/modal/marcar_entrega_paqueteria.php';
?>