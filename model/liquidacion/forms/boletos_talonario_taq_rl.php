<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $clave_usr=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_liquidacion=campo_limpiado($_POST['dato'],2,0);
  //Defino la sentencia de busqueda
    $sentencia="SELECT * FROM corte where id=$id_liquidacion";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
            $usuario=$tabla['usuario'];
            $fecha=$tabla['fecha'];
        }
      //
    }
  //
?>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function muestra_talonario(opcion){
      var dato=$("#target").val();
      var url="model/liquidacion/queries/talonario_pas_taq_rl.php"
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
  //Funcion para cargar el formulario de liquidacion especifico
    function tabla_talonario(opcion){
      var dato=$("#target").val();
      var url="model/liquidacion/queries/tabla_pas_talonario_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          dato:dato
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
  <div class="card-text">
    <a onclick="tabla_talonario()" class="btn btn-primary btn-block" data-toggle="tooltip"><strong>VER TALONARIOS REGISTRADOS</strong></a>
  </div>
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
            $sentencia = "SELECT * FROM talonario WHERE restantes>0 and usuario='$usuario' and uso=1 and tipo=1";
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