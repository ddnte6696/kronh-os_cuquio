<?php 
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//
?>
<div class="modal fade" id="baja_operador" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">BAJA DE OPERADOR</h4></center>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_baja" id="frm_baja">
						<div class="form-group" hidden>
							<input type="text" class="form-control" id="id_baja" name="id">
						</div>
						<div class="form-group">
							<label><strong>OPERADOR</strong></label>
							<input type="text" class="form-control text-center" id="nombre_baja" disabled>
						</div>
						<div class="form-group">
							<label><strong>FECHA</strong></label>
							<input type="date" class="form-control text-center" id="fecha_baja" name="fecha">
						</div>
						
						<div class="form-group">
							<label><strong>MOTIVO</strong></label>
							<textarea style="height:150px" class="form-control text-center" id="motivo" name="motivo" placeholder="Explicacion del motivo de la baja"></textarea>
						</div>
					</form>
				</div>
				<div id="response2"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>CANCELAR</button>
				<input type="submit" value="REGISTRAR" class="btn btn-sm btn-success" onclick="baja();">
			</div>
		</div>
	</div>
</div>