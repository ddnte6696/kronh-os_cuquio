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
    $texto="PAQUETERIAS DE LA TAQUILLA $taquilla DEL $fecha_inicio AL $fecha_fin";
    $agregado=" origen='$taquilla' and ";
?>
<div class="card text-center">
  <div class="card-header">
    <h5><?php echo $texto; ?></h5>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-striped" id="tabla">
      <thead>
        <tr>
          <th>FOLIO</th>
          <th>ESTADO</th>
          <th>P.VENTA</th>
          <th>F.VENTA</th>
          <th>H.VENTA</th>
          <th>ORIGEN</th>
          <th>ENVIA</th>
          <th>DESTINO</th>
          <th>RECIBE</th>
          <th>CANTIDAD</th>
          <th>PESO (Kg)</th>
          <th>DESCRIPCION</th>
          <th>OBSERVACION</th>
          <th>IMPORTE</th>
          <th>F.ENVIO</th>
          <th>CORRIDA</th>
          <th>F.RECEPCION</th>
          <th>F.ENTREGA</th>
          <th>ENTREGADO A</th>
          <th>VENDE</th>
          <th>F.LIDACION</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Busco las rutas que van hacia ese punto
          $sentencia="
            SELECT 
              *
            FROM
              paquete
            WHERE 
              $agregado
              $filtro between '$fecha_inicio' and '$fecha_fin'
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
                    $direccion_envia=$tabla['direccion_envia'];
                    $nombre_envia=$tabla['nombre_envia'];
                    $telefono_envia=$tabla['telefono_envia'];
                    $destino=$tabla['destino'];
                    $direccion_recibe=$tabla['direccion_recibe'];
                    $nombre_recibe=$tabla['nombre_recibe'];
                    $telefono_recibe=$tabla['telefono_recibe'];

                    if ($tabla['recibe']==Null) {
                      $recibe="NO IDENTIFICADO";
                    }else{
                      $recibe=$tabla['recibe'];
                    }
                    
                    $precio=$tabla['precio'];
                    $cantidad=$tabla['cantidad'];
                    $peso=$tabla['peso'];
                    $descripcion=$tabla['descripcion'];
                    $observacion=$tabla['observacion'];
                    $total=$tabla['total'];
                    $fecha=$tabla['fecha'];

                    
                    if($tabla['fecha_envio']==Null){
                      $fecha_envio="NO DEFINIDA";
                    }else{
                      $fecha_envio=$tabla['fecha_envio']; 
                    }

                    
                    if($tabla['fecha_recepcion']==Null){
                      $fecha_recepcion="NO DEFINIDA";
                    }else{
                      $fecha_recepcion=$tabla['fecha_recepcion']; 
                    }

                    
                    if($tabla['fecha_entrega']==Null){
                      $fecha_entrega="NO DEFINIDA";
                    }else{
                      $fecha_entrega=$tabla['fecha_entrega']; 
                    }

                    $hora=$tabla['hora'];
                    $referencia=$tabla['referencia'];
                    if ($tabla['corrida']==0) {
                      $corrida="NO IDENTIFICADA";
                    }else{
                      $corrida=busca_existencia("SELECT CONCAT(hora,' - ',servicio) as exist from corrida where id=".$tabla['corrida']);
                    }
                    $usuario_vende=campo_limpiado(busca_existencia("SELECT CONCAT(nombre,' ',apellido) as exist from usuarios where clave='".$tabla['usuario_vende']."'"),0,1);
                    if ($tabla['usuario_asigna']==Null) {
                      $usuario_asigna="NO IDENTIFICADO";
                    }else{
                      $usuario_asigna=campo_limpiado(busca_existencia("SELECT CONCAT(nombre,' ',apellido) as exist from usuarios where clave='".$tabla['usuario_asigna']."'"),0,1);
                    }
                    
                    if($tabla['fecha_liquidacion']==Null){
                      $fecha_liquidacion="NO DEFINIDA";
                    }else{
                      $fecha_liquidacion=$tabla['fecha_liquidacion']; 
                    }
                    switch ($tabla['estado']) {
                      case '1':
                        $estado="VENTA REALIZADA";
                        $color=" <tr>";
                      break;
                      case '2':
                        $estado="EN RUTA";
                        $color=" <tr class='table-warning'>";
                      break;
                      case '3':
                        $estado="RECIBIDA";
                        $color=" <tr class='table-primary'>";
                      break;
                      case '4':
                        $estado="ENTREGADA";
                        $color=" <tr class='table-success'>";
                      break;
                      case '5':
                        $estado="CANCELADA";
                        $color=" <tr class='table-danger'>";
                      break;
                    }
                    if ($tabla['liquidada']==true) {
                      $estado=$estado." - LIQUIDADA";
                    }

                    $cadena="$id||$fecha||$origen||$nombre_envia||$destino||$nombre_recibe";

                    $datos="'$cadena||".campo_limpiado($cadena."||".$tabla['estado'],1,0)."'";

                  //Imprimo la tabla
                    echo "
                      $color
                        <td>$id</td>
                        <td><strong>$estado</strong></td>
                        <td>$punto_venta</td>
                        <td>$fecha</td>
                        <td>$hora</td>
                        <td>$origen</td>
                        <td>$nombre_envia - $telefono_envia</td>
                        <td>$destino</td>
                         <td>$nombre_recibe - $telefono_recibe</td>
                        <td>$cantidad</td>
                        <td>$peso</td>
                        <td>$descripcion</td>
                        <td>$observacion</td>
                        <td>$total</td>
                        <td>$fecha_envio</td>
                        <td>$corrida</td>
                        <td>$fecha_recepcion</td>
                        <td>$fecha_entrega</td>
                        <td>$recibe</td>
                        <td>$usuario_vende</td>
                        <td>$fecha_liquidacion</td>
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
<script type="text/javascript">
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