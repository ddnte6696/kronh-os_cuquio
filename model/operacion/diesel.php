<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<script type="text/javascript">
  function formulario_diesel(opcion){
    var url="model/operacion/forms/diesel.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ id:opcion },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        $('#expediente').html("");
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }
  function diesel(opcion){
    var url="model/operacion/forms/busqueda_diesel.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ id:opcion },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        $('#expediente').html("");
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }
  function archivo_diesel(opcion){
    var url="model/operacion/forms/archivo_diesel.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ id:opcion },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        $('#expediente').html("");
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }
  window.onload(formulario_diesel());
</script>
<div class="card text-center">
  <div class="card-header">
      <a class="btn btn-sm text-sm btn-primary" onclick="formulario_diesel();">REGISTRAR CARGA</a>
      <a class="btn btn-sm text-sm btn-primary" onclick="diesel();">CONSULTA</a>
      <!--a class="btn btn-sm text-sm btn-primary" onclick="archivo_diesel();">Cargar archivo de diesel</a-->
  </div>
  <div class="card-body">
    <div id="muestra"></div>
  </div>
</div>