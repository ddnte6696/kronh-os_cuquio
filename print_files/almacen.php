<?php
//Se revisa si la sesión esta iniciada y sino se inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Se manda a llamar el archivo de configuración
include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
//Inclucion de los archivos principales
include A_VIEW . "header.php";
//Obtengo la clave del operador que esta logueado
$usuario = campo_limpiado($_SESSION[UBI]['clave'], 2);
$nombre_imprime = campo_limpiado($_SESSION[UBI]['nombre'], 2) . " " . campo_limpiado($_SESSION[UBI]['apellido'], 2);
//Obtengo los datos enviados por el formulario
$fecha = campo_limpiado($_GET['report'], 2);
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
<style type="text/css">
    @media print {
        @page {
            size: auto;
        }
    }
</style>
<div class="card text-center">
    <div class="card-header">
        <h2>OMNIBUS YAHUALICA GUADALAJARA S.A. DE C.V.</h2>
        <h3>REPORTE DE ALMACEN DEL DIA <?php echo campo_limpiado(transforma_fecha($fecha, 1, " DE "), 0, 1); ?></h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h7><strong>INVENTARIO INICIAL</strong></h7>
                    </div>
                    <div class="card-body">
                        <h7>$<?php echo number_format($inventario_inicial, 2); ?></h7>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h7><strong>ENTRADAS</strong></h7>
                    </div>
                    <div class="card-body">
                        <h7>$<?php echo number_format($total_entradas_fecha, 2); ?></h7>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h7><strong>SALIDAS</strong></h7>
                    </div>
                    <div class="card-body">
                        <h7>$<?php echo number_format($total_salidas_fecha, 2); ?></h7>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card text-center">
                    <div class="card-header">
                        <h7><strong>INVENTARIO FINAL</strong></h7>
                    </div>
                    <div class="card-body">
                        <h7>$<?php echo number_format($inventario_final, 2); ?></h7>
                    </div>
                </div>
            </div>
            <div class="col-12">
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
            <div class="col-12">
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
    <br><br><br><br>
    <div class="card-footer">
        <div class="row">
            <div class="col" style=" border: black 1px solid;"><strong>FIRMA ALMACEN</strong><br><?php echo $nombre_imprime ?></div>

            <div class="col" style=" border: black 1px solid;"><strong>FIRMA INGRESOS</strong><br></div>

            <div class="col" style=" border: black 1px solid;"><strong>FIRMA COORDINADOR</strong><br><?php echo campo_limpiado("Lic.Erick Ricardo Trujillo Perez", 0, 1); ?></div>

            <div class="col" style=" border: black 1px solid;"><strong>FIRMA CONTABILIDAD</strong><br></div>
            
            <div class="col" style=" border: black 1px solid;"><strong>FIRMA GERENCIA</strong><br><?php echo campo_limpiado("REINALDO MORA VELIZ", 0, 1); ?></div>
            
            <div class="col" style=" border: black 1px solid;"><strong>FIRMA DIRECCION</strong><br><?php echo campo_limpiado("Ana Karen Padilla Mendes", 0, 1); ?></div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    window.onload = function() {
        window.print();
    }
</script>