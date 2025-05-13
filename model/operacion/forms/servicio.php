<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<script type="text/javascript">
  //Función para registrar una nueva cuenta bancaria
    function busca_rutas(){
      //Defino y asigno las variables
        var puntero=$("#origen").val();
      //Indico la dirección del formulario que quiero llamar
        var url="model/operacion/forms/busca_ruta.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          data:{puntero:puntero},
          success: function(datos){$('#busca_rutas').html(datos);}
        });
      //
    }
  //
  $(document).ready(busca_rutas());
</script>
<div class="card">
  <div class="card-header"><h3>REGISTRO DE SERVICIOS</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_servicio" id="frm_agregar_servicio">
      <div class="row">
        <div class="col-md-4">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>ORIGEN</strong></span>
            </div>
            <select  name='origen' id="origen" class="custom-select"  style="color:black"  onchange="busca_rutas();" required="">
              <?php
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
        <div class="col-md-4">
          <div id="busca_rutas">
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>HORA DEL SERVICIO</strong></span>
            </div>
            <input type='time' name='hora' id='hora' class='form-control' required=''/>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <div id="respuesta_agregar_servicio"></div>
    <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_servicio();">
  </div>
</div>
<script>
  //funcion de registro de servicio
    function registro_servicio(){
      $.ajax({
        type: "POST",
        url: "model/operacion/insertion/servicio.php",
        data: $("#frm_agregar_servicio").serialize(),
        beforeSend: function(){$("#respuesta_agregar_servicio").html("<div class='spinner-border'></div>");},
        success: function(data){$("#respuesta_agregar_servicio").html(data);},
      });
      return false;
    }
  //
</script>