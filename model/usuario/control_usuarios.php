<!--Administrador>>Control de usuario-->
<script type="text/javascript">
  function registrar_usuario(opcion){
    var url="model/usuario/forms/usuario.php"
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
    var url="model/usuario/queries/habilitados.php"
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
    var url="model/usuario/queries/inhabilitados.php"
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
  window.onload(registrar_usuario());
</script>
<div class="card text-center">
  <div class="card-header">
    <h1>CONTROL DE USUARIOS</h1>
  </div>
  <div class="card-body">
    <div>
      <a class="btn btn-primary" onclick="registrar_usuario();">REGISTRO</a>
      <a class="btn btn-success" onclick="habilitados();">ACTIVOS</a>
      <a class="btn btn-danger" onclick="inhabilitados();">ELIMINADOS</a>
    </div>
    <div id="respuesta_control_usuario">
    </div>
  </div>
  <div class="card-footer">
    <div id="muestra"></div>
  </div>
</div>