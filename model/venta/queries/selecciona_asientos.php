<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
?>
<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo los datos enviados por el formulario
    $dto=explode("||",campo_limpiado($_POST['datos'],2,0));
    $id_corrida=$dto[0];
    $corrida=$dto[1];
    $id_punto_inicial=$dto[2];
    $punto_inicial=$dto[3];
    $hora=$dto[4];
  //Defino los boletos en vacio y la corrida
    $_SESSION['oyg_vb']['id_corrida']=campo_limpiado($id_corrida,1,0);
    $_SESSION['oyg_vb']['corrida']=campo_limpiado($corrida,1,0);
    $_SESSION['oyg_vb']['hora']=campo_limpiado($hora,1,0);
    $_SESSION['oyg_vb']['id_punto_inicial']=campo_limpiado($id_punto_inicial,1,0);
    $_SESSION['oyg_vb']['punto_inicial']=campo_limpiado($punto_inicial,1,0);
  //empiezo a asignar datos en los campos
    $referencia=campo_limpiado($_SESSION['oyg_vb']['referencia'],2,0);
    $fecha=campo_limpiado($_SESSION['oyg_vb']['fecha'],2,0);
    $taquilla=campo_limpiado($_SESSION['oyg_vb']['taquilla'],2,0);
    $origen=campo_limpiado($_SESSION['oyg_vb']['origen'],2,0);
    $id_destino=campo_limpiado($_SESSION['oyg_vb']['id_destino'],2,0);
    $nombre_destino=campo_limpiado($_SESSION['oyg_vb']['nombre_destino'],2,0);
    $precio_destino=campo_limpiado($_SESSION['oyg_vb']['precio_destino'],2,0);
  //saco los asientos que estan ocupados en ese momento
    $sentencia="SELECT asiento from boleto where corrida='$corrida' and origen='$taquilla' and f_corrida='$fecha' and hora_corrida='$hora'";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          echo "
            <script>
              $('#ta".$tabla['asiento']."').removeClass('btn-primary');
              $('#ta".$tabla['asiento']."').addClass('btn-danger disabled');
            </script>
          ";
        }
      //
    }
  //
?>
<script type="text/javascript">
  function muestra_pasajeros(opcion){
    //Defino y asigno las variables
      var puntero=$("#origen").val();
    //Indico la dirección del formulario que quiero llamar
      var url="model/venta/queries/asientos.php"
    //inicio el traspaso de los datos
      $.ajax({
        type: "POST",
        url:url,
        data:{
          opcion:opcion
        },
        success: function(datos){$('#pasajeros').html(datos);}
      });
    //
  }
  //window.onload(muestra_pasajeros());
