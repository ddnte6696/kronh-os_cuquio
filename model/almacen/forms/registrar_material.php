<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
	//Defina la fecha actual
		$fecha_actual = ahora(1);
	//
?>
<div class="card">
  <div class="card-header"><h3>REGISTRO DE NUEVO PRODUCTO</h3></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_crear_producto" id="frm_crear_producto">
      <div class="row text-center">
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FACTURA O NOTA</strong></span>
            </div>
            <input type='text' name="nota" class='form-control' placeholder="NUMERO DE FACTURA O NOTA DE COMPRA" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE COMPRA</strong></span>
            </div>
            <input type='date' class='form-control' required="" name="f_ingreso" value="<?php echo $fecha_actual; ?>" max="<?php echo $fecha_actual; ?>"/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>PRODUCTO</strong></span>
            </div>
            <input type="text" name="producto" class="form-control"  placeholder="NOMBRE DESCRIPTIVO" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>PROVEEDOR</strong></span>
            </div>
            <input type="text" name="proveedor" class="form-control"  placeholder="PROVEEDOR DEL PRODUCTO" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>CANTIDAD</strong></span>
            </div>
            <input type="number" name="cantidad" class="form-control" step="0.01" placeholder="CANTIDAD DE UNIDADES A INGRESAR" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>PRECIO</strong></span>
            </div>
            <input type="text" name="precio" class="form-control"  placeholder="PRECIO UNITARIO" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>UBICACION</strong></span>
            </div>
            <input type="text" name="ubicacion" class="form-control"  placeholder="UBICACION FISICA DEL PRODUCTO EN EL ALMACEN" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>OBSERVACIONES</strong></span>
            </div>
            <input type="text" name="observacion" class="form-control"  placeholder="OBSERVACIONES DEL PRODUCTO" required=""/>
          </div>
        </div>
      </div>
      <div class="form-group">
        <input type="submit" value="Registrar" class="btn btn-success btn-block" onclick="crear_producto();">
      </div>
      <div class="form-group">
        <div id="respuesta_crear_producto"></div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(function crear_producto(){
    $("#frm_crear_producto").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_crear_producto"));
      formData.append("dato", "valor");
      //formData.append(f.attr("name"), $(this)[0].files[0]);
      $.ajax({
        url: "model/almacen/insert/crear_producto.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){ $("#respuesta_crear_producto").html("<div class='spinner-border'></div>"); },
      })
      .done(function(res){ $("#respuesta_crear_producto").html(res); });
    });
  });
</script>