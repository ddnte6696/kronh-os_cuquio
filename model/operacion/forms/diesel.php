<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //obtengo el precio del diesel actual
    $precio_diesel=busca_existencia("SELECT precio_diesel as exist from datos");
    $precio_adblue=busca_existencia("SELECT precio_adblue as exist from datos");
?>
<script type="text/javascript">
  //Función para registrar una nueva cuenta bancaria
    function calcula_importe(){
      //Defino y asigno las variables
        var litros_diesel=$("#diesel_contado").val();
        var litros_adblue=$("#adblue_contado").val();
      //Defino el precio del diesel
        var precio_diesel=<?php echo $precio_diesel; ?>;
        var precio_adblue=<?php echo $precio_adblue; ?>;
      //Realizo la operacion
        var importe_diesel=litros_diesel*precio_diesel;
        var importe_adblue=litros_adblue*precio_adblue;
      //Imprimo el precio
        $("#importe_diesel_contado").val(importe_diesel.toFixed(2));
        $("#importe_adblue_contado").val(importe_adblue.toFixed(2));
      //
    }
  //
</script>
<div class="card">
  <div class="card-header"><h5>REGISTRAR CARGA DE DIESEL</h5></div>
  <div class="card-body">
    <div class="container-fluid">
      <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_carga_diesel" id="frm_carga_diesel">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="input-group mb-3 input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>FECHA</strong></span>
              </div>
              <input type="date" name="fecha" value="<?php echo ahora(1); ?>" class="form-control"  required="" placeholder="# de ticket">
            </div>
          </div>

          <div class="col-md-4 col-lg-4">
            <div class="input-group mb-3 input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>UNIDAD</strong></span>
              </div>
              <select  name='unidad' id='unidad' class="custom-select text-body" required="">
                <option value=''>SELECCIONA UNA UNIDAD</option>
                <?php
                  //Defino la sentencia a ejecutar
                    $sentencia="SELECT * FROM unidades where visual=1";
                  //Ejecuto la sentencia y almaceno lo obtenido en una variable
                    $resultado_sentencia=retorna_datos_sistema($sentencia);
                  //Identifico si el reultado no es vacio
                    if ($resultado_sentencia['rowCount'] > 0) {
                      //Almaceno los datos obtenidos
                        $resultado = $resultado_sentencia['data'];
                      // Recorrer los datos y llenar las filas
                        foreach ($resultado as $tabla) {
                          //Creo una variable especial
                            $id=$tabla['id'];
                            $numero=$tabla['numero'];
                          //Creeo un dato especial del destino
                            $dato=campo_limpiado($id,1,0);
                          //Imprimo el campo
                            echo "<option value='$dato'>$numero</option>";
                          //
                        }
                      //
                    }
                  //
                ?>
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4">
            <div class="input-group mb-3 input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>OPERADOR</strong></span>
              </div>
              <select  name='operador' id='operador' class="custom-select text-body" required="">
                <?php
                  echo "<option value='".campo_limpiado('vacio',1,0)."'>SIN OPERADOR</option>";
                  //Defino la sentencia a ejecutar
                    $sentencia="SELECT * FROM operadores where visual=1";
                  //Ejecuto la sentencia y almaceno lo obtenido en una variable
                    $resultado_sentencia=retorna_datos_sistema($sentencia);
                  //Identifico si el reultado no es vacio
                    if ($resultado_sentencia['rowCount'] > 0) {
                      //Almaceno los datos obtenidos
                        $resultado = $resultado_sentencia['data'];
                      // Recorrer los datos y llenar las filas
                        foreach ($resultado as $tabla) {
                          //Creo una variable especial
                            $id=$tabla['clave'];
                            $nombre_operador=$tabla['nombre']." ".$tabla['apellido'];
                          //Creeo un dato especial del destino
                            $dato=campo_limpiado($id,1,0);
                          //Imprimo el campo
                            echo "<option value='$dato'>[$id] $nombre_operador</option>";
                          //
                        }
                      //
                    }
                  //
                ?>
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4">
            <div class="input-group mb-3 input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>KILOMETRAJE</strong></span>
              </div>
              <input type="number" step="0.01" min="0" name="kilometraje" id="kilometraje" class="form-control" required="" placeholder="Kilometraje del odometro o hubodometro">
            </div>
          </div>

          <div class="col-md-6 col-lg-6">
            <div class="card">
              <div class="card-header"><h6>CONTADO</h6></div>
              <div class="card-body">
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>FOLIO</strong></span>
                  </div>
                  <input type="text" name="folio_contado" id="folio_contado" class="form-control"  required="" placeholder="# de folio">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>DIESEL</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="diesel_contado" id="diesel_contado" class="form-control" required="" placeholder="Litros de diesel cargados" onchange="calcula_importe()">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>IMPORTE DIESEL</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="importe_diesel_contado" id="importe_diesel_contado" class="form-control" required="" placeholder="Importe total de la carga">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>ADBLUE</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="adblue_contado" id="adblue_contado" class="form-control" required="" placeholder="Litros de adblue cargados" onchange="calcula_importe()">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>IMPORTE ADBLUE</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="importe_adblue_contado" id="importe_adblue_contado" class="form-control" required="" placeholder="Importe total de la carga">
                </div>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-6">
            <div class="card">
              <div class="card-header"><h6>CREDITO</h6></div>
              <div class="card-body">
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>FOLIO</strong></span>
                  </div>
                  <input type="text" name="folio_credito" id="folio_credito" class="form-control"  required="" placeholder="# de folio">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>DIESEL</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="diesel_credito" id="diesel_credito" class="form-control" required="" placeholder="Litros de diesel cargados" onchange="calcula_importe()">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>IMPORTE DIESEL</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="importe_diesel_credito" id="importe_diesel_credito" class="form-control" required="" placeholder="Importe total de la carga">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>ADBLUE</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="adblue_credito" id="adblue_credito" class="form-control" required="" placeholder="Litros de adblue cargados" onchange="calcula_importe()">
                </div>

                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><strong>IMPORTE ADBLUE</strong></span>
                  </div>
                  <input type="number" step="0.01" min="0" name="importe_adblue_credito" id="importe_adblue_credito" class="form-control" required="" placeholder="Importe total de la carga">
                </div>

              </div>
            </div>
          </div>

          <div class="col-md-12 col-lg-12">
            <div id="respuesta_diesel"></div>
          </div>
          <div class="col-md-12 col-lg-12">
            <input type="submit" value="REGISTRAR" class="btn btn-block btn-success" onclick="carga_diesel();">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(function carga_diesel(){
    $("#frm_carga_diesel").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_carga_diesel"));
      formData.append("dato", "valor");
      $.ajax({
        url: "model/operacion/insertion/carga_diesel.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
          $("#respuesta_diesel").html("<div class='spinner-border'></div>");
        },
      })
      .done(function(res){
        $("#respuesta_diesel").html(res);
      });
    });
  });
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#operador').select2();
        $('#unidad').select2();
      });
    });
  //
</script>