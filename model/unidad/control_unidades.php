<!--Administrador>>Control de unidad-->
<script type="text/javascript">
  function registrar_unidad(opcion){
    var url="model/unidad/forms/unidad.php"
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
  function habilitadas(opcion){
    var url="model/unidad/queries/habilitadas.php"
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
  function inhabilitadas(opcion){
    var url="model/unidad/queries/inhabilitadas.php"
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
  function eliminadas(opcion){
    var url="model/unidad/queries/eliminadas.php"
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
  window.onload(registrar_unidad());
</script>
<div class="card text-center">
  <div class="card-header">
    <h1>CONTROL DE UNIDADES</h1>
  </div>
  <div class="card-body">
    <div>
      <a class="btn btn-primary" onclick="registrar_unidad();">REGISTRO</a>
      <a class="btn btn-success" onclick="habilitadas();">ACTIVOS</a>
      <a class="btn btn-warning" onclick="inhabilitadas();">INHABILITADAS</a>
      <a class="btn btn-danger" onclick="eliminadas();">ELIMINADAS</a>
    </div>
    <div id="respuesta_control_unidad">
    </div>
  </div>
  <div class="card-footer">
    <div id="muestra"></div>
  </div>
</div>
<?php
  include_once A_MODEL.'unidad/modal/control_unidades.php';
?>
<script>
  //llamada del modal para eliminar unidades
    function eliminar_modal(datos){
      d=datos.split('||');
      $('#eliminar').modal('show');
      $('#id_eliminar').val(d[0]);
      $('#numero_eliminar').val(d[1]);
    }
  //Funcion para eliminar unidades
    function eliminar(){
      $.ajax({
        type: "POST",
        url: "model/unidad/update/eliminar.php",
        data: $("#frm_del").serialize(),
        beforeSend: function(){
        $("#respuesta_eliminar_unidad").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_eliminar_unidad").html(data);
        },
      });
      return false;
    }
  //llamada del modal para habilitar unidades
    function habilitar_modal(datos){
      d=datos.split('||');
      $('#habilitar').modal('show');
      $('#id_habilitar').val(d[0]);
      $('#numero_habilitar').val(d[1]);
    }
  //Funcion para habilitar unidades
    function habilitar(){
      $.ajax({
        type: "POST",
        url: "model/unidad/update/habilitar.php",
        data: $("#frm_hab").serialize(),
        beforeSend: function(){
        $("#respuesta_habilitar_unidad").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_habilitar_unidad").html(data);
        },
      });
      return false;
    }
  //llamada del modal para reactivar unidades
    function reactivar_modal(datos){
      d=datos.split('||');
      $('#reactivar').modal('show');
      $('#id_reactivar').val(d[0]);
      $('#numero_reactivar').val(d[1]);
    }
  //Funcion para reactivar unidades
    function reactivar(){
      $.ajax({
        type: "POST",
        url: "model/unidad/update/reactivar.php",
        data: $("#frm_reac").serialize(),
        beforeSend: function(){
        $("#respuesta_reactivar_unidad").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_reactivar_unidad").html(data);
        },
      });
      return false;
    }
  //llamada del modal para Inhabilitar unidades
    function inhabilitar_modal(datos){
      d=datos.split('||');
      $('#inhabilitar').modal('show');
      $('#id_inhabilitar').val(d[0]);
      $('#numero_inhabilitar').val(d[1]);
      $('#motivo').val('');
    }  
  //Funcion para Inhabilitar unidades
    function inhabilitar(){
      $.ajax({
        type: "POST",
        url: "model/unidad/update/inhabilitar.php",
        data: $("#frm_inh").serialize(),
        beforeSend: function(){
        $("#respuesta_inhabilitar_unidad").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_inhabilitar_unidad").html(data);
        },
      });
      return false;
    }
  //
</script>