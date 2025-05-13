<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
?>
<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo datos de la sesion
    $taquilla_actual=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Identifico si existe ya un corte registrado
    $sentencia="SELECT count(id) AS exist FROM corte where punto_venta='$taquilla_actual' and usuario='$clave' and fecha='".ahora(1)."';";
    $retorno=busca_existencia($sentencia);
  //Identifico si ya hay un corte de este usuario registrado
    if ($retorno>0) {
      echo "<script>venta_boletos();</script>";

    }
  //Obtengo los datos enviados por el formulario
    $fecha=campo_limpiado($_POST['fecha'],0,0);
    $dat_origen=explode("||",campo_limpiado($_POST['origen'],2,0));
    $id_origen=campo_limpiado($dat_origen[0],0,0);
    $origen=campo_limpiado($dat_origen[1],0,0);
    $dat_destino=explode("||",campo_limpiado($_POST['destino'],2,0));
    $id_destino=campo_limpiado($dat_destino[0],0,0);
    $destino=campo_limpiado($dat_destino[1],0,0);
    $precio=campo_limpiado($dat_destino[2],0,0);
  //Creeo una referencia para identificacion de los boletos
    $referencia="$id_origen-".referencia_temporal()."-$id_destino";
  //creo un arreglo con algunos campos y les asigno valores primarios
    $datos =array (
      'referencia'=> campo_limpiado($referencia,1,0),
      'fecha'=>campo_limpiado($fecha,1,0),
      'taquilla'=>campo_limpiado($origen,1,0),
      'origen'=>campo_limpiado($origen,1,0),
      'id_destino'=>campo_limpiado($id_destino,1,0),
      'nombre_destino'=>campo_limpiado($destino,1,0),
      'precio_destino'=>campo_limpiado($precio,1,0),
      'id_punto_inicial'=>null,
      'punto_inicial'=>null,
      'id_corrida'=>null,
      'corrida'=>null,
      'hora'=>null,
      'boletos'=>null
    );
    $datos_arreglo =array (
      'referencia'=> campo_limpiado($referencia,1,0),
      'fecha'=>campo_limpiado($fecha,1,0),
      'taquilla'=>campo_limpiado($origen,1,0),
      'origen'=>campo_limpiado($origen,1,0),
      'id_destino'=>campo_limpiado($id_destino,1,0),
      'nombre_destino'=>campo_limpiado($destino,1,0),
      'precio_destino'=>campo_limpiado($precio,1,0),
      'id_punto_inicial'=>null,
      'punto_inicial'=>null,
      'id_corrida'=>null,
      'corrida'=>null,
      'hora'=>null,
      'boletos'=>null
    );
  //Agrego el arreglo a una variable de sesion
    $_SESSION['oyg_vb']=$datos;
  //Reviso si existen corridas registradas en ese dia para esa taquilla
    $sentencia="SELECT count(id) as exist FROM corrida WHERE fecha='$fecha' and punto_origen='$origen';";
    $existencia=busca_existencia($sentencia);
    if ($existencia==0) { genera_corridas($fecha,$origen); }
  //
?>
<div id="response">
  <div class="card text-center">
    <div class="card-header">
      <h5>SALIDAS DESDE <strong><?php echo $origen; ?></strong> HACIA <strong><?php echo $destino; ?></strong></h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm table-striped" id="tabla">
        <thead>
          <tr>
            <th>HORA</th>
            <th></th>
            <th>CORRIDA</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            //Busco las rutas que van hacia ese punto
            $sentencia_corrida="SELECT DISTINCT identificador,id_punto_inicial,punto_inicial From ruta where id_destino=$id_destino and punto_origen='$origen';";
            //Ejecuto la sentencia y almaceno lo obtenido en una variable
              $resultado_sentencia_corrida=retorna_datos_sistema($sentencia_corrida);
            //Identifico si el reultado no es vacio
              if ($resultado_sentencia_corrida['rowCount'] > 0) {
                //Almaceno los datos obtenidos
                  $resultado_corrida = $resultado_sentencia_corrida['data'];
                // Recorrer los datos y llenar las filas
                  foreach ($resultado_corrida as $tabla_corrida) {
                    //Creeo las variables del identificador
                      $identificador=$tabla_corrida['identificador'];
                      $id_punto_inicial=$tabla_corrida['id_punto_inicial'];
                      $punto_inicial=$tabla_corrida['punto_inicial'];
                    //Realizo la busqueda de las servicios activos
                      $sentencia_servicio="SELECT * from corrida where identificador='$identificador' and fecha='$fecha' and punto_origen='$origen' and estatus=1";
                    //Ejecuto la sentencia y almaceno lo obtenido en una variable
                      $resultado_sentencia_servicio=retorna_datos_sistema($sentencia_servicio);
                    //Identifico si el reultado no es vacio
                      if ($resultado_sentencia_servicio['rowCount'] > 0) {
                        //Almaceno los datos obtenidos
                          $resultado_servicio = $resultado_sentencia_servicio['data'];
                        // Recorrer los datos y llenar las filas
                          foreach ($resultado_servicio as $tabla_servicio) {
                            //Almceno los datos en una variable
                            $id_corrida=$tabla_servicio['id'];
                            $servicio=$tabla_servicio['servicio'];
                            $hora=$tabla_servicio['hora'];
                            $hora_transformada=campo_limpiado(transforma_hora($hora,"12"),0,1);
                            //Creeo un concatenado con los datos a enviar informacion
                            $dato="'".campo_limpiado("$id_corrida||$servicio||$id_punto_inicial||$punto_inicial||$hora",1,0)."'";
                            //Imprimo los datos
                            echo "
                              <tr>
                                <td>$hora</td>
                                <td>$hora_transformada</td>
                                <td>$servicio</td>
                                <td>
                                  <a onclick=\"selecciona_corrida($dato)\" class='btn btn-success btn-block text-light'>
                                    <strong>SELECCIONAR</strong>
                                  </a>
                                </td>
                              </tr>
                            ";
                          }
                        //
                      }
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
  //funcion para confirmar la llegada del paquete
   function selecciona_corrida(opcion){
        var url="model/venta/queries/selecciona_asientos.php"
        $.ajax({
            type: "POST",
            url:url,
            data:{
              datos:opcion
            },
            success: function(datos){$('#response').html(datos);}
        });
    }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla').DataTable( {
        responsive: true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
          { extend: 'excel', text:'Descargar Excel' },
          { extend: 'print', text:'Imprimir' },{ extend: 'copy', text:'Copiar' },
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