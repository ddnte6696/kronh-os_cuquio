<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<table class="table table-bordered table-sm table-hover" id="tabla">
  <thead>
    <tr>
      <th>#</th>
      <th>DESTINO</th>
      <th>PUNTO DE PASO</th>
    </tr>
  </thead>
  <tbody>
    <?php
      //Defino la sentencia a ejecutar
        $sentencia="SELECT * FROM destinos";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
              //Almaceno los datos opbtenidos en sus variables correspondientes
                $destino=$tabla['destino'];
                $id=$tabla['id'];
              //Identifico si es un punto de paso o no
                if($tabla['punto']==false){
                  $punto='NO';
                }ELSE{
                  $punto='SI';
                }
              //Realizo la impresion de los datos
                echo "
                  <tr>
                    <td>$id</td>
                    <td>$destino</td>
                    <td>$punto</td>
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
<script type="text/javascript">
  //Funcio para la tabla
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
              filename: 'DESTINOS REGISTRADOS',
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
  //
</script>