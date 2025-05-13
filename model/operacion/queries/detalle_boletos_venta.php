<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo los campos enviados por el formulario
    $taquilla=campo_limpiado($_POST['origen'],2,0);
    $fecha_inicio=campo_limpiado($_POST['fecha_inicio'],0,0);
    $fecha_fin=campo_limpiado($_POST['fecha_fin'],0,0);
    $filtro=campo_limpiado($_POST['filtro'],2,0);
    $texto="BOLETOS DE LA TAQUILLA $taquilla DEL $fecha_inicio AL $fecha_fin";
    $agregado=" punto_venta='$taquilla' and ";
  //
?>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5><?php echo $texto; ?></h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>N°</th>
            <th>FECHA DE VENTA</th>
            <th>PUNTO DE VENTA</th>
            <th>ORIGEN</th>
            <th>DESTINO</th>
            <th>TIPO</th>
            <th>PRECIO</th>
            <th>PASAJERO</th>
            <th>ASIENTO</th>
            <th>FECHA DE VIAJE</th>
            <th>HORA DE VIAJE</th>
            <th>CORRIDA</th>
            <th>VENDEDOR</th>
            <th>REFERENCIA</th>
            <th>ESTADO</th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
              $sentencia="
                SELECT 
                  *
                FROM
                  boleto
                WHERE 
                  $agregado
                  $filtro between '$fecha_inicio' and '$fecha_fin'
                ;
              ";
            //Ejecuto la sentencia y almaceno lo obtenido en una variable
              $resultado_sentencia=retorna_datos_sistema($sentencia);
            //Identifico si el reultado no es vacio
              if ($resultado_sentencia['rowCount'] > 0) {
                //Almaceno los datos obtenidos
                  $resultado = $resultado_sentencia['data'];
                // Recorrer los datos y llenar las filas
                  foreach ($resultado as $tabla) {
                    //Creeo las variables del identificador
                      $id=$tabla['id'];
                      $punto_venta=$tabla['punto_venta'];
                      $origen=$tabla['origen'];
                      $destino=$tabla['destino'];
                      $tipo=$tabla['tipo'];
                      $precio=$tabla['precio'];
                      $pasajero=$tabla['pasajero'];
                      $asiento=$tabla['asiento'];
                      $f_corrida=$tabla['f_corrida'];
                      $corrida=$tabla['corrida'];
                      $hora_corrida=$tabla['hora_corrida'];
                      $f_venta=$tabla['f_venta'];
                      $h_venta=$tabla['h_venta'];
                      $referencia=$tabla['referencia'];
                      $estado=$tabla['estado'];
                    //Verifico si el usuario que vende es web o si es de taquilla
                      if ($tabla['usuario']=='WEB') {
                        $vendedor="PLATAFORMA WEB";
                      }else{
                        //Busco el nombre de la persona que vendio
                          //Defino la sentencia para buscar el nombre del operador
                          $sentencia="
                            SELECT 
                            CONCAT(nombre,' ',apellido) AS exist 
                            FROM 
                              usuarios 
                            WHERE 
                              clave='".$tabla['usuario']."'
                            ;
                          ";
                        //Obtengo el nombre del operador y lo alamceno en su variable correspodiente
                          $vendedor=campo_limpiado(busca_existencia($sentencia),0,1);
                        //
                      }
                    //Verifico el estado del boleto
                      switch ($tabla['estado']) {
                        case '1':
                          $estado_texto="PENDIENTE DE PAGO";
                          $color="<tr class='table-primary'>";
                        break;
                        case '2':
                          $estado_texto="VENDIDO";
                          $color="<tr class='table-success'>";
                        break;
                        case '3':
                          $estado_texto="CANCELADO<br>";
                          $color="<tr class='table-danger'>";
                        break;
                      }
                    //Imprimo los datos de la tabla
                      echo "
                        $color
                          <td>$id</td>
                          <td>$f_venta</td>
                          <td>$punto_venta</td>
                          <td>$origen</td>
                          <td>$destino</td>
                          <td>$tipo</td>
                          <td>$precio</td>
                          <td>$pasajero</td>
                          <td>$asiento</td>
                          <td>$f_corrida</td>
                          <td>$hora_corrida</td>
                          <td>$corrida</td>
                          <td>$vendedor</td>
                          <td>$referencia</td>
                          <td>$estado_texto</td>
                        </tr>
                      ";
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
  //funcion para confirmar la llegada del paquete
   function selecciona(opcion){
        var url="model/venta/queries/selecciona_asientos.php"
        $.ajax({
            type: "POST",
            url:url,
            data:{
              datos:opcion
            },
            success: function(datos){$('#response').html(datos);}
        });
    }
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