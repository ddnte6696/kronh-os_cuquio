<div class="modal fade"  data-backdrop="false" id="finalizar_talonario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_finalizar_talonario" id="frm_finalizar_talonario">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="id_talonario_finalizar" name="id_talonario_finalizar">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>TALONARIO</strong></span>
							</div>
							<input type="text" class="form-control" id="talonario_finalizar" name="talonario_finalizar" disabled="">
						</div>
					</form>
				</div>
				<div id="respuesta_finalizar_talonario"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="FINALIZAR TALONARIO" class="btn btn-block btn-success" onclick="finalizar_talonario();">
			</div>
		</div>
	</div>
</div>
