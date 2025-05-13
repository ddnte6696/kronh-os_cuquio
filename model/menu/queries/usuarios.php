<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
  $opcion=campo_limpiado($_POST['opcion'],0,0);
  if ($opcion==1) {
    echo "
      <script type='text/javascript'>
        function editar_permisos_menu(opcion){
          var url='model/menu/forms/editar_permisos_menu.php'
            $.ajax({
              type: 'POST',
              url:url,
              data:{ dato:opcion },
              beforeSend: function(){
                $('#edicion').html('<div class=\"spinner-border\"></div>');
              },
              success: function(datos){ $('#edicion').html(datos); }
            });
          }
      </script>
    ";
  }else{
    echo "
      <script type='text/javascript'>
        function editar_permisos_menu(opcion){
          var url='model/menu/forms/editar_pagina_inicial.php'
            $.ajax({
              type: 'POST',
              url:url,
              data:{ dato:opcion },
              beforeSend: function(){
                $('#edicion').html('<div class=\"spinner-border\"></div>');
              },
              success: function(datos){ $('#edicion').html(datos); }
            });
          }
      </script>
    ";
  }
?>
<div class="card">
  <div class="card-header"><h5>USUARIOS REGISTRADOS</h5></div>
  <div class="card-body">
    <div id="respuesta"></div>
    <table class="table table-bordered table-sm table-striped table-hover" id="tabla_colaborador">
      <thead>
        <tr>
          <th>#</th>
          <th>NOMBRE</th>
          <th>PUESTO</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia="
              SELECT
                a.id,
                a.clave,
                a.nombre,
                a.password,
                a.apellido,
                a.telefono,
                a.f_ingreso,
                b.puesto
              FROM usuarios as a
                LEFT JOIN puestos as b on a.puesto=b.id
              where a.visual=1
            ";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Creo una variable especial
                    $dato="'".campo_limpiado($tabla['id'],1,0)."'";
                  //Impresion de los datos
                    echo "
                      <tr>
                        <td>".$tabla['id']."</td>
                        <td>".$tabla['nombre']." ".$tabla['apellido']."</td>
                        <td>".$tabla['puesto']."</td>
                        <td><a onclick=\"editar_permisos_menu($dato)\" class=\"btn btn-primary text-light\"><i class=\"fas fa-edit\" style=\"font-size:24px\"></i></a></td>
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
  <div class="card-footer"><div id="edicion"></div></div>
</div>
<script type="text/javascript">
  //Funcion para la tabla_colaborador
    $(document).ready( function () {
      var table = $('#tabla_colaborador').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
            { extend: 'excel', text:'DESCARGAR EXCEL' },
            { extend: 'print', text:'IMPRIMIR' },{ extend: 'copy', text:'COPIAR' },
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