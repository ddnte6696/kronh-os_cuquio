<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
?>
<div class="card">
  <div class="card-header"><h3>REGISTRO DE DESTINOS</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_destino" id="frm_agregar_destino">
      <div class="input-group mb-3 input-group-sm">
        <div class="input-group-prepend">
          <span class="input-group-text"><strong>Destino</strong></span>
        </div>
        <input type="text" name="destino" class="form-control" placeholder="Nombre del destino a registrar" required="">
      </div>
    </form>
  </div>
  <div class="card-footer">
    <div id="respuesta_agregar_destino"></div>
    <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_destino( );">
  </div>
</div>
<script>
  //funcion de registro de destinos
    function registro_destino(){
      $.ajax({
        type: "POST",
        url: "model/operacion/insertion/destino.php",
        data: $("#frm_agregar_destino").serialize(),
        beforeSend: function(){$("#respuesta_agregar_destino").html("<div class='spinner-border'></div>");},
        success: function(data){$("#respuesta_agregar_destino").html(data);},
      });
      return false;
    }
  //
</script>