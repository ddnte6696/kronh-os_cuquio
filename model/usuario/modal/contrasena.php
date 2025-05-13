<div class="modal fade" id="cambio_contraseña" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center"><h4 class="modal-title" id="myModalLabel">Cambiar contraseña</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_cambio_contraseña" id="frm_cambio_contraseña">
						<div class="form-group input-group">
							<input type="text" class="form-control" id="id" name="identi" hidden="">
						</div>
						<div class="form-group input-group">
							<input type="password" class="form-control" id="pass" name="pass" placeholder="Ingresa la nueva contraseña">
						</div>
						<div class="form-group input-group">
							<input type="password" class="form-control" id="pass2" name="pass2" placeholder="Repite la contraseña">
						</div>
					</form>
				</div>
				<div id="respuesta_perfil_usuario_2"></div>
			</div>
			<div class="modal-footer text-center">
				<button type="button" class="btn btn-sm btn-dark" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Cancelar</button>
				<input type="submit" value="Cambiar contraseña" class="btn btn-sm btn-success" onclick="contraseña();">
			</div>
		</div>
	</div>
</div>