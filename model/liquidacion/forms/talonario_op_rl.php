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
    function muestra_talonario(opcion){
      var dato=$("#target").val();
      var url="model/liquidacion/queries/talonario_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          dato:dato,
          id_talonario:opcion
        },
        beforeSend: function(){
          $("#respuesta_pagina_talonario").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_pagina_talonario').html(datos);
        }
      });
    }
</script>
<div class="card">
  <div class="card-body">

    <table class="table table-bordered table-sm text-sm" >
      <thead>
        <tr>
          <th>SERIE</th>
          <th>ACTUAL</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia = "SELECT * FROM talonario WHERE restantes>0 and usuario='$clave' and uso=2";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia = retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Almaceno los datos en variables
                    $serie=$tabla['serie']." ".$tabla['inicial']."-".$tabla['final'];
                    $actual=$tabla['actual'];
                    $dato=campo_limpiado($tabla['id'],1,0);
                  //Realizo la impresion de los datos
                    echo "
                      <tr>
                        <td>
                          <a onclick=\"muestra_talonario('$dato')\" class=\"btn btn-primary\" data-toggle=\"tooltip\"><strong>$serie</strong></a>
                        </td>
                        <td>$actual</td>
                      </tr>
                    ";
                  //
                }
              //
            }
          //
        ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer">  
    <div id="respuesta_pagina_talonario"></div>
  </div>
</div>