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
    function total_talonario_taquilla(){
      var importe=$("#importe_taquilla").val();
      var dato=$("#target").val();
      var url="model/liquidacion/update/pas_tal_taq_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
        	dato:dato,
        	importe:importe
        },
        beforeSend: function(){
          $("#total_talonario_taquilla").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#total_talonario_taquilla').html(datos);
        }
      });
    }
</script>
<div class="card">
  <div class="card-header"><h5>BOLETOS MANUALES DE TAQUILLA</h5></div>
  <div class="card-body">
    <div class="input-group mb-3 input-group-sm">
      <div class="input-group-prepend">
        <span class="input-group-text"><strong>IMPORTE TOTAL</strong></span>
      </div>
      <input type='number' min="0" step="0.01" name='importe_taquilla' id='importe_taquilla' class='form-control' placeholder="Introdusca el importe total de los boletos de talonario de taquilla" required=''/>
    </div>
  	<a onclick="total_talonario_taquilla()" class="btn btn-sm btn-block btn-primary text-light">REGISTRAR</a>
  </div>
  <div id="total_talonario_taquilla">
  </div>
</div>