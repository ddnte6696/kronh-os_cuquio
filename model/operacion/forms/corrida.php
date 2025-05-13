<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
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
  <div class="card-header"><h3>REGISTRO DE CORRIDA</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_corrida" id="frm_agregar_corrida">
      <div class="row">
        <div class="col-md-4">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>ORIGEN</strong></span>
            </div>
            <select  name='origen' id="origen" class="custom-select"  style="color:black"  onchange="busca_rutas();" required="">
              <?php
                $query=$conn->prepare("SELECT * FROM destinos where punto=true");
                $query->execute();
                while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
                  $id=$tabla['id'];
                  $destino=$tabla['destino'];
                  echo "<option value='$destino'>$destino</option>";
                }
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
              <span class="input-group-text"><strong>HORA DE LA CORRIDA</strong></span>
            </div>
            <input type='time' name='hora' id='hora' class='form-control' required=''/>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <div id="respuesta_agregar_corrida"></div>
    <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_corrida();">
  </div>
</div>
<script>
  //funcion de registro de corrida
    function registro_corrida(){
      $.ajax({
        type: "POST",
        url: "model/operacion/insertion/corrida.php",
        data: $("#frm_agregar_corrida").serialize(),
        beforeSend: function(){$("#respuesta_agregar_corrida").html("<div class='spinner-border'></div>");},
        success: function(data){$("#respuesta_agregar_corrida").html(data);},
      });
      return false;
    }
  //
</script>