<div class="card text-center">
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_sql" id="frm_sql">
      <div class="form-group">
        <textarea name="codex" class="form-control" placeholder="Codigo SQL a ejecutar" required=""></textarea>
      </div>
      <!-- Input Boton -->
      <div class="form-actions">
        <input type="submit" value="CONSULTAR" class="btn btn-sm btn-success" onclick="ejecutar();">
      </div>
    </form>
  </div>
  <div class="card-footer" id="respuesta_sql"></div>
</div>
<script>
  $(function ejecutar(){
    $("#frm_sql").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_sql"));
      formData.append("dato", "valor");
      $.ajax({
        url: "model/menu/queries/ejecucion.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
          $("#respuesta_sql").html("<div class='spinner-border'></div>");
        },
      })
      .done(function(res){
        $("#respuesta_sql").html(res);
      });
    });
  });
</script>