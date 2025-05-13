<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2);
  //Obtengo los datos enviados por el formulario
    $id_liquidacion=campo_limpiado($_POST['dato'],2);
  //Defino la sentencia de busqueda
    $sentencia="SELECT * FROM corte where id=$id_liquidacion";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          $importe_boletos_sistema=number_format($tabla['importe_boletos_sistema']);
          $importe_boletos_talonario=number_format($tabla['importe_boletos_talonario']);
          $importe_paquetes_sistema=number_format($tabla['importe_paquetes_sistema']);
          $importe_paquetes_talonario=number_format($tabla['importe_paquetes_talonario']);
          $total=number_format(($tabla['importe_boletos_sistema']+$tabla['importe_boletos_talonario']+$tabla['importe_paquetes_sistema']+$tabla['importe_paquetes_talonario']));
        }
      //
    }
  //
?>
<div class="card">
  <div class="card-header"><h5>TOTALES</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-striped text-center" >
      <tbody>
        <?php
          //Imprimo los resultados
            echo "
            <tr>
              <th>BOLETOS DE SISTEMA</th>
              <td>$ $importe_boletos_sistema</td>
            </tr>
            <tr>
              <th>BOLETOS MANUALES</th>
              <td>$ $importe_boletos_talonario</td>
            </tr>
            <tr>
              <th>PAQUETERIAS DE SISTEMA</th>
              <td>$ $importe_paquetes_sistema</td>
            </tr>
            <tr>
              <th>PAQUTERIAS MANUALES</th>
              <td>$ $importe_paquetes_talonario</td>
            </tr>
            <tr>
              <th>TOTAL</th>
              <th>$ $total</th>
            </tr>
            ";
        ?>
      </tbody>
    </table>
  </div>
  <div id="respuesta_anticipo">
  </div>
</div>