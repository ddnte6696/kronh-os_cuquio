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
  //
  $(document).ready(busca_destinos());
</script>
<div class="card text-center">
  <div class="card-header"><h3>VENTA DE BOLETOS</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_corridas" id="frm_corridas">
      <div class="row text-center">
        <div class="col-md-4">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA</strong></span>
            </div>
            <input type='date' name='fecha' name='fecha' min='<?php echo ahora(1) ?>' class='form-control' value='<?php echo ahora(1) ?>' required=''/>
          </div>
        </div>
        <div class="col-md-4">
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
        </div>
        <div class="col-md-4">
          <div id="busca_destinos">
          </div>
        </div>
      </div>
    </form>
    <input type="submit" id="boton_registro" value="VER CORRIDAS" class="btn btn-sm btn-primary btn-block" onclick="busca_corridas( );">
  </div>
  <div class="card-footer"><div id="respuesta_corridas"></div></div>
</div>
<script>
  function busca_corridas(){
    $.ajax({
      type: "POST",
      url: "model/venta/queries/corridas.php",
      data: $("#frm_corridas").serialize(),
      beforeSend: function(){$("#respuesta_corridas").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_corridas").html(data);},
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
      venta_boletos();
    }
  //
</script>