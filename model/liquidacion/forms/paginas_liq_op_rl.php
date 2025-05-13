<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  	$usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
    $clave=$pre[3];
    $enviado=campo_limpiado("$id_unidad||$unidad||$fecha_trabajo||$clave",1,0);
  //Funcion para el detalle de la liquidacion
    //liquidacion_operador($enviado);
?>
<script type="text/javascript">
	//Funcion para cargar el formulario de liquidacion especifico
    function cambia_pagina(){
      var pagina=$("#pagina").val();
      var dato=<?php echo "'".$enviado."'"; ?>;
      var url="model/liquidacion/forms/"+pagina+"_liq_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#respuesta_pagina").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_pagina').html(datos);
        }
      });
    }
	window.onload(cambia_pagina());
</script>
<div class="card">
  <div class="card-header">
    <h5><?php echo "LIQUIDACION DE LA UNIDAD $unidad EL DIA ".campo_limpiado(transforma_fecha($fecha_trabajo),0,1) ?> </h5>
  </div>
  <div class="card-body">
  	<div class="input-group mb-3 input-group-sm">
  		<select name='pagina' id="pagina" class="custom-select" style="color:black"  onchange="cambia_pagina()" required="">
  			<option value='boletos'>BOLETOS</option>
  			<option value='paqueteria'>PAQUETERIA</option>
  			<option value='talonario'>TALONARIO</option>
  			<option value='comision'>COMISION</option>
        <option value='final'>FINAL</option>
  		</select>
  		<!--input type="text" name="target" id="target" value="<?php echo $dto ?>"hidden-->
  	</div>
  	<div id="respuesta_pagina"></div>
  </div>
</div>