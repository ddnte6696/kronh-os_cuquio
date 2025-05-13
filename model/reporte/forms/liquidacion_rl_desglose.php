<?php
  /*error_reporting(E_ALL);
  ini_set("display_errors", 1);*/
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<div class="card">
  <div class="card-header"><h1>REPORTE DE LIQUIDACION DE RUTA LARGA DESGLOSADO</h1></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_liquidacion_fecha_desglose" id="frm_liquidacion_fecha_desglose">
        <div class="row text-center">
            <div class="col-md-6">
                <div class="input-group mb-3 input-group-sm">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><strong>FECHA DE INICIO</strong></span>
                    </div>
                    <input type='date' name='fecha_inicio' name='fecha_inicio' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-3 input-group-sm">
                    <div class="input-group-prepend">
                    <span class="input-group-text"><strong>FECHA DE FIN</strong></span>
                    </div>
                    <input type='date' name='fecha_fin' name='fecha_fin' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
                </div>
            </div>
        </div>
    </form>
    <input type="submit" value="Buscar datos" class="btn btn-sm btn-success btn-block" onclick="buscar_fecha_liquidacion_desglose( );">
    </div>
  <div class="card-footer">
    <div id="respuesta_liquidacion_fecha_desglose"></div>
  </div>
</div>
<script>
  //funcion de registro de usuarios
    function buscar_fecha_liquidacion_desglose(){
        $.ajax({
            type: "POST",
            url: "model/reporte/queries/liquidacion_fecha_rl_desglose.php",
            data: $("#frm_liquidacion_fecha_desglose").serialize(),
            beforeSend: function(){$("#respuesta_liquidacion_fecha_desglose").html("<div class='spinner-border'></div>");},
            success: function(data){$("#respuesta_liquidacion_fecha_desglose").html(data);},
        });
        return false;
    }
  //
</script>