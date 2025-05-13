<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2);
  //Obtengo los datos enviados por el formulario
    $fecha_inicio=campo_limpiado($_POST['fecha_inicio']);
    $fecha_fin=campo_limpiado($_POST['fecha_fin']);
  //
    /*error_reporting(E_ALL);
    ini_set("display_errors", 1);*/
?>
<div class="card text-center">
  <div class="card-header">
    <h5>OMNIBUS YAHUALICA GUADALAJARA S.A. DE C.V.</h5>
    <h5>REPORTE DE LIQUIDACION DEL DEL <?php echo campo_limpiado(transforma_fecha($fecha_inicio,1," DE "),0,1)." AL ".campo_limpiado(transforma_fecha($fecha_fin,1," DE "),0,1); ?></h5>
  </div>
  <div class="card-body">
    <div class="row">
      <!-- TABLA DE OPERADOR -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><h6>VENTA POR UNIDAD</h6></div>
            <div class="card-body">
              <table class="table table-bordered table-sm text-sm table-striped" id="tabla_operador">
                <thead>
                  <tr>
                    <th>FECHA</th>  
                    <th>UNIDAD</th>
                    <th>OPERADOR</th>
                    <th>T. ABORDO</th>
                    <th>PAQUETES SISTEMA</th>
                    <th>PAQUETES TALONARIO</th>
                    <th>PASAJEROS SISTEMA</th>
                    <th>PASAJEROS TALONARIO</th>
                    <th>VEN.NETAS</th>
                    <th>VEN.S/IVA</th>
                    <th>IVA</th>
                    <th>CONTADO</th>
                    <th>CREDITO</th>
                    <th>LITROS</th>
                    <th>COMISION</th>
                    <th>REMANENTE</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    //Defino variables vacias para las cumatorias
                      $sumatoria_abordo=Null;
                      $sumatoria_pasajeros_sistema=Null;
                      $sumatoria_pasajeros_talonario=Null;
                      $sumatoria_paquetes_sistema=Null;
                      $sumatoria_paquetes_talonario=Null;
                      $sumatoria_ventas_totales=Null;
                      $sumatoria_ventas_sin_iva=Null;
                      $sumatoria_iva=Null;
                      $sumatoria_importe_contado=Null;
                      $sumatoria_importe_credito=Null;
                      $sumatoria_litros_diesel=Null;
                      $sumatoria_comision=Null;
                      $sumatoria_remanente=Null;
                    //Defino la sentencia de busqueda
                      $sentencia="SELECT * FROM liq_op_rl where fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                    //Ejecuto la sentencia y almaceno lo obtenido en una variable
                      $resultado_sentencia=retorna_datos_sistema($sentencia);
                    //Identifico si el reultado no es vacio
                      if ($resultado_sentencia['rowCount'] > 0) {
                        //Almaceno los datos obtenidos
                          $resultado = $resultado_sentencia['data'];
                        // Recorrer los datos y llenar las filas
                          foreach ($resultado as $tabla) {
                            //Datos de la unidad
                              $unidad=$tabla['unidad'];
                              $dto_unidad=busca_existencia("SELECT numero AS exist FROM unidades WHERE id=$unidad");
                            //Datos del operador
                              $operador=$tabla['operador'];
                              $dto_operador=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM operadores WHERE clave='$operador'");
                            //Datos de venta
                              $abordo=$tabla['talonario'];
                              $pasajeros_sistema=$tabla['boletos_sistema'];
                              $pasajeros_talonario=$tabla['boletos_talonario'];
                              $paquetes_sistema=$tabla['paqueterias_sistema'];
                              $paquetes_talonario=$tabla['paqueterias_talonario'];
                              $comision=$tabla['comision'];
                              $fecha=$tabla['fecha'];
                            //Calculos de venta
                              $ventas_totales=$abordo+$pasajeros_sistema+$paquetes_sistema+$pasajeros_talonario+$paquetes_talonario;
                              $ventas_sin_iva=$ventas_totales/1.16;
                              $iva=$ventas_sin_iva*0.16;
                            //Datos de cargas de diesel
                              $importe_contado=busca_existencia("SELECT importe_diesel_contado AS exist FROM cargas_diesel WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' and unidad=$unidad and operador='$operador'");
                              if ($importe_contado=="") { $importe_contado=0; }
                              $importe_credito=busca_existencia("SELECT importe_diesel_credito AS exist FROM cargas_diesel WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' and unidad=$unidad and operador='$operador'");
                              if ($importe_credito=="") { $importe_credito=0; }
                              $litros_diesel=busca_existencia("SELECT SUM(diesel_contado+diesel_credito) AS exist FROM cargas_diesel WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' and unidad=$unidad and operador='$operador'");
                              if ($litros_diesel=="") { $litros_diesel=0; }
                            //Calculo del remanente
                              $remanente=$ventas_totales-($importe_contado+$importe_credito+$comision);
                            //Realizo los calculos de las sumatorias
                              $sumatoria_abordo+=$abordo;
                              $sumatoria_pasajeros_sistema+=$pasajeros_sistema;
                              $sumatoria_pasajeros_talonario+=$pasajeros_talonario;
                              $sumatoria_paquetes_sistema+=$paquetes_sistema;
                              $sumatoria_paquetes_talonario+=$paquetes_talonario;
                              $sumatoria_ventas_totales+=$ventas_totales;
                              $sumatoria_ventas_sin_iva+=$ventas_sin_iva;
                              $sumatoria_iva+=$iva;
                              $sumatoria_importe_contado+=$importe_contado;
                              $sumatoria_importe_credito+=$importe_credito;
                              $sumatoria_litros_diesel+=$litros_diesel;
                              $sumatoria_comision+=$comision;
                              $sumatoria_remanente+=$remanente;
                            //Imprimo la tabla
                              echo "
                                <tr>
                                  <td>$fecha</td>
                                  <td>$dto_unidad</td>
                                  <td>[$operador] - $dto_operador</td>
                                  <td>$ ".number_format($abordo,2)."</td>
                                  <td>$ ".number_format($paquetes_sistema,2)."</td>
                                  <td>$ ".number_format($paquetes_talonario,2)."</td>
                                  <td>$ ".number_format($pasajeros_sistema,2)."</td>
                                  <td>$ ".number_format($pasajeros_talonario,2)."</td>
                                  <td>$ ".number_format($ventas_totales,2)."</td>
                                  <td>$ ".number_format($ventas_sin_iva,2)."</td>
                                  <td>$ ".number_format($iva,2)."</td>
                                  <td>$ ".number_format($importe_contado,2)."</td>
                                  <td>$ ".number_format($importe_credito,2)."</td>
                                  <td>".number_format($litros_diesel,2)."</td>
                                  <td>$ ".number_format($comision,2)."</td>
                                  <td>$ ".number_format($remanente,2)."</td>
                                </tr>
                              ";
                            //
                          }
                        //
                      }
                    //Imprimo la fila de totales
                    echo "
                      <tr>
                        <td></td>
                        <td></td>
                        <td><strong>TOTAL DE LIQUIDACION</strong></td>
                        <td><strong>$ ".number_format($sumatoria_abordo,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_paquetes_sistema,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_paquetes_talonario,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_pasajeros_sistema,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_pasajeros_talonario,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_ventas_totales,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_ventas_sin_iva,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_iva,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_importe_contado,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_importe_credito,2)."</strong></td>
                        <td><strong>".number_format($sumatoria_litros_diesel,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_comision,2)."</strong></td>
                        <td><strong>$ ".number_format($sumatoria_remanente,2)."</strong></td>
                      </tr>
                    ";
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <!-- TABLA DE VENTA POR TAQUILLA -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><h6>VENTAS POR TAQUILLA</h6></div>
            <div class="card-body">
              <table class="table table-bordered table-sm text-sm table-striped" id="tabla_taquilla">
                <thead>
                  <th>FECHA</th>
                  <th>TAQUILLA</th>
                  <th>VENDEDOR</th>
                  <th>PASAJEROS SISTEMA</th>
                  <th>PASAJEROS MANUAL</th>
                  <th>PAQUETES SISTEMA</th>
                  <th>PAQUETES MANUAL</th>
                  <th>TOTAL</th>
                </thead>
                <tbody>
                  <?php
                    //Defino una variable de sumatoria
                      $suma_importe_boletos_sistema=Null;
                      $suma_importe_boletos_talonario=Null;
                      $suma_importe_paquetes_sistema=Null;
                      $suma_importe_paquetes_talonario=Null;
                      $suma_total=Null;
                    //Defino la sentencia de busqueda
                      $sentencia="
                        SELECT 
                          a.punto_venta, 
                          a.usuario,
                          a.importe_boletos_sistema,
                          a.importe_paquetes_sistema,
                          a.importe_boletos_talonario,
                          a.importe_paquetes_talonario,
                          a.fecha
                        FROM corte AS a 
                          JOIN destinos AS b ON a.punto_venta=b.destino
                        WHERE 
                          a.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                        ORDER BY b.id ASC;

                      ";
                    //Ejecuto la sentencia y almaceno lo obtenido en una variable
                      $resultado_sentencia=retorna_datos_sistema($sentencia);
                    //Identifico si el reultado no es vacio
                      if ($resultado_sentencia['rowCount'] > 0) {
                        //Almaceno los datos obtenidos
                          $resultado = $resultado_sentencia['data'];
                        // Recorrer los datos y llenar las filas
                          foreach ($resultado as $tabla) {
                            //Datos obtenidos de la consulta
                              $fecha=$tabla['fecha'];
                              $punto_venta=$tabla['punto_venta'];
                              $usuario=$tabla['usuario'];
                              $importe_boletos_sistema=$tabla['importe_boletos_sistema'];
                              $importe_boletos_talonario=$tabla['importe_boletos_talonario'];
                              $importe_paquetes_sistema=$tabla['importe_paquetes_sistema'];
                              $importe_paquetes_talonario=$tabla['importe_paquetes_talonario'];
                              $total=$importe_boletos_sistema+$importe_boletos_talonario+$importe_paquetes_sistema+$importe_paquetes_talonario;
                            //Sumo los datos
                              $suma_importe_boletos_sistema+=$importe_boletos_sistema;
                              $suma_importe_boletos_talonario+=$importe_boletos_talonario;
                              $suma_importe_paquetes_sistema+=$importe_paquetes_sistema;
                              $suma_importe_paquetes_talonario+=$importe_paquetes_talonario;
                              $suma_total+=$total;

                              $nombre_usuario=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM usuarios WHERE clave='$usuario'");
                            //Imprimo la tabla
                              echo "
                                <tr>
                                  <td>$fecha</td>
                                  <td>$punto_venta</td>
                                  <td>$nombre_usuario</td>
                                  <td>$ ".number_format($importe_boletos_sistema,2)."</td>
                                  <td>$ ".number_format($importe_boletos_talonario,2)."</td>
                                  <td>$ ".number_format($importe_paquetes_sistema,2)."</td>
                                  <td>$ ".number_format($importe_paquetes_talonario,2)."</td>
                                  <td>$ ".number_format($total,2)."</td>
                                </tr>
                              ";
                            //
                          }
                        //
                      }
                      //Imprimo la tabla
                        echo "
                          <tr>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td>$ ".number_format($suma_importe_boletos_sistema,2)."</td>
                            <td>$ ".number_format($suma_importe_boletos_talonario,2)."</td>
                            <td>$ ".number_format($suma_importe_paquetes_sistema,2)."</td>
                            <td>$ ".number_format($suma_importe_paquetes_talonario,2)."</td>
                            <td><strong>$ ".number_format($suma_total,2)."</strong></td>
                          </tr>
                        ";
                      //*/
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <!-- TABLA DE VENTA POR TAQUILLA -->
    </div>
  </div>
</div>
<script type="text/javascript">
  //Funcion para la tabla
    $(document).ready( function () {
      var table = $('#tabla_operador').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 Filas', '25 Filas', '50 Filas', 'Mostrar todo' ]
        ],
        buttons:{
          buttons:[
            { extend: 'pageLength', text:'CANTIDAD' },
            { extend: 'excel', text:'DESCARGAR EXCEL' },
            { extend: 'print', text:'IMPRIMIR' },
            { extend: 'copy', text:'COPIAR' },
            { extend: 'colvis', text:'COLUMNAS' },
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
    $(document).ready( function () {
      var table = $('#tabla_taquilla').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 Filas', '25 Filas', '50 Filas', 'Mostrar todo' ]
        ],
        buttons:{
          buttons:[
            { extend: 'pageLength', text:'CANTIDAD' },
            { extend: 'excel', text:'DESCARGAR EXCEL' },
            { extend: 'print', text:'IMPRIMIR' },
            { extend: 'copy', text:'COPIAR' },
            { extend: 'colvis', text:'COLUMNAS' },
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
</script>