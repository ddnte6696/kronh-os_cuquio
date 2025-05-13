<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_liquidacion=campo_limpiado($_POST['dato'],2,0);
  //

?>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function cambia_pagina_paquete(){
      var pagina=$("#pagina_paquete").val();
      var dato=$("#target").val();
        if (pagina=='talonario') {
          target='forms';
        }else{
          target='queries';
        }
      var url="model/liquidacion/"+target+"/paquetes_"+pagina+"_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#respuesta_pagina_paquetes").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_pagina_paquetes').html(datos);
        }
      });
    }
  window.onload(cambia_pagina_paquete());
</script>
<div class="card">
  <div class="card-body">
    <div class="input-group mb-3 input-group-sm">
      <select name='pagina_paquete' id="pagina_paquete" class="custom-select" style="color:black"  onchange="cambia_pagina_paquete()" required="">
        <option value='sistema'>DE SISTEMA</option>
        <option value='talonario'>MANUALES</option>
      </select>
    </div>
  </div>
  <div class="card-footer">  
    <div id="respuesta_pagina_paquetes"></div>
  </div>
</div>