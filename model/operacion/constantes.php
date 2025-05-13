<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluyen los archivos de conexión
  include_once A_CONNECTION;
  $query=$conn->prepare("SELECT * from constantes");
  $query->execute();
  while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
    $precio_diesel=$tabla['precio_diesel'];
    $precio_adblue=$tabla['precio_adblue'];
  }
?>
<div class="card">
  <div class="card-header"><h5>Constantes de trabajo</h5></div>
  <div class="card-body">
      <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_constantes" id="frm_constantes">
        <table class="table table-sm table-hover">
          <tbody>
            <?php
              echo "
                <tr>
                  <th>Precio del adblue actual</th>
                  <td><input type='text' name='precio_adblue' class='form-control'  value='$precio_adblue' required=''></td>
                </tr><tr>
                  <th>Precio del diesel actual</th>
                  <td><input type='text' id='precio_diesel' name='precio_diesel' class='form-control'  value='$precio_diesel' required=''></td>
                </tr>
              ";
            ?>
          </tbody>
        </table>
        <div class="form-actions">
          <input type="submit" value="Actualizar" class="btn btn-sm btn-success btn-block" onclick="actualizar_datos();">
        </div>
      </form>
      <div id="response"></div>
  </div>
</div>
<script type="text/javascript">
  $(function actualizar_datos(){
    $("#frm_constantes").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_constantes"));
      formData.append("dato", "valor");
      //formData.append(f.attr("name"), $(this)[0].files[0]);
      $.ajax({
        url: "model/update/constantes.php",
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