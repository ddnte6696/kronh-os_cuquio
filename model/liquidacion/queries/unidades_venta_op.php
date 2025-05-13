<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo la fecha de trabajo del operador
    $fecha_trabajo=campo_limpiado($_POST['fecha'],0,0);
  //Busco las rutas que van hacia ese punto
    $sentencia="
      SELECT count(id) as exist FROM corrida where operador='$clave' and fecha='$fecha_trabajo'
    ;";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $busqueda=busca_existencia($sentencia);
?>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function cambia_unidad_liq(){
      var dato=$("#unidad").val();
      var url="model/liquidacion/forms/paginas_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
</script>
<div class="row">
  <div class="col-md-2">
    <div class="card">
      <div class="card-header"><h5>UNIDADES TRABAJADAS</h5></div>
      <div class="card-body">
        <?php
          if ($busqueda>0) { 
            /*echo "
              <script type='text/javascript'>
                window.onload(cambia_unidad_liq());
                </script>
            ";*/?>
            <div class="input-group mb-3 input-group-sm">
              <select name='unidad' id="unidad" class="custom-select" style="color:black">
                <?php
                  //Busco las rutas que van hacia ese punto
                    $sentencia="
                      SELECT DISTINCT 
                        a.unidad as id_unidad,
                        b.numero 
                      FROM corrida as a
                        join unidades as b on a.unidad=b.id
                      where a.operador='$clave' and a.fecha='$fecha_trabajo'
                    ;";
                  //Ejecuto la sentencia y almaceno lo obtenido en una variable
                    $resultado_sentencia=retorna_datos_sistema($sentencia);
                  //Identifico si el reultado no es vacio
                    if ($resultado_sentencia['rowCount'] > 0) {
                      //Almaceno los datos obtenidos
                        $resultado = $resultado_sentencia['data'];
                      // Recorrer los datos y llenar las filas
                        foreach ($resultado as $tabla) {
                          $id_unidad=$tabla['id_unidad'];
                          $unidad=$tabla['numero'];
                          $pre="$id_unidad||$unidad||$fecha_trabajo";
                          $dato="'".campo_limpiado($pre,1,0)."'";
                          echo "
                            <option value=$dato>$unidad</option>
                          ";
                        }
                      //
                    }
                  //
                ?>
              </select>
            </div>
            <a onclick="cambia_unidad_liq()" class="btn btn-sm btn-block btn-primary text-light">VER LIQUIDACION</a>
            <?php
          }else{ echo "NO HAY REGISTRO DE TRABAJO EN ESTA FECHA"; } ?>
      </div>
    </div>
  </div>
  <div class="col-md-10">
    <div id="expediente"></div>
  </div>
</div>