<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>

<div class="card">
  <div class="card-header"><h3>INVENTARIO</h3></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla_almacen">
      <thead>
        <tr>
          <th>ID</th>
          <th>PRODUCTO</th>
          <th>PROVEEDOR</th>
          <th>CANTIDAD</th>
          <th>PRECIO</th>
          <th>TOTAL</th>
          <th>OBSERVACION</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia="SELECT * from almacen where cantidad > 0";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Almceno los datos en una variable
                    $id_producto=$tabla['id'];
                    $proveedor=$tabla['proveedor'];
                    $producto=$tabla['producto'];
                    $observacion=$tabla['observaciones'];
                    $cantidad=$tabla['cantidad'];
                    $precio=$tabla['precio'];
                    $total=$precio*$cantidad;
                    $ubicacion=$tabla['ubicacion'];
                  //Creeo un concatenado con los datos a enviar informacion
                    $datos="'".campo_limpiado($id_producto,1)."||$producto||$cantidad||$precio||$proveedor||$observacion||$ubicacion'";
                  //Imprimo los datos de la tabla
                    echo "
                      <tr>
                        <td>$id_producto</td>
                        <td>$producto</td>
                        <td>$proveedor</td>
                        <td>".number_format($cantidad,2)."</td>
                        <td>".number_format($precio,2)."</td>
                        <td>".number_format($total,2)."</td>
                        <td>$observacion</td>
                        <td>
                          <a onclick=\"copiar_producto_modal($datos)\" class='btn btn-primary btn-sm'/>
                            <i class='fas fa-copy' style='font-size:24px'></i> <strong>COPIAR</strong>
                          </a>
                          <a onclick=\"reducir_existencia_modal($datos)\" class='btn btn-warning btn-sm'/>
                            <i class='fas fa-minus' style='font-size:24px'></i> <strong>RESTAR</strong>
                          </a>
                          <!--a onclick=\"modificar_existencia_modal($datos)\" class='btn btn-danger btn-sm'/>
                            <i class='fas fa-trash' style='font-size:24px'></i> <strong>ELIMINAR</strong>
                          </a-->
                        </td>
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
<script type="text/javascript">
  //Funcion para la tabla
    $(document).ready( function () {
      var table = $('#tabla_almacen').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
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
