<?php 
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo las datos del formulario
    $inicio=campo_limpiado($_POST['inicio'],0,0);
    $fin=campo_limpiado($_POST['fin'],0,0);
  //
?>
<div class="card">
  <div class="card-header"><h5>BITACORA DE EVENTOS <?php ECHO "(DEL $inicio AL $fin)" ?></h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla">
      <thead>
        <tr>
          <th>FECHA</th>
          <th>HORA</th>
          <th>USUARIO</th>
          <th>EVENTO</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia="
              SELECT 
                a.fecha,
                a.hora,
                a.accion,
                b.nombre,b.apellido
              FROM bitacora as a
              Join usuario as b on a.usuario=b.id
              where a.fecha between '$inicio' and '$fin'
            ";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla_bitacora) {
                  // Impresion de los datos
                    echo "
                      <tr>
                        <td>".ref_fecha($tabla_bitacora['fecha'])."</td>
                        <td>".$tabla_bitacora['hora']."</td>
                        <td>".$tabla_bitacora['nombre']." ".$tabla_bitacora['apellido']."</td>
                        <td>".$tabla_bitacora['accion']."</td>
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
  //Funcio para la tabla_bitacora
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
            { 
              extend: 'excelHtml5',
              text:'DESCARGAR EXCEL',
              filename: 'BITACORA DE EVENTOS <?php ECHO "(DEL $inicio AL $fina)" ?>',
              customizeData: function (data) {
                for (var i = 0; i < data.body.length; i++) {
                  for (var j = 0; j < data.body[i].length; j++) {
                    if (j === 16) {
                      data.body[i][j] = '\u200C' + data.body[i][j];
                    }
                  }
                }
              },
              orientation: 'landscape'
            },
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