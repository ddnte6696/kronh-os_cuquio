<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo los campos enviados por el formulario
    $fecha_inicio=campo_limpiado($_POST['fecha_inicio'],0,0,1);
    $fecha_fin=campo_limpiado($_POST['fecha_fin'],0,0,1);
    $filtro=campo_limpiado($_POST['filtro'],2,0,1);
  //Identifico el filtro seleccionado
    switch ($filtro) {
      case '1':
        $encabezado="ENTRADAS REGISTRADAS DEL ".campo_limpiado(transforma_fecha($fecha_inicio,1," DE "),0,1)." AL ".campo_limpiado(transforma_fecha($fecha_fin,1," DE "),0,1);
        $agregado=" AND movimiento.tipo_movimiento=1 ";
      break;
      case '2':
        $encabezado="SALIDAS REGISTRADAS DEL ".campo_limpiado(transforma_fecha($fecha_inicio,1," DE "),0,1)." AL ".campo_limpiado(transforma_fecha($fecha_fin,1," DE "),0,1);
        $agregado=" AND movimiento.tipo_movimiento=2 ";
      break;
      default:
        $encabezado="MOVIMIENTOS REGISTRADOS DEL ".campo_limpiado(transforma_fecha($fecha_inicio,1," DE "),0,1)." AL ".campo_limpiado(transforma_fecha($fecha_fin,1," DE "),0,1);
        $agregado=Null;
      break;
    }
  //
?>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5><?php echo $encabezado; ?></h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>N°</th>
            <th>TIPO</th>
            <th>FECHA</th>
            <th>PRODUCTO</th>
            <th>PROVEEDOR</th>
            <th>CANTIDAD</th>
            <th>PRECIO</th>
            <th>UBICACION/DESTINO</th>
            <th>COMENTARIOS</th>
            <th>FECHA Y HORA DE REGISTRO</th>
            <th>USUARIO QUE REGISTRA</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
              $sentencia="
                SELECT
                  movimiento.id as id,
                  movimiento.tipo_movimiento as tipo_movimiento,
                  movimiento.f_movimiento as f_movimiento,
                  almacen.producto as producto,
                  almacen.proveedor as proveedor,
                  movimiento.cantidad as cantidad,
                  almacen.precio as precio,
                  movimiento.destino as destino,
                  movimiento.observacion as observacion,
                  movimiento.f_registro as f_registro,
                  movimiento.h_registro as h_registro,
                  movimiento.usuario as usuario,
                  movimiento.estado as estado
                FROM
                  movimientos_almacen as movimiento
                INNER JOIN
                  almacen as almacen on almacen.id=movimiento.id_producto
                INNER JOIN
                  usuarios as usuarios on usuarios.clave=movimiento.usuario
                WHERE
                  movimiento.f_movimiento BETWEEN '$fecha_inicio' AND '$fecha_fin'
                  $agregado
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
                      $tipo_movimiento=$tabla['tipo_movimiento'];
                      $f_movimiento=$tabla['f_movimiento'];
                      $producto=$tabla['producto'];
                      $proveedor=$tabla['proveedor'];
                      $cantidad=$tabla['cantidad'];
                      $precio=$tabla['precio'];
                      $destino=$tabla['destino'];
                      $observacion=$tabla['observacion'];
                      $f_registro=$tabla['f_registro'];
                      $h_registro=$tabla['h_registro'];
                      $usuario=$tabla['usuario'];
                      $estado=$tabla['estado'];
                      
                    //Defino el color de la fila dependiendo del tipo de movimiento
                      if ($tipo_movimiento==1) {
                        $color="<tr class='table-success'>";
                        $leyenda="ENTRADA";
                      }else{
                        $color="<tr class='table-danger'>";
                        $leyenda="SALIDA";
                      }

                    
                    //Obengo el nombre del usuario que registra en base a la clave
                      $nombre_usuario=busca_existencia("SELECT CONCAT(nombre,'  ',apellido) as exist from usuarios where clave='$usuario'");
                    //Creeo un concatenado de datos
                      $datos="'".campo_limpiado($id,1)."||$leyenda||$producto||$proveedor||".number_format($cantidad,2)."||$ ".number_format($precio,2)."||".campo_limpiado(transforma_fecha($f_movimiento,1," DE "),0,1)."'";
                    //Reviso el estado del movimiento para ver si no se ah cancelado
                    if ($estado==0) {
                      $color="<tr class='table-warning'>";
                      $leyenda.=" CANCELADA";
                      $boton=Null;
                    }else{
                      $boton="
                        <a onclick=\"eliminar_movimiento_modal($datos)\" class='btn btn-danger btn-sm'/>
                          <i class='fas fa-trash' style='font-size:24px'></i>
                        </a>
                      ";
                    }
                    //Imprimo los datos de la tabla
                      echo "
                        $color
                          <td>$id</td>
                          <td><strong>$leyenda</strong></td>
                          <td>$f_movimiento</td>
                          <td>$producto</td>
                          <td>$proveedor</td>
                          <td>$cantidad</td>
                          <td>$precio</td>
                          <td>$destino</td>
                          <td>$observacion</td>
                          <td>$f_registro $h_registro</td>
                          <td>$nombre_usuario</td>
                          <td>$boton</td>
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
    $(document).ready(function() {
      var table = $('#tabla').DataTable({
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType": "full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [10, 25, 50, -1],
          ['10 Filas', '25 Filas', '50 Filas', 'Mostrar todo']
        ],
        buttons: {
          buttons: [
            {
              extend: 'pageLength',
              text: 'CANTIDAD'
            },
            {
              extend: 'excelHtml5',
              text: 'DESCARGAR EXCEL',
              filename: 'CORRIDAS RESGITRADAS',
              orientation: 'landscape'
            },
            {
              extend: 'print',
              text: 'IMPRIMIR'
            },
            {
              extend: 'copy',
              text: 'COPIAR'
            },
            {
              extend: 'colvis',
              text: 'COLUMNAS'
            },
          ],
        },
      });
      table.on('responsive-resize', function(e, datatable, columns) {
        var count = columns.reduce(function(a, b) {
          return b === false ? a + 1 : a;
        }, 0);
        console.log(count + ' column(s) are hidden');
      });
    });
  //
</script>