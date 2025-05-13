<div class="modal fade"  data-backdrop="false" id="cambiar_talonario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">FINLIZAR TALONARIO</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_cambiar_talonario" id="frm_cambiar_talonario">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="id_talonario_cambiar" name="id_talonario_cambiar">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>TALONARIO</strong></span>
							</div>
							<input type="text" class="form-control" id="talonario_cambiar" name="talonario_cambiar" disabled="">
						</div>
						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FOLIO ACTUAL</strong></span>
							</div>
							<input type="text" class="form-control" id="actual_cambiar" name="actual_cambiar" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>NUEVO FOLIO ACTUAL</strong></span>
							</div>
							<input type="text" class="form-control" id="nuevo_cambiar" name="nuevo_cambiar">
						</div>
					</form>
				</div>
				<div id="respuesta_cambiar_talonario"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="CAMBIAR FOLIO ACTUAL" class="btn btn-block btn-success" onclick="cambiar_talonario();">
			</div>
		</div>
	</div>
</div>
