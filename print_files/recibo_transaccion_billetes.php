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
    $referencia=campo_limpiado($_GET['ref'],2);
  //Obtengo los datos de la transaccion
    $sentencia="SELECT * FROM billetes where referencia='$referencia'";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Datos del registro
            $fecha_registro=campo_limpiado(transforma_fecha($tabla['fecha_registro'],1," de "),0,1);
            $hora_registro=campo_limpiado(transforma_hora($tabla['hora_registro'],"12"),0,1);
            $usuario_registra=$tabla['usuario'];
            $taquilla=$tabla['taquilla'];
             $tipo=tipo_transaccion_billetes($tabla['tipo']);
            $t1000=$tabla['t1000'];
            $t500=$tabla['t500'];
            $t200=$tabla['t200'];
            $t100=$tabla['t100'];
            $t50=$tabla['t50'];
            $t20=$tabla['t20'];
            $t10=$tabla['t10'];
            $t5=$tabla['t5'];
            $t2=$tabla['t2'];
            $t1=$tabla['t1'];
            $t050=$tabla['t050'];
            $total=$tabla['total'];
          //Obtengo los datos de usuario que lo registro
            $nombre_usuario=busca_existencia("SELECT CONCAT(nombre,' ',apellido) as exist from usuarios where clave='$usuario_registra';");
          //
        }
      //
    }
  //
?>
<style type="text/css">
  @media print {
    @page { size: auto; }
  }
