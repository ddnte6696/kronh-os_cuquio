<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
    //
?>
<div class="modal fade"  data-backdrop="false" id="cambiar_estado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">CAMBIAR ESTADO DE LA CORRIDA</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_cambiar_estado" id="frm_cambiar_estado">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="id_corrida_estado" name="id_corrida_estado">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ORIGEN</strong></span>
							</div>
							<input type="text" class="form-control" id="origen_estado" name="origen_estado" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>CORRIDA</strong></span>
							</div>
							<input type="text" class="form-control" id="servicio_estado" name="servicio_estado" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>HORA</strong></span>
							</div>
							<input type="text" class="form-control" id="hora_estado" name="hora_estado" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ESTADO</strong></span>
							</div>
							<select  name='estado' id='estado' class="custom-select text-body" required="">
								<option value=''>SELECCIONA UN ESTADO</option>
								<option value="<?php echo campo_limpiado('1',1,0); ?>">ABIERTA</option>
								<option value="<?php echo campo_limpiado('2',1,0); ?>">DESPACHADA</option>
								<option value="<?php echo campo_limpiado('3',1,0); ?>">CANCELADA</option>
							</select>
						</div>
						

					</form>
				</div>
				<div id="respuesta_cambiar_estado"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="ASIGNAR DATOS" class="btn btn-block btn-success" onclick="cambiar_estado();">
			</div>
		</div>
	</div>
</div>
