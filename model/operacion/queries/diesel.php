<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  $fecha_inicial=campo_limpiado($_POST['fecha_inicial'],0,0);
  $fecha_final=campo_limpiado($_POST['fecha_final'],0,0);
?>
<script type="text/javascript">
  //Funcion para editar la carga de diesel
    function expediente(opcion){
      var url="model/operacion/exp/diesel.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{id:opcion},
        success: function(datos){$('#expediente').html(datos);}
      });
    }
  //Funcion para eliminar la carga de diesel
    function eliminar(opcion){
      var url="model/operacion/delete/carga_diesel.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{id:opcion},
        success: function(datos){$('#expediente').html(datos);}
      });
    }
</script>
<div id="expediente"></div>
<div class="card">
  <div class="card-header">
    <h5>Cargas de diesel <?php echo "del ". transforma_fecha($fecha_inicial)." al ".transforma_fecha($fecha_final) ?></h5>
  </div>
  <div id="response"></div>
  
  <div class="card-body">
    <table class="table table-bordered table-sm" id="tabla">
      <thead>
        <tr>
          <th>#</th>
          <th>FECHA</th>
          <th>UNIDAD</th>
          <th>OPERADOR</th>
          <th>KILOMETRAJE</th>

          <th>FOLIO CONTADO</th>
          <th>LITROS CONTADO</th>
          <th>IMPORTE DIESEL CONTADO</th>
          <th>ADBLUE CONTADO</th>
          <th>IMPORTE ADBLUE CONTADO</th>

          <th>FOLIO CREDITO</th>
          <th>LITROS CREDITO</th>
          <th>IMPORTE DIESEL CREDITO</th>
          <th>ADBLUE CREDITO</th>
          <th>IMPORTE ADBLUE CREDITO</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia="
              SELECT * FROM cargas_diesel WHERE fecha BETWEEN '$fecha_inicial' and '$fecha_final'
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
                    $adblue_contado=number_format($tabla['adblue_contado'],2);
                    $adblue_credito=number_format($tabla['adblue_credito'],2);
                    $diesel_contado=number_format($tabla['diesel_contado'],2);
                    $diesel_credito=number_format($tabla['diesel_credito'],2);
                    $fecha=$tabla['fecha'];
                    $fecha_registra=$tabla['fecha_registra'];
                    $folio_contado=$tabla['folio_contado'];
                    $folio_credito=$tabla['folio_credito'];
                    $id=$tabla['id'];
                    $importe_adblue_contado=number_format($tabla['importe_adblue_contado'],2);
                    $importe_adblue_credito=number_format($tabla['importe_adblue_credito'],2);
                    $importe_diesel_contado=number_format($tabla['importe_diesel_contado'],2);
                    $importe_diesel_credito=number_format($tabla['importe_diesel_credito'],2);
                    $kilometraje=number_format($tabla['kilometraje'],2);
                    $operador=$tabla['operador'];
                    $unidad=$tabla['unidad'];
                    $usuario_registra=$tabla['usuario_registra'];
                  //Obtengo el nombre del operador
                    $nombre_operador=busca_existencia("SELECT CONCAT(clave,' ',nombre,' ',apellido) as exist from operadores where clave='$operador';");
                    $numero_unidad=busca_existencia("SELECT numero as exist from unidades where id=$unidad;");
                  //Creeo un dato especial del destino
                    $dato=campo_limpiado($id,1)."||$id";
                  //Imprimo el campo
                    echo "
                      <tr>
                        <td>$id</td>
                        <td>$fecha</td>
                        <td>$numero_unidad</td>
                        <td>$nombre_operador</td>
                        <td>$kilometraje</td>
                        
                        <td>$folio_contado</td>
                        <td>$diesel_contado</td>
                        <td>$importe_diesel_contado</td>
                        <td>$adblue_contado</td>
                        <td>$importe_adblue_contado</td>
                        
                        <td>$folio_credito</td>
                        <td>$diesel_credito</td>
                        <td>$importe_diesel_credito</td>
                        <td>$adblue_credito</td>
                        <td>$importe_adblue_credito</td>
                        <td>
                          <a onclick=\"eliminar_carga_diesel_modal('$dato')\" class=\"btn btn-danger\" data-toggle=\"tooltip\" title=\"Eliminar esta carga\">
                            <i class='fas fa-trash' style='font-size:24px'></i>
                          </a>
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
<?php
  include_once A_MODEL.'operacion/modal/eliminar_carga_diesel.php';
?>
<script>
  //llamada del modal para eliminar unidades
    function eliminar_carga_diesel_modal(datos){
      d=datos.split('||');
      $('#eliminar_carga_diesel').modal('show');
      $('#id_carga_elimina').val(d[0]);
      $('#folio_carga_elimina').val(d[1]);
    }
  //Funcion para eliminar_carga_diesel unidades
    function eliminar_carga_diesel(){
      $.ajax({
        type: "POST",
        url: "model/operacion/update/eliminar_carga_diesel.php",
        data: $("#frm_eliminar_carga_diesel").serialize(),
        beforeSend: function(){
        $("#respuesta_eliminar_carga_diesel").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_eliminar_carga_diesel").html(data);
        },
      });
      return false;
    }
  //Funcion para la tabla_archivos
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
              filename: 'Cargas de diesel <?php echo "del ". transforma_fecha($fecha_inicial)." al ".transforma_fecha($fecha_final)?>',
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
        console.log( count +' columna(s) ocultas' );
      } );
    } );
  //
</script>
