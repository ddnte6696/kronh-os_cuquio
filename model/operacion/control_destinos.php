<!--Administrador>>Control de destino-->
<script type="text/javascript">
  //Funcion para registrar un destino
    function registrar_destino(opcion){
      var url="model/operacion/forms/destino.php"
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
  //Funcion para consultar los destinos registrados
    function destinos(opcion){
      var url="model/operacion/queries/destinos.php"
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
  //Funcion para registrar un ruta
    function registrar_ruta(opcion){
      var url="model/operacion/forms/ruta.php"
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
  //Funcion para consultar los rutas registrados
    function rutas(opcion){
      var url="model/operacion/queries/rutas.php"
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
  //Funcion para registrar un servicio
    function registrar_servicio(opcion){
      var url="model/operacion/forms/servicio.php"
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
  //Funcion para consultar los servicios registrados
    function servicios(opcion){
      var url="model/operacion/queries/servicios.php"
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
  //Funcion para abrir el formulario de registro de destinos
    window.onload(registrar_destino());
  //
</script>
<div class="card text-center">
  <div class="card-header">
    <h3>CONTROL DE DESTINOS, RUTAS Y SERVICIOS</h3>
  </div>
  <div class="card-body">
    <div>
      <a class="btn btn-sm btn-primary" onclick="registrar_destino();">REGISTRAR DESTINO</a>
      <a class="btn btn-sm btn-info" onclick="destinos();">DESTINOS REGISTRADOS</a>
      <a class="btn btn-sm btn-primary" onclick="registrar_ruta();">CREAR RUTA</a>
      <a class="btn btn-sm btn-info" onclick="rutas();">RUTAS CREADAS</a>
      <a class="btn btn-sm btn-primary" onclick="registrar_servicio();">CREAR SERVICIO</a>
      <a class="btn btn-sm btn-info" onclick="servicios();">SERVICIOS CREADOS</a>
    </div>
    <div id="respuesta_control_destino">
    </div>
  </div>
  <div class="card-body"><div id="muestra"></div></div>
  <div class="card-footer"><div id="expediente"></div></div>
</div>