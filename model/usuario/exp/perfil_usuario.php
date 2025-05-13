<!--nombre>>Mi perfil-->
<?php
  session_start();
  $id=$_SESSION['cuquio']['id'];
  include_once '../connection/cuquio.sql.db.php';
  $query=$conn->prepare("
    SELECT 
      a.id as id_empresa,
      a.empresa,
      b.id as id_division,
      b.division,
      c.id as id_puesto,
      c.puesto
    FROM usuarios as d
      LEFT JOIN empresas as a on a.id=d.empresa
      LEFT JOIN division as b ON b.id=d.division
      LEFT JOIN puestos as c ON c.id=d.puesto
    WHERE d.id=$id
  ");
  $query->execute();
  while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
    $id_empresa=$tabla['id_empresa'];
    $id_division=$tabla['id_division'];
    $id_puesto=$tabla['id_puesto'];
    $empresa=$tabla['empresa'];
    $puesto=$tabla['puesto'];
    $division=$tabla['division'];
  }
  $nombre=$_SESSION['cuquio']['nombre'];
  $apellido=$_SESSION['cuquio']['apellido'];
  $clave=$_SESSION['cuquio']['clave'];
  $visual=$_SESSION['cuquio']['visual'];
  $imagen=$_SESSION['cuquio']['imagen'];
  $phone=$_SESSION['cuquio']['telefono'];
  $mail=$_SESSION['cuquio']['correo'];
?>
<div class="card text-center">
  <div class="card-header"><h5>DATOS DEL USUARIO N° <?php echo $id ?></h5></div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6 col-lg-6">
        <img src='img/usuarios/<?php echo $imagen ?>' class='card-img-top' style='width: 50%'>
      </div>
      <div class="col-md-6 col-lg-6">
        <div class="card-body">
          <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_perfil_usuario" id="frm_perfil_usuario">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile" name='imagen'>
              <label class="custom-file-label" for="customFile">Subir nueva Imagen</label>
            </div>
            <table class="table table-sm">
              <tbody>
                <?php
                  echo "
                    <input type='text' name='id'value='$id' style='display: none;'>
                    <tr>
                      <th>Nombre</th>
                      <td>
                        <input type='text' id='nombre' name='nombre' class='form-control'  value='$nombre' required='' >
                        <input type='text' id='apellido' name='apellido' class='form-control'  value='$apellido' required='' >
                      </td>
                    </tr>
                    <tr>
                      <th>Telefono</th>
                      <td><input type='text' name='phone' class='form-control'  value='$phone' required=''></td>
                    </tr>
                    <tr>
                      <th>Correo electronico</th>
                      <td><input type='text' name='mail' class='form-control'  value='$mail' required=''></td>
                    </tr>
                    <tr>
                      <th>Clave</th>
                      <td><input type='text' name='clave' class='form-control'  value='$clave' required=''></td>
                    </tr>
                    <tr>

                      <th>Puesto</th>
                      <td>
                        <input value='$puesto' class='form-control' disabled=''>
                      </td>
                    </tr>
                    <tr>
                      <th>Empresa</th>
                      <td>
                      <select  name='empresa' class='custom-select' required=''>
                        <option value='$id_empresa'>$empresa</option>";
                          $res=$conn->prepare("SELECT * FROM empresas where id<>$id_empresa");
                          $res->execute();
                          while ($tabla=$res->fetch(PDO::FETCH_ASSOC)){
                            $id_empresa2=$tabla['id'];
                            $empresa=$tabla['empresa'];
                            echo "<option value='$id_empresa2'>$empresa</option>";
                          }
                      echo "
                      </select></td>
                    </tr>
                    <tr>
                      <th>Division</th>
                      <td>
                      <select  name='division' class='custom-select' required=''>
                        <option value='$id_division'>$division</option>";
                          $res=$conn->prepare("SELECT * FROM division where id<>$id_division");
                          $res->execute();
                          while ($tabla=$res->fetch(PDO::FETCH_ASSOC)){
                            $id_division2=$tabla['id'];
                            $division=$tabla['division'];
                            echo "<option value='$id_division2'>$division</option>";
                          }
                      echo "
                      </select></td>
                    </tr>
                  ";
                ?>    
              </tbody>
            </table>
            <input type="submit" value="Actualizar perfil" class='btn btn-success btn-block btn-sm' onclick="actualizar_datos();">
          </form>
          <button type="button" class="btn btn-sm btn-danger btn-block" onclick="contra('<?php echo $id ?>')">Cambiar contraseña</button>
          <div id="respuesta_perfil_usuario"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once('../model/modales/contrasena.php'); ?>
<script>
  //funcion para llamada del modal
    function contra(datos){
      $('#cambio_contraseña').modal('show');
      $('#id').val(datos);
    }
  //actualizacion de datos
    $(function actualizar_datos(){
      $("#frm_perfil_usuario").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("frm_perfil_usuario"));
        formData.append("dato", "valor");
        $.ajax({
          url: "model/update/info_user.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){$("#respuesta_perfil_usuario").html("<div class='spinner-border'></div>");},
        })
        .done(function(res){$("#respuesta_perfil_usuario").html(res);});
      });
    });
  //actualizacion de contraseña
    function contraseña(){
      $.ajax({
        type: "POST",
        url: "model/update/contraseña.php",
        data: $("#frm_cambio_contraseña").serialize(),
        beforeSend: function(){
        $("#respuesta_perfil_usuario_2").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_perfil_usuario_2").html(data);
        },
      });
      return false;
    }
  //script para vista del archivo cargado
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  //
</script>