</style>
<table class="text-center">
  <tr><th colspan="3"><h4>RECIBO DE <?php echo $tipo ?></h4></th></tr>
  <tr>
    <th><strong>FECHA DE REGISTRO:</strong></th>
    <td  colspan="2"><?php echo $fecha_registro ?></td>
  </tr>
  <tr>
    <th><strong>HORA DE REGISTRO:</strong></th>
    <td  colspan="2"><?php echo $hora_registro ?></td>
  </tr>
  <tr>
    <th><strong>PERSONA:</strong></th>
    <td  colspan="2"><?php echo $nombre_usuario ?></td>
  </tr>
  <tr>
    <th><strong>TAQUILLA:</strong></th>
    <td  colspan="2"><?php echo $taquilla ?></td>
  </tr>
  <tr><th>Cantidad</th><th>Denominacion</th><th>Subtotal</th></tr>
  <tr>
    <td><strong><?php echo $t1000 ?></strong></td>
    <th>$ 1,000</th>
    <td><strong>$ <?php echo number_format(($t1000*1000)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t500 ?></strong></td>
    <th>$ 500</th>
    <td><strong>$ <?php echo number_format(($t500*500)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t200 ?></strong></td>
    <th>$ 200</th>
    <td><strong>$ <?php echo number_format(($t200*200)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t100 ?></strong></td>
    <th>$ 100</th>
    <td><strong>$ <?php echo number_format(($t100*100)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t50 ?></strong></td>
    <th>$ 50</th>
    <td><strong>$ <?php echo number_format(($t50*50)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t20 ?></strong></td>
    <th>$ 20</th>
    <td><strong>$ <?php echo number_format(($t20*20)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t10 ?></strong></td>
    <th>$ 10</th>
    <td><strong>$ <?php echo number_format(($t10*10)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t5 ?></strong></td>
    <th>$ 5</th>
    <td><strong>$ <?php echo number_format(($t5*5)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t2 ?></strong></td>
    <th>$ 2</th>
    <td><strong>$ <?php echo number_format(($t2*2)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t1 ?></strong></td>
    <th>$ 1</th>
    <td><strong>$ <?php echo number_format($t1) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t050 ?></strong></td>
    <th>$ 0.50</th>
    <td><strong>$ <?php echo number_format(($t050*0.50),2) ?></strong></td>
  </tr>
  <tr>
    <th>TOTAL</th>
    <td colspan="2"><strong>$ <?php echo number_format($total,2) ?></strong></td>
  </tr>
  <tr>
    <th><strong>IMPRIME:</strong></th>
    <td colspan="2"><?php echo $nombre_imprime ?> </td>
  </tr>
  <tr>
    <th><strong>FECHA DE IMPRESION:</strong></th>
    <td colspan="2"><?php echo campo_limpiado(transforma_fecha(ahora(1),1," de "),0,1) ?></td>
  </tr>
  <tr>
    <th><strong>HORA DE IMPRESION:</strong></th>
    <td colspan="2"><?php echo campo_limpiado(transforma_hora(ahora(2),"12"),0,1) ?></td>
  </tr>
  <tr><th colspan="3">OMNIBUS YAHUALICA</th></tr>
  <tr><th>SANTANDER:</th><th colspan="2">65-50890936-8</th></tr>
  <tr><th>BBVA:</th><th colspan="2">0111426281</th></tr>
<tr>
  <td colspan="3" >
    <hr media="print" style="border-bottom: 6px solid;">
  </td>
</tr>
  <tr><th colspan="3"><h4>RECIBO DE <?php echo $tipo ?></h4></th></tr>
  <tr>
    <th><strong>FECHA DE REGISTRO:</strong></th>
    <td  colspan="2"><?php echo $fecha_registro ?></td>
  </tr>
  <tr>
    <th><strong>HORA DE REGISTRO:</strong></th>
    <td  colspan="2"><?php echo $hora_registro ?></td>
  </tr>
  <tr>
    <th><strong>PERSONA:</strong></th>
    <td  colspan="2"><?php echo $nombre_usuario ?></td>
  </tr>
  <tr>
    <th><strong>TAQUILLA:</strong></th>
    <td  colspan="2"><?php echo $taquilla ?></td>
  </tr>
  <tr><th>Cantidad</th><th>Denominacion</th><th>Subtotal</th></tr>
  <tr>
    <td><strong><?php echo $t1000 ?></strong></td>
    <th>$ 1,000</th>
    <td><strong>$ <?php echo number_format(($t1000*1000)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t500 ?></strong></td>
    <th>$ 500</th>
    <td><strong>$ <?php echo number_format(($t500*500)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t200 ?></strong></td>
    <th>$ 200</th>
    <td><strong>$ <?php echo number_format(($t200*200)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t100 ?></strong></td>
    <th>$ 100</th>
    <td><strong>$ <?php echo number_format(($t100*100)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t50 ?></strong></td>
    <th>$ 50</th>
    <td><strong>$ <?php echo number_format(($t50*50)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t20 ?></strong></td>
    <th>$ 20</th>
    <td><strong>$ <?php echo number_format(($t20*20)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t10 ?></strong></td>
    <th>$ 10</th>
    <td><strong>$ <?php echo number_format(($t10*10)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t5 ?></strong></td>
    <th>$ 5</th>
    <td><strong>$ <?php echo number_format(($t5*5)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t2 ?></strong></td>
    <th>$ 2</th>
    <td><strong>$ <?php echo number_format(($t2*2)) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t1 ?></strong></td>
    <th>$ 1</th>
    <td><strong>$ <?php echo number_format($t1) ?></strong></td>
  </tr>
  <tr>
    <td><strong><?php echo $t050 ?></strong></td>
    <th>$ 0.50</th>
    <td><strong>$ <?php echo number_format(($t050*0.50),2) ?></strong></td>
  </tr>
  <tr>
    <th>TOTAL</th>
    <td colspan="2"><strong>$ <?php echo number_format($total,2) ?></strong></td>
  </tr>
  <tr>
    <th><strong>IMPRIME:</strong></th>
    <td colspan="2"><?php echo $nombre_imprime ?> </td>
  </tr>
  <tr>
    <th><strong>FECHA DE IMPRESION:</strong></th>
    <td colspan="2"><?php echo campo_limpiado(transforma_fecha(ahora(1),1," de "),0,1) ?></td>
  </tr>
  <tr>
    <th><strong>HORA DE IMPRESION:</strong></th>
    <td colspan="2"><?php echo campo_limpiado(transforma_hora(ahora(2),"12"),0,1) ?></td>
  </tr>
  <tr><th colspan="3">OMNIBUS YAHUALICA</th></tr>
  <tr><th>SANTANDER:</th><th colspan="2">65-50890936-8</th></tr>
  <tr><th>BBVA:</th><th colspan="2">0111426281</th></tr>
</table>
<script type="text/javascript">window.onload = function() { window.print(); }</script>