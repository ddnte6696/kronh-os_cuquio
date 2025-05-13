<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave de quien realiza la liquidacion
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo la fecha de trabajo del operador
    $fecha_trabajo=campo_limpiado($_POST['fecha'],0,0);
  //
?>
<script type="text/javascript">
  
</script>
<div class="card">
  <div class="card-text" id="mensajes"></div>
  <div class="card-body">
    <table class="table table-bordered table-sm text-sm table-striped" id="tabla_liquidaciones_registradas">
      <thead>
        <tr>
          <th>#</th>
          <th>UNIDAD</th>
          <th>OPERADOR</th>
          <th>BOLETOS DE SISTEMA</th>
          <th>BOLETOS DE TALONARIO</th>
          <th>PAQUETERIAS DE SISTEMA</th>
          <th>PAQUETERIAS DE TALONARIO</th>
          <th>TALONARIOS</th>
          <th>COMISION</th>
          <th>ANTICIPO</th>
          <th>ESTADO</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia de busqueda
            $sentencia="SELECT * FROM liq_op_rl where fecha='$fecha_trabajo'";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  $id=$tabla['id'];
                  $unidad=$tabla['unidad'];
                  $dto_unidad=busca_existencia("SELECT numero AS exist FROM unidades WHERE id=$unidad");
                  $operador=$tabla['operador'];
                  $dto_operador=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM operadores WHERE clave='$operador'");
                  $boletos_sistema=number_format(($tabla['boletos_sistema']),2);
                  $boletos_talonario=number_format(($tabla['boletos_talonario']),2);
                  $paqueterias_sistema=number_format(($tabla['paqueterias_sistema']),2);
                  $paqueterias_talonario=number_format(($tabla['paqueterias_talonario']),2);
                  $talonario=number_format(($tabla['talonario']),2);
                  $comision=number_format(($tabla['comision']),2);
                  $anticipo=number_format(($tabla['anticipo']),2);
                  $dato="'".campo_limpiado("$unidad||$dto_unidad||$fecha_trabajo||$operador",1,0)."'";
                  $dato2="'".campo_limpiado($id,1,0)."'";
                  switch ($tabla['estado']) {
                    case '1':
                      $color="<tr class='table-primary'>";
                      $estado="<strong>ABIERTA</strong>";
                      $boton="
                        <a onclick=\"editar_liquidacion($dato)\" class=\"btn btn-primary text-light\" data-toggle=\"tooltip\" title='EDITAR'><i class='fas fa-edit'  style='font-size:24px'></i></a>

                        <a onclick=\"eliminar_liquidacion($dato2)\" class=\"btn btn-danger text-light\" data-toggle=\"tooltip\" title='ELIMINAR'><i class='fas fa-trash'  style='font-size:24px'></i></a>

                        <a onclick=\"cerrar_liquidacion($dato2)\" class=\"btn btn-success text-light\" data-toggle=\"tooltip\" title='CERRAR'><i class='fas fa-check'  style='font-size:24px'></i></a>
                      ";
                      break;
                    
                    case '2':
                      $color="<tr class='table-success'>";
                      $estado="<strong>CERRADA</strong>";
                      $boton="
                        <a onclick=\"eliminar_liquidacion($dato2)\" class=\"btn btn-danger text-light\" data-toggle=\"tooltip\" title='ELIMINAR'><i class='fas fa-trash'  style='font-size:24px'></i></a>

                        <a onclick=\"abrir_liquidacion($dato2)\" class=\"btn btn-danger text-light\" data-toggle=\"tooltip\" title='ABRIR'><i class='fas fa-ban'  style='font-size:24px'></i></a>
                      ";
                    break;
                  }
                  echo "
                    $color
                      <td>$id</td>
                      <td>$dto_unidad</td>
                      <td>[$operador] - $dto_operador</td>
                      <td>$boletos_sistema</td>
                      <td>$boletos_talonario</td>
                      <td>$paqueterias_sistema</td>
                      <td>$paqueterias_talonario</td>
                      <td>$talonario</td>
                      <td>$comision</td>
                      <td>$anticipo</td>
                      <td>$estado</td>
                      <td>$boton</td>
                    </tr>
                  ";
                }
              //
            }
          //
        ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    <div id="expediente"></div>
  </div>
</div>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function editar_liquidacion(dato){
      var url="model/liquidacion/forms/paginas_liq_op_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Funcion para cargar el formulario de liquidacion especifico
    function cerrar_liquidacion(dato){
      var url="model/liquidacion/update/cerrar_liquidacion.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Funcion para cargar el formulario de liquidacion especifico
    function abrir_liquidacion(dato){
      var url="model/liquidacion/update/abrir_liquidacion.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Funcion para cargar el formulario de liquidacion especifico
    function eliminar_liquidacion(dato){
      var url="model/liquidacion/update/eliminar_liquidacion.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_liquidaciones_registradas').DataTable( {
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
  //
</script>