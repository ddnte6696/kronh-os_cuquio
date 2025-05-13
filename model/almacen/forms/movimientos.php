<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card text-center">
    <div class="card-header"><h5>MOVIMIENTOS DE ALMACEN</h5></div>
    <div class="card-body">
        <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_movimientos" id="frm_movimientos">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>FILTRO</strong></span>
                        </div>
                        <select  name='filtro' id="filtro" class="custom-select"  style="color:black"  onchange="busca_destinos();" required="">
                        <?php
                            //Defino algunos valores por defecto
                            echo "<option value='".campo_limpiado('TODOS',1,0)."'>TODOS LOS MOVIMIENTOS</option>";
                            echo "<option value='".campo_limpiado('1',1,0)."'>SOLO ENTRADAS</option>";
                            echo "<option value='".campo_limpiado('2',1,0)."'>SOLO SALIDAS</option>";
                            //
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><strong>FECHA DE INICIO</strong></span>
                        </div>
                        <input type='date' name='fecha_inicio' name='fecha_inicio' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><strong>FECHA DE FIN</strong></span>
                        </div>
                        <input type='date' name='fecha_fin' name='fecha_fin' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
                    </div>
                </div>
            </div>
        </form>
        <input type="submit" value="Buscar datos" class="btn btn-sm btn-success btn-block" onclick="ver_movimientos( );">
    </div>
    <div class="card-footer">
        <div id="respuesta_movimientos"></div>
    </div>
</div>
<script>
  //funcion de registro de usuarios
    function ver_movimientos(){
        $.ajax({
            type: "POST",
            url: "model/almacen/queries/movimientos.php",
            data: $("#frm_movimientos").serialize(),
            beforeSend: function(){$("#respuesta_movimientos").html("<div class='spinner-border'></div>");},
            success: function(data){$("#respuesta_movimientos").html(data);},
        });
        return false;
    }
  //
</script>