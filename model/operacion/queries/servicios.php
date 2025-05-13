<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div id="edicion"></div>
<table class="table table-bordered table-sm table-hover" id="tabla">
  <thead>
    <tr>
      <th>#</th>
      <th>RUTA</th>
      <th>IDENTIFICADOR</th>
      <th>INICIO DE RUTA</th>
      <th>PUNTO DE PASO</th>
      <th>FIN DE RUTA</th>
      <th>HORA</th>
      <th>ESTADO</th>
    </tr>
  </thead>
  <tbody>
    <?php
      //Defino la sentencia a ejecutar
        $sentencia="SELECT * FROM servicio";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
              //Almaceno los datos opbtenidos en sus variables correspondientes
                $id=$tabla['id'];
                $nombre_ruta=$tabla['nombre_ruta'];
                $identificador=$tabla['identificador'];
                $punto_inicial=$tabla['punto_inicial'];
                $punto_origen=$tabla['punto_origen'];
                $punto_final=$tabla['punto_final'];
                $hora=$tabla['hora'];
                $dato="'".campo_limpiado($id,1,0)."'";
              //Identifico si es un punto de paso o no
                if($tabla['estatus']==false){
                  $estado='<strong>INHABILITADO</strong>';
                  $boton="
                    <a onclick=\"habilita_servicio($dato)\" class=\"btn btn-sm btn-success text-light\">
                      <i class=\"fas fa-check\" style=\"font-size:20px\"></i>
                    </a>
                  ";
                }ELSE{
                  $estado='<strong>HABILITADO</strong>';
                  $boton="
                    <a onclick=\"inhabilita_servicio($dato)\" class=\"btn btn-sm btn-danger text-light\">
                      <i class=\"fas fa-ban\" style=\"font-size:20px\"></i>
                    </a>
                  ";
                }
              //Realizo la impresion de los datos
                echo "
                  <tr>
                    <td>$id</td>
                    <td>$nombre_ruta</td>
                    <td>$identificador</td>
                    <td>$punto_inicial</td>
                    <td>$punto_origen</td>
                    <td>$punto_final</td>
                    <td>$hora</td>
                    <td>$estado $boton</td>
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
  //Función para obtener los campos y el modelo en base a la marca y el tipo
    function habilita_servicio(datos){
      //Indico la dirección del formulario que quiero llamar
        var url="model/operacion/update/habilita_servicio.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          data:{id:datos},
          beforeSend: function(){$("#edicion").html("<div class='spinner-border'></div>");},
          success: function(datos){$('#edicion').html(datos);}
        });
      //
    }
  //Función para obtener los campos y el modelo en base a la marca y el tipo
    function inhabilita_servicio(datos){
      //Indico la dirección del formulario que quiero llamar
        var url="model/operacion/update/inhabilita_servicio.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          data:{id:datos},
          beforeSend: function(){$("#edicion").html("<div class='spinner-border'></div>");},
          success: function(datos){$('#edicion').html(datos);}
        });
      //
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
              filename: 'CORRIDAS RESGITRADAS',
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