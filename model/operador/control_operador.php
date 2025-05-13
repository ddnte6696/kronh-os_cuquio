<!--Administrador>>Control de operador-->
<script type="text/javascript">
  function registrar_operador(opcion){
    var url="model/operador/forms/operador.php"
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
  function habilitados(opcion){
    var url="model/operador/queries/habilitados.php"
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
  function inhabilitados(opcion){
    var url="model/operador/queries/inhabilitados.php"
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
  window.onload(registrar_operador());
</script>
<div class="card text-center">
  <div class="card-body">
    <div>
      <a class="btn btn-sm btn-info" onclick="registrar_operador();">REGISTRO</a>
      <a class="btn btn-sm btn-info" onclick="habilitados();">ACTIVOS</a>
      <a class="btn btn-sm btn-info" onclick="inhabilitados();">INHABILITADOS</a>
    </div>
    <div id="respuesta_control_operador">
    </div>
  </div>
  <div class="card-footer">
    <div id="muestra"></div>
  </div>
</div>
<?php include_once A_MODEL.'operador/modal/baja_operador.php'; ?>