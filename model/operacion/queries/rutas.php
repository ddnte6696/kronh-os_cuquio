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
      <th>RUTA</th>
      <th>IDENTIFICADOR</th>
      <th>INICIO DE RUTA</th>
      <th>PUNTO DE PASO</th>
      <th>FIN DE RUTA</th>
      <th>CANTIDAD DE DESTINOS</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
      //Defino la sentencia a ejecutar
        $sentencia="SELECT DISTINCT nombre_ruta,identificador,punto_inicial,punto_origen,punto_final FROM ruta";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
              //Almaceno los datos opbtenidos en sus variables correspondientes
                $nombre_ruta=$tabla['nombre_ruta'];
                $identificador=$tabla['identificador'];
                $punto_inicial=$tabla['punto_inicial'];
                $punto_origen=$tabla['punto_origen'];
                $punto_final=$tabla['punto_final'];
              //Defino una variable para enviar datos
               $dato="'".campo_limpiado(("$nombre_ruta||$identificador||$punto_origen"),1,0)."'";
              //Obtengo la cantidad de destinos en la ruta
                $cuenta=busca_existencia("SELECT count(id) as exist from ruta where identificador='$identificador' and punto_origen='$punto_origen';");
              //Realizo la impresion de los datos
                echo "
                  <tr>
                    <td>$nombre_ruta</td>
                    <td>$identificador</td>
                    <td>$punto_inicial</td>
                    <td>$punto_origen</td>
                    <td>$punto_final</td>
                    <td>$cuenta</td>
                    <td>
                      <a onclick=\"editar($dato)\" class=\"btn btn-primary text-light\" data-toggle=\"tooltip\"><i class='fas fa-edit'  style='font-size:24px'></i></a>
                    </td>
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
  //Funcion para editar los datos del usuario
    function editar(opcion){
      $.ajax({
        type: "POST", 
        url: "model/operacion/forms/editar_ruta.php",
        data: {form_act: opcion},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border text-center'></div>");
        },
        success: function(respuesta){
          $("#expediente").html(respuesta);
        }
      });
      return false;
    }
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
              filename: 'RUTAS CREADAS',
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