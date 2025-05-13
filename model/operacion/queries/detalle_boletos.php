<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo los campos enviados por el formulario
    $taquilla=campo_limpiado($_POST['origen'],2,0);
    $fecha_inicio=campo_limpiado($_POST['fecha_inicio'],0,0);
    $fecha_fin=campo_limpiado($_POST['fecha_fin'],0,0);
    $filtro=campo_limpiado($_POST['filtro'],2,0);
    if ($taquilla=='TODAS') {
      $texto="BOLETOS DE TODAS LAS TAQUILLAS DEL $fecha_inicio AL $fecha_fin";
      $agregado=Null;
    }else{
      $texto="BOLETOS DE LA TAQUILLA $taquilla DEL $fecha_inicio AL $fecha_fin";
      $agregado=" punto_venta='$taquilla' and ";
    }
  //
?>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5><?php echo $texto; ?></h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>N°</th>
            <th>FECHA DE VENTA</th>
            <th>PUNTO DE VENTA</th>
            <th>ORIGEN</th>
            <th>DESTINO</th>
            <th>TIPO</th>
            <th>PRECIO</th>
            <th>PASAJERO</th>
            <th>ASIENTO</th>
            <th>FECHA DE VIAJE</th>
            <th>HORA DE VIAJE</th>
            <th>CORRIDA</th>
            <th>VENDEDOR</th>
            <th>REFERENCIA</th>
            <th>COMENTARIOS</th>
            <th>ESTADO</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
              $sentencia="
                SELECT 
                  *
                FROM
                  boleto
                WHERE 
                  $agregado
                  $filtro between '$fecha_inicio' and '$fecha_fin'
                ;
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
                      $punto_venta=$tabla['punto_venta'];
                      $origen=$tabla['origen'];
                      $destino=$tabla['destino'];
                      $tipo=$tabla['tipo'];
                      $precio=$tabla['precio'];
                      $pasajero=$tabla['pasajero'];
                      $asiento=$tabla['asiento'];
                      $f_corrida=$tabla['f_corrida'];
                      $corrida=$tabla['corrida'];
                      $hora_corrida=$tabla['hora_corrida'];
                      $f_venta=$tabla['f_venta'];
                      $h_venta=$tabla['h_venta'];
                      $referencia=$tabla['referencia'];
                      $estado=$tabla['estado'];
                      $comentarios=$tabla['motivo'];
                    //Reviso el estado de los comentarios
                      if ($tabla['motivo']=='') {
                        $comentarios='SIN COMENTARIOS';
                      }
                    //Creeo un dato especifico para pasar
                      $dato=campo_limpiado($id,1)."||$id";
                    //Verifico si el usuario que vende es web o si es de taquilla
                      if ($tabla['usuario']=='WEB') {
                        $vendedor="PLATAFORMA WEB";
                      }else{
                        //Busco el nombre de la persona que vendio
                          //Defino la sentencia para buscar el nombre del operador
                          $sentencia="
                            SELECT 
                            CONCAT(nombre,' ',apellido) AS exist 
                            FROM 
                              usuarios 
                            WHERE 
                              clave='".$tabla['usuario']."'
                            ;
                          ";
                        //Obtengo el nombre del operador y lo alamceno en su variable correspodiente
                          $vendedor=campo_limpiado(busca_existencia($sentencia),0,1);
                        //
                      }
                    //Verifico el estado del boleto
                      switch ($tabla['estado']) {
                        case '1':
                          $estado_texto="PENDIENTE DE PAGO";
                          $color="<tr class='table-primary'>";
                          $boton="
                            <a onclick=\"cancelar_boleto_modal('$dato')\" class=\"btn btn-danger\" data-toggle=\"tooltip\" title=\"Cancelar este boleto\">
                              <i class='fas fa-trash' style='font-size:24px'></i>
                            </a>
                            <a onclick=\"aprobar_boleto_modal('$dato')\" class=\"btn btn-success\" data-toggle=\"tooltip\" title=\"Aprobar este boleto\">
                              <i class='fas fa-check' style='font-size:24px'></i>
                            </a>
                          ";
                        break;
                        case '2':
                          $estado_texto="VENDIDO";
                          $color="<tr class='table-success'>";
                          $boton="
                            <a onclick=\"cancelar_boleto_modal('$dato')\" class=\"btn btn-danger\" data-toggle=\"tooltip\" title=\"Cancelar este boleto\">
                              <i class='fas fa-trash' style='font-size:24px'></i>
                            </a>
                          ";
                        break;
                        case '3':
                          $estado_texto="CANCELADO<br>";
                          $color="<tr class='table-danger'>";
                          $boton="
                            <a onclick=\"aprobar_boleto_modal('$dato')\" class=\"btn btn-success\" data-toggle=\"tooltip\" title=\"Aprobar este boleto\">
                              <i class='fas fa-check' style='font-size:24px'></i>
                            </a>
                          ";
                        break;
                      }
                    //Imprimo los datos de la tabla
                      echo "
                        $color
                          <td>$id</td>
                          <td>$f_venta</td>
                          <td>$punto_venta</td>
                          <td>$origen</td>
                          <td>$destino</td>
                          <td>$tipo</td>
                          <td>$precio</td>
                          <td>$pasajero</td>
                          <td>$asiento</td>
                          <td>$f_corrida</td>
                          <td>$hora_corrida</td>
                          <td>$corrida</td>
                          <td>$vendedor</td>
                          <td>$referencia</td>
                          <td>$comentarios</td>
                          <td>$estado_texto</td>
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
  </div>
