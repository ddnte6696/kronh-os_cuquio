<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluyen los archivos de conexión
  include_once A_CONNECTION;
?>
<div class="card">
  <div class="card-header">
    <h5>OPERADORES INHABILITADOS</h5>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla">
      <thead>
        <tr>
          <th>CLAVE</th>
          <th>NOMBRE</th>
          <th>TELEFONO</th>
          <th>EMPRESA</th>
          <th>DIVISION</th>
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
                a.apellido,
                a.telefono,
                a.f_ingreso,
                b.empresa,
                c.division
              FROM operadores as a
                JOIN empresas as b on a.empresa=b.id
                JOIN division as c on a.division=c.id
              where a.visual=0
            ";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Defino el dato de edicion
                    $dato="'".campo_limpiado($tabla['id'],1,0)."'";
                  // Impresion de los datos
                    echo "
                      <tr>
                        <td>".$tabla['clave']."</td>
                        <td>".$tabla['nombre']." ".$tabla['apellido']."</td>
                        <td>".$tabla['telefono']."</td>
                        <td>".$tabla['empresa']."</td>
                        <td>".$tabla['division']."</td>
                        <td>
                          <a onclick=\"editar($dato)\" class=\"btn btn-sm btn-primary text-light\" data-toggle=\"tooltip\" title=\"Editar este colaborador\"><i class='fas fa-user-edit'  style='font-size:24px'></i></a>
                          <a onclick=\"activar($dato)\" class=\"btn btn-success text-light\"><i class='fas fa-user'  style='font-size:24px'></i></a>
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
  </div>
  <div class="card-footer"><div id="expediente"></div></div>
</div>
<script>
  //Funcion para marcar la baja del operador
    function activar(opcion){
      $.ajax({
        type: "POST", 
        url: "model/operador/update/activar.php",
        data: {form_act: opcion},
        beforeSend: function(){
          $("#respuesta_control_operador").html("<div class='spinner-border text-center'></div>");
        },
        success: function(respuesta){
          $("#respuesta_control_operador").html(respuesta);
        }
      });
      return false;
    }
  //Funcion para editar los datos del operador
    function editar(opcion){
      $.ajax({
        type: "POST", 
        url: "model/operador/exp/operador.php",
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