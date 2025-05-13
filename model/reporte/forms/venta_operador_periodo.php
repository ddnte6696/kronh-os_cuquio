<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card text-center">
    <div class="card-header"><h5>VENTA DE OPERADOR POR PERIODO</h5></div>
    <div class="card-body">
        <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_venta_operador_periodo" id="frm_venta_operador_periodo">
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
        <input type="submit" value="Buscar datos" class="btn btn-sm btn-success btn-block" onclick="ver_venta_operador_periodo( );">
    </div>
    <div class="card-footer">
        <div id="respuesta_venta_operador_periodo"></div>
    </div>
</div>
<script>
  //funcion de registro de usuarios
    function ver_venta_operador_periodo(){
        $.ajax({
            type: "POST",
            url: "model/reporte/queries/venta_operador_periodo.php",
            data: $("#frm_venta_operador_periodo").serialize(),
            beforeSend: function(){$("#respuesta_venta_operador_periodo").html("<div class='spinner-border'></div>");},
            success: function(data){$("#respuesta_venta_operador_periodo").html(data);},
        });
        return false;
    }
  //
</script>