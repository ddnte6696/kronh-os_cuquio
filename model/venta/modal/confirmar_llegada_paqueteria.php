<div class="modal fade"  data-backdrop="false" id="confirmar_llegada_paqueteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">CONFIRMAR LLEGADA</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_confirmar_llegada_paqueteria" id="frm_confirmar_llegada_paqueteria">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="datos_cll" name="datos">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FOLIO</strong></span>
							</div>
							<input type="text" class="form-control" id="id_cll" name="id" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ORIGEN</strong></span>
							</div>
							<input type="text" class="form-control" id="origen_cll" name="origen" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ENVIA</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_envia_cll" name="envia" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>DESTINO</strong></span>
							</div>
							<input type="text" class="form-control" id="destino_cll" name="destino" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>RECIBE</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_recibe_cll" name="recibe" disabled="">
						</div>
						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FECHA DE ENVIO</strong></span>
							</div>
							<input type="text" class="form-control" id="fecha_corrida_cll" name="fecha_corrida" disabled="">
						</div>
						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>CORRIDA</strong></span>
							</div>
							<input type="text" class="form-control" id="servicio_cll" name="servicio" disabled="">
						</div>
					</form>
				</div>
				<div id="respuesta_confirmar_llegada_paqueteria"></div>
			</div>
			<div class="modal-footer">
				<input type="submit" value="CONFIRMAR LLEGADA" class="btn btn-block btn-success" onclick="confirmar_llegada_paqueteria();">
			</div>
		</div>
	</div>
</div>
