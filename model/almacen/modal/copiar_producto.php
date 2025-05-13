<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
	//Defina la fecha actual
		$fecha_actual = ahora(1);
	//
?>
<div class="modal fade" id="copiar_producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center><h4 class="modal-title" id="myModalLabel">COPIAR PRODUCTO</h4></center>
			</div>
			<div class="modal-body text-center">
				<div class="container-fluid">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_copiar_producto" id="frm_copiar_producto">
						<div class="form-group input-group" hidden="">
							<input type="text" class="form-control" id="copiar_datos_producto" name="datos_producto">
						</div>
						<div class="form-group">
							<label><strong>PRODUCTO</strong></label>
							<input type="text" class="form-control" id="copiar_nombre_producto" name="producto" disabled="">
						</div>
						<div class="form-group">
							<label><strong>PROVEEDOR</strong></label>
							<input type="text" class="form-control" id="copiar_proveedor" name="proveedor" disabled="">
						</div>
						<div class="form-group">
							<label><strong>FECHA DE INRGESO</strong></label>
							<input type="date" class="form-control" name="f_ingreso" value="<?php echo $fecha_actual; ?>" max="<?php echo $fecha_actual; ?>">
						</div>
						<div class="form-group">
							<label><strong>FACTURA O NOTA</strong></label>
							<input type="text" class="form-control" id="copiar_nota" name="nota">
						</div>
						<div class="form-group">
							<label><strong>CANTIDAD</strong></label>
							<input type="number" class="form-control" id="copiar_cantidad" name="cantidad" placeholder="CANTIDAD DE UNIDADES A INGRESAR">
						</div>
						<div class="form-group">
							<label><strong>PRECIO</strong></label>
							<input type="text" class="form-control" id="copiar_precio" name="precio" placeholder="PRECIO UNITARIO">
						</div>
						<div class="form-group">
							<label><strong>UBICACION</strong></label>
							<input type="text" class="form-control" id="copiar_ubicacion" name="ubicacion" placeholder="UBICACION DEL PRODUCTO">
						</div>
						<div class="form-group">
							<label><strong>COMENTARIOS</strong></label>
							<input type="text" class="form-control" id="copiar_observacion" name="observacion" placeholder="COMENTARIOS O DETALLES DEL INGESO">
						</div>
					</form>
				</div>
				<div id="respuesta_copiar_producto"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>CANCELAR</strong></button>
				<input type="submit" value="REGISTRAR" class="btn btn-success" onclick="copiar_producto();">
			</div>
		</div>
	</div>
</div>