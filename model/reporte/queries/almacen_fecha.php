<?php
    //Se revisa si la sesión esta iniciada y sino se inicia
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
    //Se manda a llamar el archivo de configuración
        include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
    //Obtengo la clave del operador que esta logueado
        $usuario = campo_limpiado($_SESSION[UBI]['clave'], 2);
    //Obtengo los datos enviados por el formulario
        $fecha = campo_limpiado($_POST['fecha']);
    //Defino la fecha actual
        $actual = ahora(1);
    //Obtengo el total del almacen
        $total_almacen = busca_existencia("SELECT SUM(cantidad*precio) as exist FROM almacen");
    //Obtengo el total de las entradas y salidas a la fecha
        $total_entradas = busca_existencia("SELECT SUM(cantidad*precio) as exist FROM movimientos_almacen where f_movimiento BETWEEN '$fecha' AND '$actual' and tipo_movimiento=1 and estado=1");
        $total_salidas = busca_existencia("SELECT SUM(cantidad*precio) as exist FROM movimientos_almacen where f_movimiento BETWEEN '$fecha' AND '$actual' and tipo_movimiento=2 and estado=1");
    //Obtengo el total de entradas y salidas en la fecha especificada
        $total_entradas_fecha = busca_existencia("SELECT SUM(cantidad*precio) as exist FROM movimientos_almacen where f_movimiento='$fecha' and tipo_movimiento=1 and estado=1");
        $total_salidas_fecha = busca_existencia("SELECT SUM(cantidad*precio) as exist FROM movimientos_almacen where f_movimiento='$fecha' and tipo_movimiento=2 and estado=1");
    //Calculo el inventario inicial y el inventario final
        $inventario_inicial = ($total_almacen - $total_entradas) + $total_salidas;
        $inventario_final = ($inventario_inicial - $total_salidas_fecha) + $total_entradas_fecha;
    //
?>
<div class="card text-center">
    <div class="card-text">
        <a class="btn btn-block btn-primary" href="print_files/almacen.php?report=<?php echo campo_limpiado($fecha, 1); ?> ?>" target='_blank'>IMPRIMIR REPORTE</a>
    </div>
    <div class="card-header">
        <h2>OMNIBUS YAHUALICA GUADALAJARA S.A. DE C.V.</h2>
        <h3>REPORTE DE ALMACEN DEL DIA <?php echo campo_limpiado(transforma_fecha($fecha, 1, " DE "), 0, 1); ?></h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h5>INVENTARIO INICIAL</h5>
                    </div>
                    <div class="card-body">
                        <h2>$<?php echo number_format($inventario_inicial, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h5>ENTRADAS</h5>
                    </div>
                    <div class="card-body">
                        <h2>$<?php echo number_format($total_entradas_fecha, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h5>SALIDAS</h5>
                    </div>
                    <div class="card-body">
                        <h2>$<?php echo number_format($total_salidas_fecha, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h5>INVENTARIO FINAL</h5>
                    </div>
                    <div class="card-body">
                        <h2>$<?php echo number_format($inventario_final, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h5>ENTRADAS</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped" id="tabla_entradas">
                            <thead>
                            <tr>
                                <th>DESTINO</th>
                                <th>FACTURA O NOTA</th>
                                <th>PRODUCTO</th>
                                <th>COMENTARIOS</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    //Busco las rutas que van hacia ese punto
                                    $sentencia="
                                        SELECT
                                            movimiento.nota as nota,
                                            almacen.producto as producto,
                                            almacen.proveedor as proveedor,
                                            movimiento.cantidad as cantidad,
                                            almacen.precio as precio,
                                            movimiento.destino as destino,
                                            movimiento.observacion as observacion
                                        FROM movimientos_almacen as movimiento
                                        INNER JOIN almacen as almacen on almacen.id=movimiento.id_producto
                                        WHERE movimiento.f_movimiento='$fecha' and movimiento.tipo_movimiento=1 and movimiento.estado=1
                                        ORDER BY movimiento.id DESC
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
                                            $nota=$tabla['nota'];
                                            $producto=$tabla['producto'];
                                            $proveedor=$tabla['proveedor'];
                                            $cantidad=$tabla['cantidad'];
                                            $precio=$tabla['precio'];
                                            $destino=$tabla['destino'];
                                            $observacion=$tabla['observacion'];
                                            $total=$cantidad*$precio;
                                            //Imprimo los datos de la tabla
                                            echo "
                                                <tr>
                                                    <td>$destino</td>
                                                    <td>$nota</td>
                                                    <td>$producto</td>
                                                    <td>$observacion</td>
                                                    <td>$cantidad</td>
                                                    <td>$precio</td>
                                                    <td>$total</td>
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
            <div class="col-lg-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h5>SALIDAS</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped" id="tabla_salidas">
                            <thead>
                            <tr>
                                <th>DESTINO</th>
                                <th>FACTURA O NOTA</th>
                                <th>PRODUCTO</th>
                                <th>COMENTARIOS</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    //Busco las rutas que van hacia ese punto
                                    $sentencia="
                                        SELECT
                                            movimiento.nota as nota,
                                            almacen.producto as producto,
                                            almacen.proveedor as proveedor,
                                            movimiento.cantidad as cantidad,
                                            almacen.precio as precio,
                                            movimiento.destino as destino,
                                            movimiento.observacion as observacion
                                        FROM movimientos_almacen as movimiento
                                        INNER JOIN almacen as almacen on almacen.id=movimiento.id_producto
                                        WHERE movimiento.f_movimiento='$fecha' and movimiento.tipo_movimiento=2 and movimiento.estado=1
                                        ORDER BY movimiento.id DESC
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
                                            $nota=$tabla['nota'];
                                            $producto=$tabla['producto'];
                                            $proveedor=$tabla['proveedor'];
                                            $cantidad=$tabla['cantidad'];
                                            $precio=$tabla['precio'];
                                            $destino=$tabla['destino'];
                                            $observacion=$tabla['observacion'];
                                            $total=$cantidad*$precio;
                                            //Imprimo los datos de la tabla
                                            echo "
                                                <tr>
                                                    <td>$destino</td>
                                                    <td>$nota</td>
                                                    <td>$producto</td>
                                                    <td>$observacion</td>
                                                    <td>$cantidad</td>
                                                    <td>$precio</td>
                                                    <td>$total</td>
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
        </div>
    </div>
</div>
<script type="text/javascript">
  //Funcio para la tabla
    $(document).ready(function() {
      var table = $('#tabla_entradas').DataTable({
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
//Funcio para la tabla
$(document).ready(function() {
      var table = $('#tabla_salidas').DataTable({
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