<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}
include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<div class="card">
  <div class="card-header"><h5>Búsqueda de diésel</h5></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_busqueda_diesel" id="frm_busqueda_diesel">
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA INICIAL</strong></span>
            </div>
            <input type="date" name="fecha_inicial" value="<?php echo ahora(1); ?>" class="form-control"  required="">
          </div>
        </div>
        <div class="col-md-6 col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA FINAL</strong></span>
            </div>
            <input type="date" name="fecha_final" value="<?php echo ahora(1); ?>" class="form-control"  required="">
          </div>
        </div>
      </div>
      <div class="form-actions">
        <input type="submit" value="BUSCAR" class="btn btn-sm btn-block btn-info" onclick="busqueda_diesel();">
      </div>
    </form>
  </div>
  <div class="card-footer">
    <div id="respuesta_diesel"></div>
  </div>
</div>
<script>
  $(function busqueda_diesel(){
    $("#frm_busqueda_diesel").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_busqueda_diesel"));
      formData.append("dato", "valor");
      $.ajax({
        url: "model/operacion/queries/diesel.php",
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
</script>