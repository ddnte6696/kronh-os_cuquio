<!--Administrador>>Control de talonarios-->
<script type="text/javascript">
  //Funcion para registrar un talonarios
    function registrar_talonario(opcion){
      var url="model/operacion/forms/talonario.php"
      $.ajax({
        type: "POST",
        url:url,
        beforeSend: function(){
            $("#muestra").html("<div class='spinner-border'></div>");
            $("#expediente").html("");
          },
        success: function(datos){
          $('#muestra').html(datos);
        }
      });
    }
  //Funcion para registrar un talonarios
    function talonarios_activos(opcion){
      var url="model/operacion/queries/talonarios_activos.php"
      $.ajax({
        type: "POST",
        url:url,
        beforeSend: function(){
            $("#muestra").html("<div class='spinner-border'></div>");
            $("#expediente").html("");
          },
        success: function(datos){
          $('#muestra').html(datos);
        }
      });
    }
  //Funcion para registrar un talonarios
    function talonarios_finalizados(opcion){
      var url="model/operacion/queries/talonarios_finalizados.php"
      $.ajax({
        type: "POST",
        url:url,
        beforeSend: function(){
            $("#muestra").html("<div class='spinner-border'></div>");
            $("#expediente").html("");
          },
        success: function(datos){
          $('#muestra').html(datos);
        }
      });
    }
  //Funcion para abrir el formulario de registro de talonarioss
    window.onload(registrar_talonario());
  //
</script>
<div class="card text-center">
  <div class="card-header">
    <h3>CONTROL DE TALONARIOS</h3>
  </div>
  <div class="card-body">
    <div>
      <a class="btn btn-sm btn-primary" onclick="registrar_talonario();">REGISTRAR TALONARIO</a>
      <a class="btn btn-sm btn-info" onclick="talonarios_activos();">TALONARIOS ACTIVOS</a>
      <a class="btn btn-sm btn-info" onclick="talonarios_finalizados();">TALONARIOS FINALIZADOS</a>
    </div>
    <div id="respuesta_control_talonarios">
    </div>
  </div>
  <div class="card-body"><div id="muestra"></div></div>
  <div class="card-footer"><div id="expediente"></div></div>
</div>