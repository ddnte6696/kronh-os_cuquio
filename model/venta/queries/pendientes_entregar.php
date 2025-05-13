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
      <h5>PENDIENTES DE ENTREGAR</h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>FOLIO</th>
            <th>FECHA</th>
            <th>ORIGEN</th>
            <th>ENVIA</th>
            <th>DESTINO</th>
            <th>RECIBE</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
            $sentencia="SELECT * FROM paquete WHERE estado=3 AND destino='$taquilla'";
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
                      $cadena="$id||$fecha||$origen||$nombre_envia||$destino||$nombre_recibe";
                      $datos="'$cadena||".campo_limpiado($cadena,1,0)."'";
                    //Imprimo la tabla
                      echo "
                        <tr>
                          <td>$id</td>
                          <td>$fecha</td>
                          <td>$origen</td>
                          <td>$nombre_envia</td>
                          <td>$destino</td>
                          <td>$nombre_recibe</td>
                          <td>
                            <a onclick=\"marcar_entrega_modal($datos)\" class=\"btn btn-sm btn-success text-light\"><i class='fas fa-check' style='font-size:24px'></i></a>
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
    function marcar_entrega_modal(datos){
      //separo los datos
      var datos_paqueteria=datos.split("||");
      //las asigno a sus respectivas variables
      var id_mcp=datos_paqueteria[0];
      var fecha_mcp=datos_paqueteria[1];
      var origen_mcp=datos_paqueteria[2];
      var nombre_envia_mcp=datos_paqueteria[3];
      var destino_mcp=datos_paqueteria[4];
      var nombre_recibe_mcp=datos_paqueteria[5];
      var datos_mcp=datos_paqueteria[6];
      //Mando a llamar el modal
      $('#marcar_entrega_paqueteria').modal('show');
      //seteo los datos en su respectivo input
      $('#id_mcp').val(id_mcp);
      $('#fecha_mcp').val(fecha_mcp);
      $('#origen_mcp').val(origen_mcp);
      $('#nombre_envia_mcp').val(nombre_envia_mcp);
      $('#destino_mcp').val(destino_mcp);
      $('#nombre_recibe_mcp').val(nombre_recibe_mcp);
      $('#datos_mcp').val(datos_mcp);
      
    }
  //Funcion para asignacion de unidad y operador a la corrida
    function marcar_entrega_paqueteria(){
      $.ajax({
        type: "POST",
        url: "model/venta/update/marcar_entrega_paqueteria.php",
        data: $("#frm_marcar_entrega_paqueteria").serialize(),
        beforeSend: function(){
          $("#response").html("<div class='spinner-border'></div>"); },
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
  //Funcion para imprimir el div de los boletos
    function imprimirDiv(nombreDiv) {
      var contenido = document.getElementById(nombreDiv).innerHTML;
      var contenidoOriginal = document.body.innerHTML;
      document.body.innerHTML = contenido;
      window.print();
      document.body.innerHTML = contenidoOriginal;
      ver_corridas();
    }
  //
</script>