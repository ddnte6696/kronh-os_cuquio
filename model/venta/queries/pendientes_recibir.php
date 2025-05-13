<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
    $taquilla=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
  //
?>
<script type="text/javascript">
  //Función para registrar una nueva cuenta bancaria
    function faltantes_asignar_datos(){
      //Indico la dirección del formulario que quiero llamar
        var url="model/venta/queries/operador_unidad_modal.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          success: function(datos){$('#faltantes_asignar_datos').html(datos);}
        });
      //
    }
</script>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5>PENDIENTES DE RECIBIR</h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>FOLIO</th>
            <th>FECHA DE VENTA</th>
            <th>FECHA DE ENVIO</th>
            <th>ORIGEN</th>
            <th>ENVIA</th>
            <th>DESTINO</th>
            <th>RECIBE</th>
            <th>CORRIDA</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
            $sentencia="
              SELECT
                paquete.id,
                paquete.fecha,
                paquete.origen,
                paquete.nombre_envia,
                paquete.destino,
                paquete.nombre_recibe,
                paquete.corrida,
                corrida.fecha as fecha_corrida,
                corrida.hora,
                corrida.servicio
              FROM paquete as paquete
               join corrida as corrida on corrida.id=paquete.corrida
              wHERE paquete.estado=2 and paquete.destino='$taquilla';
            ";
            //Ejecuto la sentencia y almaceno lo obtenido en una variable
              $resultado_sentencia=retorna_datos_sistema($sentencia);
            //Identifico si el reultado no es vacio
              if ($resultado_sentencia['rowCount'] > 0) {
                //Almaceno los datos obtenidos
                  $resultado = $resultado_sentencia['data'];
                // Recorrer los datos y llenar las filas
                  foreach ($resultado as $tabla) {
                    //Creeo las variables del identificador
                      $id=$tabla['id'];
                      $fecha=$tabla['fecha'];
                      $origen=$tabla['origen'];
                      $nombre_envia=$tabla['nombre_envia'];
                      $destino=$tabla['destino'];
                      $nombre_recibe=$tabla['nombre_recibe'];
                      $corrida=$tabla['corrida'];
                      $fecha_corrida=$tabla['fecha_corrida'];
                      $hora=$tabla['hora'];
                      $servicio=$tabla['servicio'];
                      $cadena="$id||$fecha||$origen||$nombre_envia||$destino||$nombre_recibe||$corrida||$fecha_corrida||$hora||$servicio";
                      $datos="'$cadena||".campo_limpiado($cadena,1,0)."'";
                    //Imprimo la tabla
                      echo "
                        <tr>
                          <td>$id</td>
                          <td>$fecha</td>
                          <td>$fecha_corrida</td>
                          <td>$origen</td>
                          <td>$nombre_envia</td>
                          <td>$destino</td>
                          <td>$nombre_recibe</td>
                          <td>$hora - $servicio</td>
                          <td>
                            <a onclick=\"confirmar_llegada_modal($datos)\" class=\"btn btn-sm btn-success text-light\"><i class='fas fa-check' style='font-size:24px'></i></a>
                            
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
  </div>
</div>
<script type="text/javascript">
   //funcion para llamada del modal
    function confirmar_llegada_modal(datos){
      //separo los datos
      var datos_paqueteria=datos.split("||");
      //las asigno a sus respectivas variables
      var id_cll=datos_paqueteria[0];
      var fecha_cll=datos_paqueteria[1];
      var origen_cll=datos_paqueteria[2];
      var nombre_envia_cll=datos_paqueteria[3];
      var destino_cll=datos_paqueteria[4];
      var nombre_recibe_cll=datos_paqueteria[5];
      var corrida_cll=datos_paqueteria[6];
      var fecha_corrida_cll=datos_paqueteria[7];
      var servicio_cll=datos_paqueteria[8]+' - '+datos_paqueteria[9];
      var datos_cll=datos_paqueteria[10];
      //Mando a llamar el modal
      $('#confirmar_llegada_paqueteria').modal('show');
      //seteo los datos en su respectivo input
      $('#id_cll').val(id_cll);
      $('#fecha_cll').val(fecha_cll);
      $('#origen_cll').val(origen_cll);
      $('#nombre_envia_cll').val(nombre_envia_cll);
      $('#destino_cll').val(destino_cll);
      $('#nombre_recibe_cll').val(nombre_recibe_cll);
      $('#corrida_cll').val(corrida_cll);
      $('#fecha_corrida_cll').val(fecha_corrida_cll);
      $('#servicio_cll').val(servicio_cll);
      $('#datos_cll').val(datos_cll);
    }
  //Funcion para asignacion de unidad y operador a la corrida
    function confirmar_llegada_paqueteria(){
      $.ajax({
        type: "POST",
        url: "model/venta/update/confirmar_llegada_paqueteria.php",
        data: $("#frm_confirmar_llegada_paqueteria").serialize(),
        beforeSend: function(){ $("#response").html("<div class='spinner-border'></div>"); },
        success: function(data){ $("#response").html(data); },
      });
      return false;
    }
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
  //
</script>