<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Inclucion de los archivos principales
    include A_VIEW."header.php";
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2);
    $nombre_imprime=campo_limpiado($_SESSION[UBI]['nombre'],2)." ".campo_limpiado($_SESSION[UBI]['apellido'],2);
  //Obtengo los datos enviados por el formulario
    $fecha=campo_limpiado($_GET['report'],2);
  //
?>
<style type="text/css">
  @media print {
    @page { size: auto; }
  }
</style>
<div class="card text-center">
  <div class="card-header">
    <h4>OMNIBUS YAHUALICA GUADALAJARA S.A. DE C.V.</h4>
    <h4>REPORTE DE LIQUIDACION DEL DIA <?php echo campo_limpiado(transforma_fecha($fecha,1," DE "),0,1); ?></h4>
  </div>
  <div class="card-body">
    <div class="row">
      <!-- TABLA DE OPERADOR -->
        <div class="col-lg-12" style="border: black 1px solid;">
          <h5>VENTA POR UNIDAD</h5>
          <table class="table table-bordered table-sm text-sm">
            <thead>
              <tr>
                <th>UNIDAD</th>
                <th>OPERADOR</th>
                <th>ABORDO</th>
                <th>PAQUETES</th>
                <th>PASAJEROS</th>
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
                  $sumatoria_pasajeros=Null;
                  $sumatoria_paquetes=Null;
                  $sumatoria_ventas_totales=Null;
                  $sumatoria_ventas_sin_iva=Null;
                  $sumatoria_iva=Null;
                  $sumatoria_importe_contado=Null;
                  $sumatoria_importe_credito=Null;
                  $sumatoria_litros_diesel=Null;
                  $sumatoria_comision=Null;
                  $sumatoria_remanente=Null;
                //Defino la sentencia de busqueda
                  $sentencia="SELECT * FROM liq_op_rl where fecha='$fecha'";
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
                          $pasajeros=$tabla['boletos_sistema']+$tabla['boletos_talonario'];
                          $paquetes=$tabla['paqueterias_sistema']+$tabla['paqueterias_talonario'];
                          $comision=$tabla['comision'];
                        //Calculos de venta
                          $ventas_totales=$abordo+$pasajeros+$paquetes;
                          $ventas_sin_iva=$ventas_totales/1.16;
                          $iva=$ventas_sin_iva*0.16;
                        //Datos de cargas de diesel
                          $importe_contado=busca_existencia("SELECT importe_diesel_contado AS exist FROM cargas_diesel WHERE fecha='$fecha' and unidad=$unidad and operador='$operador'");
                          if ($importe_contado=="") { $importe_contado=0; }
                          $importe_credito=busca_existencia("SELECT importe_diesel_credito AS exist FROM cargas_diesel WHERE fecha='$fecha' and unidad=$unidad and operador='$operador'");
                          if ($importe_credito=="") { $importe_credito=0; }
                          $litros_diesel=busca_existencia("SELECT SUM(diesel_contado+diesel_credito) AS exist FROM cargas_diesel WHERE fecha='$fecha' and unidad=$unidad and operador='$operador'");
                          if ($litros_diesel=="") { $litros_diesel=0; }
                        //Calculo del remanente
                          $remanente=$ventas_totales-($importe_contado+$importe_credito+$comision);
                        //Realizo los calculos de las sumatorias
                          $sumatoria_abordo+=$abordo;
                          $sumatoria_pasajeros+=$pasajeros;
                          $sumatoria_paquetes+=$paquetes;
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
                            <tr >
                              <td>$dto_unidad</td>
                              <td class='text-left'>[$operador] - $dto_operador</td>
                              <td>$ ".number_format($abordo,2)."</td>
                              <td>$ ".number_format($paquetes,2)."</td>
                              <td>$ ".number_format($pasajeros,2)."</td>
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
                    <td colspan='2' class='text-left'><strong>TOTAL DE LIQUIDACION</strong></td>
                    <td><strong>$ ".number_format($sumatoria_abordo,2)."</strong></td>
                    <td><strong>$ ".number_format($sumatoria_paquetes,2)."</strong></td>
                    <td><strong>$ ".number_format($sumatoria_pasajeros,2)."</strong></td>
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
       <!-- TABLA DE  COMISIONES -->
        <div class="col-lg-6" style="border: black 1px solid;">
          <h5>COMISIONES POR OPERADOR</h5>
          <table class="table table-bordered table-sm text-sm ">
            <thead>
              <th>ABORDO</th>
              <th>OPERADOR</th>
              <th>COMISION</th>
            </thead>
            <tbody>
              <?php
                //Defino una variable de sumatoria
                  $sumatoria_comision_2=Null;
                //Defino la sentencia de busqueda
                  $sentencia="SELECT * FROM liq_op_rl where fecha='$fecha' order by talonario desc";
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
                          $comision=$tabla['comision']-$tabla['anticipo'];
                        //Sumo la comision
                          $sumatoria_comision_2+=$comision;
                        //Imprimo la tabla
                          echo "
                            <tr>
                              <td>$ ".number_format($abordo,2)."</td>
                              <td>[$operador] - $dto_operador</td>
                              <td>$ ".number_format($comision,2)."</td>
                            </tr>
                          ";
                        //
                      }
                    //
                  }
                //Imprimo la tabla
                  echo "
                    <tr>
                      <td colspan='2'><strong>TOTAL COMISIONES</strong></td>
                      <td><strong>$ ".number_format($sumatoria_comision_2,2)."</strong></td>
                    </tr>
                  ";
                //
              ?>
            </tbody>
          </table>
        </div>
      <!-- TABLA DE VENTA POR TAQUILLA -->
        <div class="col-lg-6" style="border: black 1px solid;">
          <h5>VENTAS POR TAQUILLA</h5>
          <table class="table table-bordered table-sm text-sm ">
            <thead>
              <th>TAQUILLA</th>
              <th>SISTEMA</th>
              <th>MANUAL</th>
              <th>TOTAL</th>
            </thead>
            <tbody>
              <?php
                //Defino una variable de sumatoria
                  $suma_sistema=Null;
                  $suma_talonario=Null;
                  $suma_total=Null;
                //Defino la sentencia de busqueda
                  $sentencia="
                    SELECT 
                      a.punto_venta,
                      SUM(a.importe_boletos_sistema+a.importe_paquetes_sistema) as sistema,
                      SUM(a.importe_boletos_talonario+a.importe_paquetes_talonario) as talonario
                    FROM corte as a
                    join destinos as b on a.punto_venta=b.destino
                    where a.fecha='$fecha' group by a.punto_venta order by b.id asc
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
                          $punto_venta=$tabla['punto_venta'];
                          $sistema=$tabla['sistema'];
                          $talonario=$tabla['talonario'];
                          $total=$sistema+$talonario;
                        //Sumo los datos
                          $suma_sistema+=$sistema;
                          $suma_talonario+=$talonario;
                          $suma_total+=$total;
                        //Imprimo la tabla
                          echo "
                            <tr>
                              <td>$punto_venta</td>
                              <td>$ ".number_format($sistema,2)."</td>
                              <td>$ ".number_format($talonario,2)."</td>
                              <td>$ ".number_format($total,2)."</td>
                            </tr>
                          ";
                        //
                      }
                    //
                  }
                //Imprimo la tabla
                  //Imprimo la tabla
                    echo "
                      <tr>
                        <td><strong>TOTAL</strong></td>
                        <td><strong>$ ".number_format($suma_sistema,2)."</strong></td>
                        <td><strong>$ ".number_format($suma_talonario,2)."</strong></td>
                        <td><strong>$ ".number_format($suma_total,2)."</strong></td>
                      </tr>
                    ";
                  //
                //Datos para la tabla final
                  $diferencia=$suma_total-($sumatoria_pasajeros+$sumatoria_paquetes);

                  $favor_abordo=$sumatoria_abordo-($sumatoria_importe_contado+$sumatoria_comision);

                  $banco=$suma_total+$favor_abordo;
              ?>
              <tr>
                <th colspan="3">DIFERENCIA</th>
                <td><?php echo number_format($diferencia,2); ?></td>
              </tr><tr>
                <th colspan="3">DIESEL CREDITO</th>
                <td><?php echo number_format($sumatoria_importe_credito,2); ?></td>
              </tr><tr>
                <th colspan="3">SALDO A FAVOR VENTA A BORDO</th>
                <td><?php echo number_format($favor_abordo,2); ?></td>
              </tr><tr>
                <th colspan="3">TOTAL DEPOSITADO EN BANCOS</th>
                <td><?php echo number_format($banco,2); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
  </div>
  <br><br><br><br>
  <div class="card-footer">
    <div class="row" >
      <div class="col" style=" border: black 1px solid;"><strong>FIRMA LIQUIDACIONES</strong><br><?php echo $nombre_imprime ?></div>

      <div class="col" style=" border: black 1px solid;"><strong>FIRMA CONTABILIDAD</strong><br></div>

      <div class="col" style=" border: black 1px solid;"><strong>FIRMA COORDINADOR</strong><br><?php echo campo_limpiado("Lic.Erick Ricardo Trujillo Perez",0,1); ?></div>

      <div class="col" style=" border: black 1px solid;"><strong>FIRMA DIRECCION</strong><br><?php echo campo_limpiado("Ana Karen Padilla Mendes",0,1); ?></div></div>
    </div>
  </div>
</div>
<script type="text/javascript">window.onload = function() { window.print(); }</script>