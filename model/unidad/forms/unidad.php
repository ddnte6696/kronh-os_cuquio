<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
?>
<div class="card">
  <div class="card-header"><h3>REGISTRO DE UNIDAD</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_unidad" id="frm_agregar_unidad">
      <div class="row">

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>NUMERO ECONOMICO</strong></span>
            </div>
            <input type="text" name="numero" class="form-control"  placeholder="Numero economico asignado a la unidad" required="">
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE INGRESO</strong></span>
            </div>
            <input type="date" name="f_ingreso" value="<?php echo ahora(1);?>" class="form-control" required="">
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>EMPRESA</strong></span>
            </div>
            <select  name='empresa' class="custom-select" required="">
              <option value="">- Seleccione la empresa -</option>
              <?php
                $sentencia="SELECT * FROM empresas";
                try {
                  $sql=$conn->prepare( $sentencia);
                  $sql->execute();
                  while($tabla=$sql->fetch(PDO::FETCH_ASSOC)){
                    $id=campo_limpiado($tabla['id'],1,0);
                    $empresa=$tabla['empresa'];
                    echo "<option value='$id'>$empresa</option>";
                  }
                } catch (PDOException $e) {
                  //Almaceno el error en una variabLe
                  $error=$e->getMessage();
                  //Ubico el archivo desde donde se presenta el error
                  $archivo=__FILE__;
                  //Mando a escribir el mensaje
                  escribir_log($error,$sentencia,$archivo);
                  //Detengo el procedimiento
                  die();
                }
              ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>DIVISION</strong></span>
            </div>
            <select  name='division' class="custom-select" required="">
              <option value="">- Seleccione la division -</option>
              <?php
                $sentencia="SELECT * FROM division";
                try {
                  $sql=$conn->prepare( $sentencia);
                  $sql->execute();
                  while($tabla=$sql->fetch(PDO::FETCH_ASSOC)){
                    $id=campo_limpiado($tabla['id'],1,0);
                    $division=$tabla['division'];
                    echo "<option value='$id'>$division</option>";
                  }
                } catch (PDOException $e) {
                  //Almaceno el error en una variabLe
                  $error=$e->getMessage();
                  //Ubico el archivo desde donde se presenta el error
                  $archivo=__FILE__;
                  //Mando a escribir el mensaje
                  escribir_log($error,$sentencia,$archivo);
                  //Detengo el procedimiento
                  die();
                }
              ?>
            </select>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <div id="respuesta_agregar_unidad"></div>
    <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_unidad( );">
  </div>
</div>
<script>
  //funcion de registro de unidades
    function registro_unidad(){
      $.ajax({
        type: "POST",
        url: "model/unidad/insertion/unidad.php",
        data: $("#frm_agregar_unidad").serialize(),
        beforeSend: function(){$("#respuesta_agregar_unidad").html("<div class='spinner-border'></div>");},
        success: function(data){$("#respuesta_agregar_unidad").html(data);},
      });
      return false;
    }
  //
</script>