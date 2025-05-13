<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
	//Defina la fecha actual
		$fecha_actual = ahora(1);
	//
?>
<div class="modal fade" id="eliminar_movimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center><h4 class="modal-title" id="myModalLabel">ELIMINAR MOVIMIENTO</h4></center>
			</div>
			<div class="modal-body text-center">
				<div class="container-fluid">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_eliminar_movimiento" id="frm_eliminar_movimiento">
						<div class="form-group input-group" hidden="">
							<input type="text" class="form-control text-center" id="eliminar_movimiento_datos" name="datos">
						</div>
            <div class="form-group">
							<label><strong>FECHA</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_fecha" name="fecha" disabled="">
						</div>
            <div class="form-group">
							<label><strong>MOVIMIENTO</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_tipo" name="tipo" disabled="">
						</div>
						<div class="form-group">
							<label><strong>PRODUCTO</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_producto" name="producto" disabled="">
						</div>
            <div class="form-group">
							<label><strong>PROVEEDOR</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_proveedor" name="proveedor" disabled="">
						</div>
						<div class="form-group">
							<label><strong>EXISTENCIA</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_existencia" name="existencia" disabled="">
						</div>
						<div class="form-group">
							<label><strong>PRECIO UNITARIO</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_precio" name="precio" disabled="">
						</div>
						<div class="form-group">
							<label><strong>COMENTARIOS</strong></label>
							<input type="text" class="form-control text-center" id="eliminar_movimiento_observacion" name="observacion" placeholder="COMENTARIOS DE LA CANCELACION DEL MOVIMIENTO">
						</div>
					</form>
				</div>
				<div id="respuesta_eliminar_movimiento"></div>
			</div>
			<div class="modal-footer">
				<input type="submit" value="ELIMINAR" class="btn btn-block btn-success" onclick="eliminar_movimiento();">
			</div>
		</div>
	</div>
</div>