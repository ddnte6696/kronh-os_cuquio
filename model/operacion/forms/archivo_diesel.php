<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
?>
<div class="container-fluid" role="main">
  <div class="card">
    <div class="card-header">
      <h5>Cargar archivo de diésel</h5>
    </div>
    <div class="card-body">
      <div class="container-fluid">
      <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_usuario" id="frm_usuario">
        <div class="input-group mb-3 input-group-sm">
          <div class="input-group-prepend">
            <span class="input-group-text"><strong>FECHA DEL ARCHIVO</strong></span>
          </div>
          <input type="date" name="fecha" class="form-control"  required="" placeholder="# de ticket">
        </div>

        <div class="input-group mb-3 input-group-sm">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile" name='imagen'>
            <label class="custom-file-label" for="customFile">Cargar archivo .CSV con las cargas de diésel</label>
          </div>
        </div>
        <!-- Input Boton -->
        <div class="form-actions">
          <input type="submit" value="Registrar" class="btn btn-sm btn-success" onclick="archivo_liq();">
        </div>
        <div id="response"></div>
      </form>
    </div>
  </div>
</div>
<script>
  $(function archivo_liq(){
    $("#frm_usuario").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_usuario"));
      formData.append("dato", "valor");
      //formData.append(f.attr("name"), $(this)[0].files[0]);
      $.ajax({
        url: "model/operacion/insert/archivo_diesel.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
          $("#response").html("<div class='spinner-border'></div>");
        },
      })
      .done(function(res){
        $("#response").html(res);
        
      });
    });
  });
</script>
<script>
  $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });
</script>