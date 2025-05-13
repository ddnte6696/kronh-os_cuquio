<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
    //
?>
<div class="modal fade" id="pasajero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center><h4 class="modal-title" id="myModalLabel">Registrar pasajero</h4></center>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_asientos" id="frm_asientos">
						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="id_asiento" name="id_asiento">
						</div>
						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>Asiento numero</strong></span>
							</div>
							<input type="text" class="form-control" id="asiento" name="asiento" disabled="">
						</div>
						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>Nombre</strong></span>
							</div>
							<input type="text" name="nombre" id="nombre" class="form-control" required="">
						</div>
						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>Tipo</strong></span>
							</div>
							<select  name='tipo' id='tipo' class="custom-select text-body" required="">
								<option value="<?php echo campo_limpiado('Adulto',1,1); ?>">ADULTO</option>
								<option value="<?php echo campo_limpiado('Discapacitado',1,1); ?>">DISCAPACITADO</option>
								<option value="<?php echo campo_limpiado('Estudiante',1,1); ?>">ESTUDIANTE</option>
								<option value="<?php echo campo_limpiado('Insen',1,1); ?>">INSEN</option>
								<option value="<?php echo campo_limpiado('Menor',1,1); ?>">MENOR</option>
								<option value="<?php echo campo_limpiado('Profesor',1,1); ?>">PROFESOR</option>
							</select>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Registrar" class="btn btn-sm btn-block btn-success" onclick="agregar_pasajero();">
			</div>
		</div>
	</div>
</div>