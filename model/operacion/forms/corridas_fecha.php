<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card text-center">
  <div class="card-header"><h3>CORRIDAS</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_corridas" id="frm_corridas">
      <div class="row text-center">
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA</strong></span>
            </div>
            <input type='date' name='fecha' name='fecha' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>ORIGEN</strong></span>
            </div>
            <select  name='origen' id="origen" class="custom-select"  style="color:black" required="">
              <?php
                //Defino algunos valores por defecto
                  echo "<option value='".campo_limpiado('TODAS',1,0)."'>TODAS LAS TAQUILLAS</option>";
                //Defino la sentencia a ejecutar
                  $sentencia="SELECT * FROM destinos where punto=true";
                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                  $resultado_sentencia=retorna_datos_sistema($sentencia);
                //Identifico si el reultado no es vacio
                  if ($resultado_sentencia['rowCount'] > 0) {
                    //Almaceno los datos obtenidos
                      $resultado = $resultado_sentencia['data'];
                    // Recorrer los datos y llenar las filas
                      foreach ($resultado as $tabla) {
                        //Creo una variable especial
                          $id_origen=$tabla['id'];
                          $origen=$tabla['destino'];
                        //Creeo un dato especial del destino
                          $dato=campo_limpiado($origen,1,0);
                        //Imprimo el campo
                          echo "<option value='$dato'>$origen</option>";
                        //
                      }
                    //
                  }
                //
              ?>
            </select>
          </div>
        </div>
      </div>
    </form>
    <input type="submit" value="VER CORRIDAS" class="btn btn-sm btn-primary btn-block" onclick="mostrar_corridas();">
  </div>
  <div class="card-footer"><div id="respuesta_corridas"></div></div>
</div>

<script>
  function mostrar_corridas(){
    $.ajax({
      type: "POST",
      url: "model/operacion/queries/corridas_taquilla.php",
      data: $("#frm_corridas").serialize(),
      beforeSend: function(){$("#respuesta_corridas").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_corridas").html(data);},
    });
    return false;
  }
  //funcion para llamada del modal
    function asignar_datos_modal(datos){
      //Ejecuto la funcion para que se carguen los datos actuales
        faltantes_asignar_datos();
      //separo los datos
      var datos_corrida=datos.split("||");
      //las asigno a sus respectivas variables
      var id_corrida_datos=datos_corrida[0];
      var origen_datos=datos_corrida[1];
      var servicio_datos=datos_corrida[2];
      var hora_datos=datos_corrida[3];
      //Mando a llamar el modal
      $('#asignar_datos').modal('show');
      //seteo los datos en su respectivo input
      $('#id_corrida_datos').val(id_corrida_datos);
      $('#origen_datos').val(origen_datos);
      $('#servicio_datos').val(servicio_datos);
      $('#hora_datos').val(hora_datos);
    }
  //Funcion para asignacion de unidad y operador a la corrida
    function asignar_datos(){
      $.ajax({
        type: "POST",
        url: "model/operacion/update/asignar_datos.php",
        data: $("#frm_asignar_datos").serialize(),
        beforeSend: function(){ $("#respuesta_asignar_datos").html("<div class='spinner-border'></div>"); },
        success: function(data){ $("#respuesta_asignar_datos").html(data); },
      });
      return false;
    }
  //funcion para llamada del modal
    function cambiar_estado_modal(datos){
      //separo los datos
      var datos_corrida=datos.split("||");
      //las asigno a sus respectivas variables
      var id_corrida_estado=datos_corrida[0];
      var origen_estado=datos_corrida[1];
      var servicio_estado=datos_corrida[2];
      var hora_estado=datos_corrida[3];
      //Mando a llamar el modal
      $('#cambiar_estado').modal('show');
      //seteo los datos en su respectivo input
      $('#id_corrida_estado').val(id_corrida_estado);
      $('#origen_estado').val(origen_estado);
      $('#servicio_estado').val(servicio_estado);
      $('#hora_estado').val(hora_estado);
    }
  //Funcion para cambiar el estado
    function cambiar_estado(){
      $.ajax({
        type: "POST",
        url: "model/operacion/update/cambiar_estado.php",
        data: $("#frm_cambiar_estado").serialize(),
        beforeSend: function(){
        $("#respuesta_cambiar_estado").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_cambiar_estado").html(data);
        },
      });
      return false;
    }
  
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#origen').select2();
      });
    });
  //
</script>
<?php
  include_once A_MODEL.'operacion/modal/asignar_datos.php';
  include_once A_MODEL.'operacion/modal/cambiar_estado.php';
?>