</script>
<div class="card text-center">
  <div class="card-header"><h5><?php echo "SALIDA DESDE <strong>$origen</strong> HACIA <strong>$nombre_destino</strong> EN LA CORRIDA <strong>$corrida</strong> A LAS <strong>".campo_limpiado(transforma_hora($hora),0,1)."</strong>"; ?></h5></div>
  <div class="card-body">
    <div class="row">
      <!--vista de los asientos-->
      <div class="col-md-8">
        <label><strong>PASAJEROS</strong></label>
        <div id="pasajeros"></div>
        <div id="campo_boleto"></div>
      </div>
      <!-- vista de los asientod del autobus-->
      <div class="col-md-4 text-center">
        <table class="table table-sm">
          <thead>
            <tr>
              <th colspan="2">PASAJEROS REGISTRADOS</th>
            </tr>
            <tr>
              <th>TIPO</th>
              <th>CANTIDAD</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //Busco los asientos por tipo
                $sentencia="SELECT count(tipo) as cuenta, tipo from boleto where corrida='$corrida' and origen='$taquilla' and f_corrida='$fecha' and hora_corrida='$hora' group by tipo";
              //Ejecuto la sentencia y almaceno lo obtenido en una variable
                $resultado_sentencia=retorna_datos_sistema($sentencia);
              //Identifico si el reultado no es vacio
                if ($resultado_sentencia['rowCount'] > 0) {
                  //Almaceno los datos obtenidos
                    $resultado = $resultado_sentencia['data'];
                  // Recorrer los datos y llenar las filas
                    foreach ($resultado as $tabla) {
                      echo "
                        <tr>
                          <td>".$tabla['tipo']."</td>
                          <td>".$tabla['cuenta']."</td>
                        </tr>
                      ";
                    }
                  //
                }
              //
            ?>
          </tbody>
        </table>

        <a class="btn btn-sm btn-primary text-light"><strong>Disponible</strong></a>
        <a class="btn btn-sm btn-danger"><strong>No disponible</strong></a>
        <a class="btn btn-sm btn-warning"><strong>Seleccionado</strong></a>
        <div>
          <table class="table table-sm">
            <tr>
              <td colspan="2"><a class="btn btn-sm btn-block  btn-danger disabled">CHOFER</a></td>
              <td>
                <i class='fas fa-angle-double-down'></i>
                <i class='fas fa-angle-double-left'></i><br>
                <i class='fas fa-angle-double-down'></i>
                <i class='fas fa-angle-double-right'></i>
              </td>
              <td>
                <i class='fas fa-angle-double-left'></i><br>
                <i class='fas fa-angle-double-right'>
                </td>
              <td>
                <i class='fas fa-angle-double-left'></i><br>
                <i class='fas fa-angle-double-right'>
                </td>
            </tr>
            <tr>
              <th colspan="6">
                <a class="btn btn-sm btn-block  btn-block btn-success text-light"onclick="agregar_pasajero_modal('DE PIE');">VENDER DE PIE</a>
              </th>
            </tr>
            <?php
              $j=0;
              for ($i=0; $i <10 ; $i++) { ?>
                <tr>
                  <td><a class="btn btn-sm btn-block  btn-block btn-primary text-light" id="ta<?php echo $j+1 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+1,0,0) ?>');"><?php echo $j+1 ?></a></td>
                  <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+2 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+2,0,0) ?>');"><?php echo $j+2 ?></a></td>
                  <td>
                    <i class='fas fa-angle-double-down'></i>
                    <i class='fas fa-angle-double-up'></i>
                  </td>
                  <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+3 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+3,0,0) ?>');"><?php echo $j+3 ?></a></td>
                  <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+4 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+4,0,0) ?>');"><?php echo $j+4 ?></a></td>
                </tr><?php
                $j=$j+4;
              }
            ?>
            <tr>
              <td><a class="btn btn-sm btn-block  btn-primary text-light"id="ta<?php echo $j+1 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+1,0,0) ?>');"><?php echo $j+1 ?></a></td>
              <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+2 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+2,0,0) ?>');"><?php echo $j+2 ?></a></td>
              <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+3 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+3,0,0) ?>');"><?php echo $j+3 ?></a></td>
              <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+4 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+4,0,0) ?>');"><?php echo $j+4 ?></a></td>
              <td><a class="btn btn-sm btn-block  btn-primary text-light" id="ta<?php echo $j+5 ?>" onclick="agregar_pasajero_modal('<?php echo campo_limpiado($j+5,0,0) ?>');"><?php echo $j+5 ?></a></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div id="ver_impresion_boleto"></div>
  </div>
</div>
<?php include_once A_MODEL."venta/modal/pasajero.php" ?>
<script type="text/javascript">
   //funcion para llamada del modal
    function agregar_pasajero_modal(datos){
      //Mando a llamar el modal
      $('#pasajero').modal('show');
      //seteo los datos en su respectivo input
      $('#id_asiento').val(datos);
      $('#asiento').val(datos);
      $('#nombre').val('');
    }
  //Funcion para asignacion de la corrida
    function agregar_pasajero(){
      $.ajax({
        type: "POST",
        url: "model/venta/update/ingresar_asiento.php",
        data: $("#frm_asientos").serialize(),
        beforeSend: function(){$("#pasajeros").html("<div class='spinner-border'></div>");},
        success: function(data){$("#pasajeros").html(data);},
      });
      return false;
    }
  //
</script>