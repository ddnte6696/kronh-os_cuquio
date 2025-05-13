<div class="modal fade"  data-backdrop="false" id="asignar_datos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">ASIGNAR UNIDAD Y OPERADOR</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_asignar_datos" id="frm_asignar_datos">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="id_corrida_datos" name="id_corrida_datos">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ORIGEN</strong></span>
							</div>
							<input type="text" class="form-control" id="origen_datos" name="origen_datos" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>CORRIDA</strong></span>
							</div>
							<input type="text" class="form-control" id="servicio_datos" name="servicio_datos" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>HORA</strong></span>
							</div>
							<input type="text" class="form-control" id="hora_datos" name="hora_datos" disabled="">
						</div>

						<div id="faltantes_asignar_datos"></div>

					</form>
				</div>
				<div id="respuesta_asignar_datos"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="ASIGNAR DATOS" class="btn btn-block btn-success" onclick="asignar_datos();">
			</div>
		</div>
	</div>
</div>
