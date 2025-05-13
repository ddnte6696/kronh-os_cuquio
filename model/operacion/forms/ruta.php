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
  <div class="card-header"><h3>REGISTRO DE RUTA</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_corrida" id="frm_agregar_corrida">
      <div class="row">
        <div class="col-md-4">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>NOMBRE</strong></span>
            </div>
            <input type='text' name='nombre' id='nombre' class='form-control' placeholder="Nombre de la corrida" required=''/>
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>IDENTIFICADOR</strong></span>
            </div>
            <input type='text' name='identificador' id='identificador' class='form-control' placeholder="Identificador para la ruta" required=''/>
          </div>
        </div>
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
      </div>
    </form>
  </div>
  <div class="card-footer">
      <input type="submit" value="BUSCAR" class="btn btn-sm btn-primary btn-block" onclick="registro_ruta();">
    <div id="respuesta_agregar_ruta"></div>
  </div>
</div>
<script>
  //Funcion para editar los datos del usuario
    function registro_ruta(opcion){
      var nombre=$("#nombre").val();
      var identificador=$("#identificador").val();
      var origen=$("#origen").val();
      $.ajax({
        type: "POST", 
        url: "model/operacion/forms/crear_ruta.php",
        data: {
          nombre:nombre,
          identificador:identificador,
          origen:origen,
        },
        beforeSend: function(){
          $("#respuesta_agregar_ruta").html("<div class='spinner-border text-center'></div>");
        },
        success: function(respuesta){
          $("#respuesta_agregar_ruta").html(respuesta);
        }
      });
      return false;
    }
</script>