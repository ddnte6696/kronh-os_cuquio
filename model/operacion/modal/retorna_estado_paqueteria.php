<div class="modal fade"  data-backdrop="false" id="retorna_estado_paqueteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">CAMBIAR ESTADO DE LA PAQUETERIA</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_retorna_estado_paqueteria" id="frm_retorna_estado_paqueteria">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="datos_rep" name="datos">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FOLIO</strong></span>
							</div>
							<input type="text" class="form-control" id="id_rep" name="id" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ORIGEN</strong></span>
							</div>
							<input type="text" class="form-control" id="origen_rep" name="origen" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ENVIA</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_envia_rep" name="envia" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>DESTINO</strong></span>
							</div>
							<input type="text" class="form-control" id="destino_rep" name="destino" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>RECIBE</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_recibe_rep" name="recibe" disabled="">
						</div>

						<div id="faltantes_retorna_estado_paqueteria"></div>

					</form>
				</div>
				<div id="respuesta_retorna_estado_paqueteria"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="ASIGNAR DATOS" class="btn btn-block btn-success" onclick="retorna_estado_paqueteria();">
			</div>
		</div>
	</div>
</div>
