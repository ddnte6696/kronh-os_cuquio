<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card">
  <div class="card-header"><h3>REGISTRO DE USUARIO</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_usuario" id="frm_agregar_usuario">
      <div class="row">

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>NOMBRE</strong></span>
            </div>
            <input type="text" name="name" class="form-control"  placeholder="Nombre" required="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>APELLIDOS</strong></span>
            </div>
            <input type="text" name="lastname" class="form-control"  placeholder="Apellidos" required="">
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>TELEFONO</strong></span>
            </div>
            <input type="text" name="phone" class="form-control"  placeholder="Telefono"/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>CORREO</strong></span>
            </div>
            <input type="text" name="mail" class="form-control"  placeholder="Correo electronico">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>INGRESO</strong></span>
            </div>
            <input type="date" name="f_ingreso" class="form-control" value="<?php echo ahora(1); ?>" required="">
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>USUARIO</strong></span>
            </div>
            <input type="text" name="user" class="form-control"  placeholder="Clave de empleado o username" autocomplete="off" required="">
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>CONTRASEÑA</strong></span>
            </div>
            <input type="password" name="password" class="form-control" placeholder="Contraseña" autocomplete="off" required="">
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>PUESTO</strong></span>
            </div>
            <select name='puesto' id='puesto' class="custom-select" required="">
              <?php
                //Defino la sentencia a ejecutar
                  $sentencia="SELECT * FROM puestos where id>1";
                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                  $resultado_sentencia=retorna_datos_sistema($sentencia);
                //Identifico si el reultado no es vacio
                  if ($resultado_sentencia['rowCount'] > 0) {
                    //Almaceno los datos obtenidos
                      $resultado = $resultado_sentencia['data'];
                    // Recorrer los datos y llenar las filas
                      foreach ($resultado as $tabla) {
                        //Creo una variable especial
                          $id=campo_limpiado($tabla['id'],1,0);
                          $puesto=$tabla['puesto'];
                        //Imprimo el campo
                          echo "<option value='$id'>$puesto</option>";
                        //
                      }
                    //
                  }
                //
              ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>EMPRESA</strong></span>
            </div>
            <select  name='empresa' id='empresa' class="custom-select" required="">
              <?php
                //Defino la sentencia a ejecutar
                  $sentencia="SELECT * FROM empresas";
                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                  $resultado_sentencia=retorna_datos_sistema($sentencia);
                //Identifico si el reultado no es vacio
                  if ($resultado_sentencia['rowCount'] > 0) {
                    //Almaceno los datos obtenidos
                      $resultado = $resultado_sentencia['data'];
                    // Recorrer los datos y llenar las filas
                      foreach ($resultado as $tabla) {
                        //Creo una variable especial
                          $id=campo_limpiado($tabla['id'],1,0);
                          $empresa=$tabla['empresa'];
                        //Imprimo el campo
                          echo "<option value='$id'>$empresa</option>";
                        //
                      }
                    //
                  }
                //
              ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>DIVISION</strong></span>
            </div>
            <select  name='division' id='division' class="custom-select" required="">
              <?php
                //Defino la sentencia a ejecutar
                  $sentencia="SELECT * FROM division";
                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                  $resultado_sentencia=retorna_datos_sistema($sentencia);
                //Identifico si el reultado no es vacio
                  if ($resultado_sentencia['rowCount'] > 0) {
                    //Almaceno los datos obtenidos
                      $resultado = $resultado_sentencia['data'];
                    // Recorrer los datos y llenar las filas
                      foreach ($resultado as $tabla) {
                        //Creo una variable especial
                          $id=campo_limpiado($tabla['id'],1,0);
                          $division=$tabla['division'];
                        //Imprimo el campo
                          echo "<option value='$id'>$division</option>";
                        //
                      }
                    //
                  }
                //
              ?>
            </select>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <div id="respuesta_agregar_usuario"></div>
    <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_usuario( );">
  </div>
</div>
<script>
  //funcion de registro de usuarios
    function registro_usuario(){
      $.ajax({
        type: "POST",
        url: "model/usuario/insertion/usuario.php",
        data: $("#frm_agregar_usuario").serialize(),
        beforeSend: function(){$("#respuesta_agregar_usuario").html("<div class='spinner-border'></div>");},
        success: function(data){$("#respuesta_agregar_usuario").html(data);},
      });
      return false;
    }
  //habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#taquilla').select2();
        $('#puesto').select2();
        $('#empresa').select2();
        $('#division').select2();
      });
    });
  //
</script>