<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
  $dato=explode("||", campo_limpiado($_POST['form_act'],2,0));
  $nombre=$dato[0];
  $identificador=$dato[1];
  $origen=$dato[2];

?>
<script>
    function tabla_destinos_disponibles(){
      var dato=$("#dato").val();
      var url="model/operacion/queries/destinos_disponibles.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{form_act:dato},
        beforeSend: function(){
          $("#destinos_disponibles").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#destinos_disponibles').html(datos);
        }
      });
    }
    function tabla_destinos_asignados(){
      var dato=$("#dato").val();
      var url="model/operacion/queries/destinos_asignados.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{form_act:dato},
        beforeSend: function(){
          $("#destinos_asignados").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#destinos_asignados').html(datos);
        }
      });
    }
    $(document).ready(tabla_destinos_disponibles());
    $(document).ready(tabla_destinos_asignados());
</script>
<div class="card">
  <div class="card-header"><h5><?php echo "CORRIDA <strong>$nombre</strong> DESDE <strong>$origen</strong>" ?></h5></div>
  <div class="card-body">
    <div class='form-group' hidden>
      <input type='text' name='dato' id='dato' value="<?php echo $_POST['form_act'] ?>" class='form-control' required=''>
    </div>
    <div id="respuesta"></div>
    <div class="row">
      <div class="col-md-6">
        <div id="destinos_disponibles"></div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div id="destinos_asignados"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  include_once A_MODEL.'operacion/modal/agregar_destino.php';
  include_once A_MODEL.'operacion/modal/editar_precio.php';
?>