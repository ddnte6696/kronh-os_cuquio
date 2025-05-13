<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card">
  <div class="card-header"><h3>REGISTRO DE OPERADOR</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_operador" id="frm_agregar_operador">
      <div class="row">

        <div class="col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>NOMBRE</strong></span>
            </div>
            <input type="text" name="name" class="form-control"  placeholder="Nombre" required="">
          </div>
        </div>

        <div class="col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>APELLIDOS</strong></span>
            </div>
            <input type="text" name="lastname" class="form-control"  placeholder="Apellidos" required="">
          </div>
        </div>

        <div class="col-lg-12">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>DIRECCION</strong></span>
            </div>
            <textarea name="address" class="form-control"  placeholder="Direccion"></textarea>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>TELEFONO</strong></span>
            </div>
            <input type="text" name="phone" class="form-control"  placeholder="Telefono"/>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE INGRESO</strong></span>
            </div>
            <input type="date" name="f_ingreso" class="form-control" value="<?php echo ahora(1); ?>" required="">
          </div>
        </div>

        <div class="col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>EMPRESA</strong></span>
            </div>
            <select  name='empresa' class="custom-select" required="">
              <option value="">- Seleccione la empresa -</option>
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
                        //Impresion de los datos
                          echo "<option value='".campo_limpiado($tabla['id'],1,0)."'>".$tabla['empresa']."</option>";
                        //
                      }
                    //
                  }
                //
              ?>
            </select>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>DIVISION</strong></span>
            </div>
            <select  name='division' class="custom-select" required="">
              <option value="">- Seleccione la division -</option>
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
                        //Impresion de los datos
                          echo "<option value='".campo_limpiado($tabla['id'],1,0)."##".campo_limpiado($tabla['prefijo'],1,0)."'>".$tabla['division']."</option>";
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
    <div id="respuesta_agregar_operador"></div>
    <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_operador( );">
  </div>
</div>
<script>
  //funcion de registro de operadors
    function registro_operador(){
      $.ajax({
        type: "POST",
        url: "model/operador/insertion/operador.php",
        data: $("#frm_agregar_operador").serialize(),
        beforeSend: function(){$("#respuesta_agregar_operador").html("<div class='spinner-border'></div>");},
        success: function(data){$("#respuesta_agregar_operador").html(data);},
      });
      return false;
    }
  //
</script>