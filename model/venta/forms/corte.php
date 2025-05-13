<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card text-center">
  <div class="card-header"><h3>GENERAR CORTE</h3></div>
  <div class="card-body">
    <input type="submit" value="GENERAR CORTE" class="btn btn-primary " onclick="generar_corte( );">
  </div>
  <div class="card-footer"><div id="respuesta_corte"></div></div>
</div>

<script>
  function generar_corte(){
    $.ajax({
      type: "POST",
      url: "model/venta/insert/registrar_corte.php",
      data: $("#frm_corte").serialize(),
      beforeSend: function(){$("#respuesta_corte").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_corte").html(data);},
    });
    return false;
  }
  //Funcion para imprimir el div de los boletos
    function imprimirDiv(nombreDiv) {
      var contenido = document.getElementById(nombreDiv).innerHTML;
      var contenidoOriginal = document.body.innerHTML;
      document.body.innerHTML = contenido;
      window.print();
      document.body.innerHTML = contenidoOriginal;
      corte();
    }
  //
</script>