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
  //
?>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function cerrar_cuenta(){
      var dato=<?php echo "'".$enviado."'"; ?>;
      var url="model/liquidacion/update/cerrar_cuenta_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          dato:dato,
          anticipo:anticipo
        },
        beforeSend: function(){
          $("#respuesta_cerrar_cuenta").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_cerrar_cuenta').html(datos);
        }
      });
    }
</script>
<div class="card">
  <div class="card-header"><h5>TOTALES</h5></div>
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
                    $boletos_sistema=number_format($tabla['boletos_sistema'],2);
                    $boletos_talonario=number_format($tabla['boletos_talonario'],2);
                    $paqueterias_sistema=number_format($tabla['paqueterias_sistema'],2);
                    $paqueterias_talonario=number_format($tabla['paqueterias_talonario'],2);
                    $talonario=number_format($tabla['talonario'],2);
                    $comision=number_format($tabla['comision'],2);
                    if ($tabla['anticipo']>0) { $anticipo=number_format($tabla['anticipo'],2); }else{ $anticipo="0.00"; }
                    if ($anticipo>0) { 
                      $deposito=number_format(($tabla['talonario']-$tabla['anticipo']),2); }else{ $deposito=$talonario; }
                  //
                }
              //
            }
          //Imprimo los resultados
            echo "
            <tr>
              <th>BOLETOS DE SISTEMA</th>
              <td>$ $boletos_sistema</td>
            </tr>
            <tr>
              <th>BOLETOS MANUALES</th>
              <td>$ $boletos_talonario</td>
            </tr>
            <tr>
              <th>PAQUETERIAS DE SISTEMA</th>
              <td>$ $paqueterias_sistema</td>
            </tr>
            <tr>
              <th>PAQUTERIAS MANUALES</th>
              <td>$ $paqueterias_talonario</td>
            </tr>
            <tr>
              <th>TALONARIOS</th>
              <td>$ $talonario</td>
            </tr>
            <tr>
              <th>COMISION</th>
              <td>$ $comision</td>
            </tr>
            <tr>
              <th>ANTICIPO</th>
              <td>$ $anticipo</td>
            </tr>
            <tr>
              <th>A DEPOSITAR</th>
              <th>$ $deposito</th>
            </tr>
            ";
        ?>
      </tbody>
    </table>
  </div>
  <div id="respuesta_anticipo">
  </div>
</div>