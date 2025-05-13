<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo datos de la sesion
    $taquilla_actual=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo el id de la taquilla
    $sentencia="SELECT id as exist FROM destinos WHERE destino='$taquilla_actual';";
    $id_taquilla_actual=busca_existencia($sentencia);
    $dato_actual=campo_limpiado(("$id_taquilla_actual||$taquilla_actual"),1,0);
  //Identifico si existe ya un corte registrado
    $sentencia="SELECT count(id) AS exist FROM corte where punto_venta='$taquilla_actual' and usuario='$clave' and fecha='".ahora(1)."';";
    $retorno=busca_existencia($sentencia);
  //Identifico si ya hay un corte de este usuario registrado
    if ($retorno>0) {
      //Doy un mensaje y oculto el boton
      echo "
        <script>
          alert('EL TURNO DE VENTA DE ESTE USUARIO YA FUE MARCADO COMO FINALIZADO');
        </script>
      ";
      //Detengo la ejecusion
      die();
    }
  //
?>
<script type="text/javascript">
  //Función para calcular la diferencia
    function calcula_diferencia(){
      var importe=$("#importe").val();
      var recibido=$("#recibido").val();
      var cambio=recibido-importe;
      $("#cambio").val(cambio);
    }
  //Función para registrar una nueva cuenta bancaria
    function busca_destinos(){
      //Defino y asigno las variables
        var puntero=$("#origen").val();
      //Indico la dirección del formulario que quiero llamar
        var url="model/venta/forms/busca_destinos.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          data:{puntero:puntero},
          success: function(datos){$('#busca_destinos').html(datos);}
        });
      //
    }
  //Función para calcular el total de la venta
    function calcula_importe(){
      //Defino y asigno las variables
        var destino=$("#destino").val();
        var peso=$("#peso").val();
        var cantidad=$("#cantidad").val();
      //Indico la dirección del formulario que quiero llamar
        var url="model/venta/update/calcular_importe.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          data:{
            destino:destino,
            peso:peso,
            cantidad:cantidad
          },
          success: function(datos){$('#respuesta_paqueterias').html(datos);}
        });
      //
    }
  //
  $(document).ready(busca_destinos());
</script>
<div class="card text-center">
  <div class="card-header"><h3>VENTA DE PAQUERIA</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_paqueterias" id="frm_paqueterias">
      <div class="row text-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header"><h5>DATOS DE ENVIO</h5></div>
            <div class="card-body">
              <!-- Seleccionador de origen -->
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                      <span class="input-group-text"><strong>ORIGEN</strong></span>
                  </div>
                  <select  name='origen' id="origen" class="custom-select"  style="color:black"  onchange="busca_destinos();" required="">
                    <?php
                       echo "<option value='$dato_actual'>$taquilla_actual</option>";
                      //Defino la sentencia a ejecutar
                        $sentencia="SELECT * FROM destinos where punto=true and id<>$id_taquilla_actual";
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
                                $dato=campo_limpiado(("$id_origen||$origen"),1,0);
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
              <!-- Nombre de quien envia -->
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <strong>ENVIA</strong>
                    </span>
                  </div>
                  <input type='text' id='n_envia' name='n_envia' class='form-control' placeholder="Nombre de quien envia el paquete" required=''/>
                </div>
              <!-- Numero de telefono envia -->
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <strong>TELEFONO</strong>
                    </span>
                  </div>
                  <input type='text' id='t_envia' name='t_envia' class='form-control' placeholder="Telefono de quien envia el paquete" required=''/>
                </div>
              <!-- -->
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header"><h5>DATOS DE RECEPCION</h5></div>
            <div class="card-body">
              <!-- Seleccionador de destino -->
                <div id="busca_destinos"></div>
              <!-- Nombre de quien envia -->
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <strong>RECIBE</strong>
                    </span>
                  </div>
                  <input type='text' id='n_recibe' name='n_recibe' class='form-control' placeholder="Nombre de quien recibe el paquete" required=''/>
                </div>
              <!-- Nombre de telefono envia -->
                <div class="input-group mb-3 input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <strong>TELEFONO</strong>
                    </span>
                  </div>
                  <input type='text' id='t_recibe' name='t_recibe' class='form-control' placeholder="Telefono de quien recibe el paquete" required=''/>
                </div>
              <!-- -->
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header"><h5>DATOS DEL PAQUETE</h5></div>
            <div class="card-body">
              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>CANTIDAD</strong>
                  </span>
                </div>
                <input type='number' id='cantidad' name='cantidad' class='form-control' min="0" onchange='calcula_importe()' placeholder="Cantidad de paquetes" required=''/>
              </div>

              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>PESO</strong>
                  </span>
                </div>
                <input type='number' id='peso' name='peso' class='form-control' min="0" onchange='calcula_importe()' placeholder="Peso por paquete en kilogramos" required=''/>
              </div>

              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>DESCRIPCION</strong>
                  </span>
                </div>
                <input type='text' id='descripcion' name='descripcion' class='form-control' placeholder="Descripcion de lo que se va a enviar" required=''/>
              </div>

              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>OBSERVACIONES</strong>
                  </span>
                </div>
                <textarea class='form-control' id="observacion" name="observacion" placeholder="Notas adicionales para el transporte o entrega del paquete" id='observaciones' name='observaciones'></textarea>
              </div>

              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>IMPORTE</strong>
                  </span>
                </div>
                <input type='number' id='importe' name='importe' class='form-control' value="0" required='' disabled />
              </div>

              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>RECIBIDO</strong>
                  </span>
                </div>
                <input type='number' id='recibido' name='recibido' class='form-control' onchange='calcula_diferencia()' value="0" required=''/>
              </div>

              <div class="input-group mb-3 input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <strong>CAMBIO</strong>
                  </span>
                </div>
                <input type='number' id='cambio' name='cambio' class='form-control' required='' value="0" disabled />
              </div>

            </div>
          </div>
        </div>
      </div>
    </form>
    <input type="submit" id="boton_registro" value="IMPRIMIR" class="btn btn-sm btn-primary btn-block" onclick="cargar_paqueteria();" disabled>
  </div>
  <div class="card-footer"><div id="respuesta_paqueterias"></div></div>
</div>
<script>
  function cargar_paqueteria(){
    $.ajax({
      type: "POST",
      url: "model/venta/insert/venta_paqueteria.php",
      data: $("#frm_paqueterias").serialize(),
      beforeSend: function(){$("#respuesta_paqueterias").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_paqueterias").html(data);},
    });
    return false;
  }
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#origen').select2();
      });
    });
  //Funcion para imprimir el div de los boletos
    function imprimirDiv(nombreDiv) {
      var contenido = document.getElementById(nombreDiv).innerHTML;
      var contenidoOriginal = document.body.innerHTML;
      document.body.innerHTML = contenido;
      window.print();
      document.body.innerHTML = contenidoOriginal;
      venta_paqueteria();
    }
  //
</script>