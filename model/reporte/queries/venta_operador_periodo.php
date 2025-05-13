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
?>
<div class="card text-center">
  <div class="card-header">
    <h5>REPORTE DE VENTA DEL <?php echo campo_limpiado(transforma_fecha($fecha_inicio,1," DE "),0,1)." AL ".campo_limpiado(transforma_fecha($fecha_fin,1," DE "),0,1); ?></h5>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-sm text-sm table-striped" id="tabla">
      <thead>
        <tr>
          <th>OPERADOR</th>
          <th>ABORDO</th>
          <th>PAQUETES</th>
          <th>PASAJEROS</th>
          <th>COMISION</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia de busqueda
            $sentencia="
              SELECT
                operador, 
                SUM(boletos_sistema) AS boletos_sistema, 
                SUM(boletos_talonario) AS boletos_talonario, 
                SUM(paqueterias_sistema) AS paqueterias_sistema, 
                SUM(paqueterias_talonario) AS paqueterias_talonario, 
                SUM(comision) AS comision,
                SUM(talonario) AS talonario
              FROM liq_op_rl 
              WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
              Group by operador
            ";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Datos del operador
                    $operador=$tabla['operador'];
                    $dto_operador=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM operadores WHERE clave='$operador'");
                  //Datos de venta
                    $abordo=$tabla['talonario'];
                    $pasajeros=$tabla['boletos_sistema']+$tabla['boletos_talonario'];
                    $paquetes=$tabla['paqueterias_sistema']+$tabla['paqueterias_talonario'];
                    $comision=$tabla['comision'];
                  //Imprimo la tabla
                    echo "
                      <tr>
                        <td>$operador - $dto_operador</td>
                        <td>$ ".number_format($abordo,2)."</td>
                        <td>$ ".number_format($paquetes,2)."</td>
                        <td>$ ".number_format($pasajeros,2)."</td>
                        <td>$ ".number_format($comision,2)."</td>
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
      var table = $('#tabla').DataTable( {
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