<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';


  //Obtengo los datos enviados por el formulario
    $fecha=campo_limpiado($_POST['fecha'],0,0);
    $dat_origen=explode("||",campo_limpiado($_POST['origen'],2,0));
    $id_origen=campo_limpiado($dat_origen[0],0,0);
    $origen=campo_limpiado($dat_origen[1],0,0);
  //Mando a llamar la funcion para generar las corridas en caso de que no existan
    genera_corridas($fecha,$origen);
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
  //Funcion para imprimir la guia
    function imprimir_guia(opcion){
      var url="model/venta/queries/guia_corrida.php"
      var fecha=
      $.ajax({
        type: "POST",
        url:url,
        data:{datos:opcion},
        beforeSend: function(){
            $("#response").html("<div class='spinner-border'></div>");
          },
        success: function(datos){
          $("#response").html(datos);
        }
      });
    }
  //
</script>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5>CORRIDAS DESDE <strong><?php echo $origen; ?></strong></h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>HORA</th>
            <th></th>
            <th>CORRIDA</th>
            <th>OPERADOR</th>
            <th>UNIDAD</th>
            <th>ESTADO</th>
            <th>#</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
            $sentencia="SELECT * From corrida where punto_origen='$origen' and fecha='$fecha';";
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
                      $identificador=$tabla['identificador'];
                      $id_servicio=$tabla['id_servicio'];
                      $servicio=$tabla['servicio'];
                      $hora=$tabla['hora'];
                      $hora_transformada=campo_limpiado(transforma_hora($hora,"12"),0,1);
                      if ($tabla['operador']==Null) {
                        $operador="NO ASIGNADO";
                      }else{
                        //Defino la sentencia para buscar el nombre del operador
                          $sentencia="
                            SELECT 
                            CONCAT(nombre,' ',apellido) AS exist 
                            FROM 
                              operadores 
                            WHERE 
                              clave='".$tabla['operador']."'
                            ;
                          ";
                        //Obtengo el nombre del operador y lo alamceno en su variable correspodiente
                          $operador=$tabla['operador']." - ".busca_existencia($sentencia);
                        //
                      }
                      if ($tabla['unidad']==Null) {
                        $unidad="NO ASIGNADA";
                      }else{
                        //Defino la sentencia para buscar el nombre del operador
                          $sentencia="
                            SELECT 
                              numero AS exist 
                            FROM 
                              unidades 
                            WHERE 
                              id=".$tabla['unidad']."
                            ;
                          ";
                        //Obtengo el nombre del operador y lo alamceno en su variable correspodiente
                          $unidad=$tabla['unidad']." - ".busca_existencia($sentencia);
                        //
                      }
                      switch ($tabla['estatus']) {
                        case '1':
                          $estado_texto="ABIERTA";
                          $color="<tr class='table-primary'>";
                        break;
                        case '2':
                          $estado_texto="DESPACHADA";
                          $color="<tr class='table-success'>";
                        break;
                        case '3':
                          $estado_texto="CANCELADA";
                          $color="<tr class='table-danger'>";
                        break;
                      }
                      $datos="'".campo_limpiado($id,1,0)."||$origen||$servicio||".transforma_hora($hora)."||$fecha'";
                    //Imprimo la tabla
                      echo "
                        $color
                          <td>$hora</td>
                          <td>$hora_transformada</td>
                          <td>$servicio</td>
                          <td>$operador</td>
                          <td>$unidad</td>
                          <td><strong>$estado_texto</strong></td>
                          <td>$id</td>
                          <td>
                            <a onclick=\"asignar_datos_modal($datos)\" class='btn btn-primary text-light'>
                              <strong>
                                <i class='fas fa-user-plus'  style='font-size:24px'></i>
                                <i class='fas fa-bus'  style='font-size:24px'></i>
                              </strong>
                            </a>
                            <a onclick=\"cambiar_estado_modal($datos)\" class='btn btn-primary text-light'>
                              <strong>
                                <i class='fas fas fa-exchange-alt'  style='font-size:24px'></i>
                              </strong>
                            </a>
                            <a onclick=\"imprimir_guia($datos)\" class='btn btn-primary text-light'>
                              <strong>
                                <i class='fas fa-print'  style='font-size:24px'></i>
                              </strong>
                            </a>
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
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#unidad').select2();
        $('#operador').select2();
      });
    });
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