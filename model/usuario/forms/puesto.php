<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
?>
<script>
    function tabla_puestos(dato){
      var url="model/usuario/queries/puestos.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#puestos").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#puestos').html(datos);
        }
      });
    }
    $(document).ready(tabla_puestos());
</script>
<div class="card">
  <div class="card-header">
    <h5>Agregar un puesto</h5>
  </div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_puesto" id="frm_agregar_puesto">
      <div class="form-group">
        <div>
          <input type="text" id="puesto" name="puesto" class="form-control"  placeholder="Puesto a crear" required="">
        </div>
      </div>        
      <!-- Input Boton -->
      <div class="form-actions">
        <input type="submit" value="Registrar" class="btn btn-sm btn-success" onclick="registrar_puesto();">
      </div>
      <div id="respuesta_agregar_puesto"></div>
    </form>
  </div>
  <div class="card-footer"><div id="puestos"></div></div>
</div>
<script>
  //script para agregar un puesto
    $(function registrar_puesto(){
      $("#frm_agregar_puesto").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("frm_agregar_puesto"));
        formData.append("dato", "valor");
        $.ajax({
          url: "model/usuario/insertion/puesto.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $("#respuesta_agregar_puesto").html("<div class='spinner-border'></div>");
          },
        })
        .done(function(res){
          $("#respuesta_agregar_puesto").html(res);
          
        });
      });
    });
  //
</script>