</div>
<?php
  include_once A_MODEL.'operacion/modal/cancelar_boleto.php';
  include_once A_MODEL.'operacion/modal/aprobar_boleto.php';
?>
<script type="text/javascript">
  //llamada del modal para eliminar unidades
    function cancelar_boleto_modal(datos){
      d=datos.split('||');
      $('#cancelar_boleto').modal('show');
      $('#id_boleto_cancela').val(d[0]);
      $('#folio_boleto_cancela').val(d[1]);
    }
  //Funcion para cancelar_boleto unidades
    function cancelar_boleto(){
      $.ajax({
        type: "POST",
        url: "model/operacion/update/cancelar_boleto.php",
        data: $("#frm_cancelar_boleto").serialize(),
        beforeSend: function(){
        $("#respuesta_cancelar_boleto").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_cancelar_boleto").html(data);
        },
      });
      return false;
    }
  //llamada del modal para eliminar unidades
    function aprobar_boleto_modal(datos){
      d=datos.split('||');
      $('#aprobar_boleto').modal('show');
      $('#id_boleto_aproba').val(d[0]);
      $('#folio_boleto_aproba').val(d[1]);
    }
  //Funcion para aprobar_boleto unidades
    function aprobar_boleto(){
      $.ajax({
        type: "POST",
        url: "model/operacion/update/aprobar_boleto.php",
        data: $("#frm_aprobar_boleto").serialize(),
        beforeSend: function(){
        $("#respuesta_aprobar_boleto").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_aprobar_boleto").html(data);
        },
      });
      return false;
    }
  //Funcio para la tabla
    $(document).ready(function() {
      var table = $('#tabla').DataTable({
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType": "full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [10, 25, 50, -1],
          ['10 Filas', '25 Filas', '50 Filas', 'Mostrar todo']
        ],
        buttons: {
          buttons: [
            {
              extend: 'pageLength',
              text: 'CANTIDAD'
            },
            {
              extend: 'excelHtml5',
              text: 'DESCARGAR EXCEL',
              filename: 'CORRIDAS RESGITRADAS',
              orientation: 'landscape'
            },
            {
              extend: 'print',
              text: 'IMPRIMIR'
            },
            {
              extend: 'copy',
              text: 'COPIAR'
            },
            {
              extend: 'colvis',
              text: 'COLUMNAS'
            },
          ],
        },
      });
      table.on('responsive-resize', function(e, datatable, columns) {
        var count = columns.reduce(function(a, b) {
          return b === false ? a + 1 : a;
        }, 0);
        console.log(count + ' column(s) are hidden');
      });
    });
  //
</script>