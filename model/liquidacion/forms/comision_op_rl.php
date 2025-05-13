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
    function anticipo(){
      var anticipo=$("#anticipo").val();
      var dato=$("#target").val();
      var url="model/liquidacion/update/ant_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          dato:dato,
          anticipo:anticipo
        },
        beforeSend: function(){
          $("#respuesta_anticipo").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_anticipo').html(datos);
        }
      });
    }
</script>
<div class="card">
  <div class="card-body">
    <table class="table table-bordered table-sm table-striped text-center" >
      
      <tbody>
        <?php
          // Defino la sentencia para obtener los datos de la liquidacion
            $sentencia="
              SELECT * FROM liq_op_rl WHERE unidad=$id_unidad and operador='$clave' and fecha='$fecha_trabajo'";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Creeo las variables del identificador
                    $comision=number_format($tabla['comision'],2);
                    $anticipo=number_format($tabla['anticipo'],2);
                    $restante=number_format($comision-$anticipo,2);
                  //
                }
              //
            }
          //Imprimo los resultados
            echo "
              <tr>
                <th>COMISION</th>
                <td>$ $comision</td>
              </tr>
              <tr>
                <th>ANTICIPO</th>
                <td>$ $anticipo</td>
              </tr>
              <tr>
                <th>RESTANTE</th>
                <th>$ $restante</th>
              </tr>
            ";
        ?>
      </tbody>
    </table>

    <div class="input-group mb-3 input-group-sm">
      <div class="input-group-prepend">
        <span class="input-group-text"><strong>COMISION TOMADA</strong></span>
      </div>
      <input type='number' min="0" step="0.01" name='anticipo' id='anticipo' class='form-control' placeholder="Introduzca la cantidad tomada de la comision" required=''/>
    </div>
    <a onclick="anticipo()" class="btn btn-sm btn-block btn-primary text-light">REGISTRAR</a>
  </div>
  <div id="respuesta_anticipo">
  </div>
</div>