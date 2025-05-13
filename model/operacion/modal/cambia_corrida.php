<div class="modal fade"  data-backdrop="false" id="cambia_corrida_paqueteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">CAMBIAR CORRIDA DE ENVIO</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_cambia_corrida" id="frm_cambia_corrida">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="datos_cc" name="datos">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FOLIO</strong></span>
							</div>
							<input type="text" class="form-control" id="id_cc" name="id" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ORIGEN</strong></span>
							</div>
							<input type="text" class="form-control" id="origen_cc" name="origen" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ENVIA</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_envia_cc" name="envia" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>DESTINO</strong></span>
							</div>
							<input type="text" class="form-control" id="destino_cc" name="destino" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>RECIBE</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_recibe_cc" name="recibe" disabled="">
						</div>

            <div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FECHA</strong></span>
							</div>
							<input type="date" class="form-control" id="fecha_cc" name="fecha" onchange="faltantes_cambia_corrida();" required="">
						</div>

						<div id="#faltantes_cambia_corrida_paqueteria"></div>

					</form>
				</div>
				<div id="respuesta_cambia_corrida"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="ASIGNAR DATOS" class="btn btn-block btn-success" onclick="cambia_corrida();">
			</div>
		</div>
	</div>
</div>
