<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  	$clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
  //

?>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function cambia_pagina_boleto(){
      var pagina=$("#pagina_boleto").val();
      var dato=$("#target").val();
        if (pagina=='talonario') {
          target='forms';
        }else{
          target='queries';
        }
      var url="model/liquidacion/"+target+"/boletos_taq_"+pagina+"_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#respuesta_pagina_boletos").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_pagina_boletos').html(datos);
        }
      });
    }
  window.onload(cambia_pagina_boleto());
</script>
<div class="card">
  <div class="card-body">
    <div class="input-group mb-3 input-group-sm">
      <select name='pagina_boleto' id="pagina_boleto" class="custom-select" style="color:black"  onchange="cambia_pagina_boleto()" required="">
        <option value='sistema'>DE SISTEMA</option>
        <option value='talonario'>MANUALES</option>
      </select>
    </div>
  </div>
  <div class="card-footer">  
    <div id="respuesta_pagina_boletos"></div>
  </div>
</div>