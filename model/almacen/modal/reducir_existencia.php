<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
	//Defina la fecha actual
		$fecha_actual = ahora(1);
	//
?>
<div class="modal fade" id="reducir_existencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center><h4 class="modal-title" id="myModalLabel">RESTAR EXISTENCIA</h4></center>
			</div>
			<div class="modal-body text-center">
				<div class="container-fluid">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_reducir_existencia" id="frm_reducir_existencia">
						<div class="form-group input-group" hidden="">
							<input type="text" class="form-control" id="reducir_datos_producto" name="datos_producto">
						</div>
						<div class="form-group">
							<label><strong>PRODUCTO</strong></label>
							<input type="text" class="form-control" id="reducir_producto" name="producto" disabled="">
						</div>
						<div class="form-group">
							<label><strong>EXISTENCIA</strong></label>
							<input type="text" class="form-control" id="reducir_cantidad_existencia" name="existencia" disabled="">
						</div>
						<div class="form-group">
							<label><strong>PRECIO UNITARIO</strong></label>
							<input type="text" class="form-control" id="reducir_precio" name="precio" disabled="">
						</div>
						<div class="form-group">
							<label><strong>FECHA DE SALIDA</strong></label>
							<input type="date" class="form-control" id="reducir_f_salida" name="f_salida" max="<?php echo $fecha_actual; ?>">
						</div>
						<div class="form-group">
							<label><strong>NUMERO DE NOTA U ORDEN</strong></label>
							<input type="text" class="form-control" id="reducir_nota" name="nota" placeholder="NUMERO DE NOTA U ORDEN PARA LA SALIDA DEL PRODUCTOS">
						</div>
						<div class="form-group">
							<label><strong>UNIDADES A SALIR</strong></label>
							<input type="number" class="form-control" id="reducir_cantidad" name="cantidad" placeholder="CANTIDAD DE PRODUCTOS A SACAR DE ALMACEN">
						</div>
						<div class="form-group">
							<label><strong>DESTINO</strong></label>
							<input type="text" class="form-control" id="reducir_destino" name="destino" placeholder="UNIDAD O DESTINO DE LOS PRODUCTOS">
						</div>
						<div class="form-group">
							<label><strong>COMENTARIOS</strong></label>
							<input type="text" class="form-control" id="reducir_observacion" name="observacion" placeholder="COMENTARIOS O DETALLES DE LA SALIDA DE PRODUCTOS">
						</div>
					</form>
				</div>
				<div id="respuesta_reducir_existencia"></div>
			</div>
			<div class="modal-footer">
				<input type="submit" value="REGISTRAR" class="btn btn-block btn-success" onclick="reducir_existencia();">
			</div>
		</div>
	</div>
</div>