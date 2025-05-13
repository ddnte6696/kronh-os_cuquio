<?php
error_reporting(E_ALL);
  ini_set("display_errors", 1);
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<script>
  function acceso(){
    $.ajax({
      type: "POST", 
      url: "model/login.php",
      data: $("#loginform").serialize(),
      beforeSend: function(){
        $("#respuesta_iniciar_sesion").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        if ($.trim(data)== 'ok') {
          location.reload(true); 
        }else{
          $("#respuesta_iniciar_sesion").html(data);
        }
      },
    });
    return false;
  }
</script>
<div>
  <div class="jumbotron text-center" id="page-body">
    <img src="<?php echo LOGO_YAHUALICA; ?>" style="width: 300px;">
    <br>
    <img src="<?php echo LOGO_KORNHOS; ?>" style="width: 150px;">
    <strong><p><?php echo $_SESSION[$_SESSION['ubi']]['title'] ?></p></strong>
  </div>
  <div class="container-fluid" role="main">
    <div class="row ">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <form enctype="multipart/form-data" class="form" method="post" name="loginform" id="loginform" action="view/login.php">

          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>Usuario</strong></span>
            </div>
            <input type="text" name="user" class="form-control"  placeholder="Clave de empleado o username" required="">
          </div>

          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>Contraseña</strong></span>
            </div>
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required="">
          </div>

        </form>
        <br>
        <div>
          <div id="respuesta_iniciar_sesion"></div>
          <a class='btn btn-success btn-block text-light' onclick="acceso();" >Iniciar sesión</a>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>
  </div>
</div>
<footer class="footer text-center">
  <div class="container">kronh-os_cuquio LAB CENTER</div>
</footer>
</body>
</html>