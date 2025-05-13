<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo datos de la sesion
    $taquilla_actual=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $dat_origen=explode("||",campo_limpiado($_POST['origen'],2,0));
    $id_origen=campo_limpiado($dat_origen[0],0,0);
    $origen=campo_limpiado($dat_origen[1],0,0);
    $dat_destino=explode("||",campo_limpiado($_POST['destino'],2,0));
    $id_destino=campo_limpiado($dat_destino[0],0,0);
    $destino=campo_limpiado($dat_destino[1],0,0);
  //
?>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5>PRECIOS POR RUTA REGISTRADOS DESDE <strong><?php echo $origen; ?></strong> HACIA <strong><?php echo $destino; ?></strong></h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>RUTA</th>
            <th>NORMAL</th>
            <th>MEDIO</th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
            $sentencia="SELECT * From ruta where id_destino=$id_destino and punto_origen='$origen';";
            //Ejecuto la sentencia y almaceno lo obtenido en una variable
              $resultado_sentencia=retorna_datos_sistema($sentencia);
            //Identifico si el reultado no es vacio
              if ($resultado_sentencia['rowCount'] > 0) {
                //Almaceno los datos obtenidos
                  $resultado = $resultado_sentencia['data'];
                // Recorrer los datos y llenar las filas
                  foreach ($resultado as $tabla) {
                    //Creeo las variables del identificador
                      $nombre_ruta=$tabla['nombre_ruta'];
                      $precio_normal=$tabla['precio_normal'];
                      $precio_medio=$tabla['precio_medio'];
                    //
                      echo "
                        <tr>
                        <td>$nombre_ruta</td>
                        <td>$precio_normal</td>
                        <td>$precio_medio</td>
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
  </div>
</div>
<script type="text/javascript">
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla').DataTable( {
        responsive: true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
          { extend: 'excel', text:'Descargar Excel' },
          { extend: 'print', text:'Imprimir' },{ extend: 'copy', text:'Copiar' },
          ],
        },
      } );
      table.on( 'responsive-resize', function ( e, datatable, columns ) {
        var count = columns.reduce( function (a,b) {
          return b === false ? a+1 : a;
        }, 0 );
        console.log( count +' column(s) are hidden' );
      } );
    } );
  //
</script>