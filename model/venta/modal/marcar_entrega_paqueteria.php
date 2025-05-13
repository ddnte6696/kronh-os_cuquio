<div class="modal fade"  data-backdrop="false" id="marcar_entrega_paqueteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<center>
					<h4 class="modal-title" id="myModalLabel">MARCAR ENTREGA</h4>
				</center>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid text-center">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_marcar_entrega_paqueteria" id="frm_marcar_entrega_paqueteria">

						<div class="form-group input-group" hidden>
							<input type="text" class="form-control" id="datos_mcp" name="datos">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>FOLIO</strong></span>
							</div>
							<input type="text" class="form-control" id="id_mcp" name="id" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ORIGEN</strong></span>
							</div>
							<input type="text" class="form-control" id="origen_mcp" name="origen" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>ENVIA</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_envia_mcp" name="envia" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>DESTINO</strong></span>
							</div>
							<input type="text" class="form-control" id="destino_mcp" name="destino" disabled="">
						</div>

						<div class="input-group mb-3 input-group-sm">
							<div class="input-group-prepend">
								<span class="input-group-text"><strong>RECIBE</strong></span>
							</div>
							<input type="text" class="form-control" id="nombre_recibe_mcp" name="recibe">
						</div>


					</form>
				</div>
				<div id="respuesta_marcar_entrega_paqueteria"></div>
			</div>
			<div class="modal-footer">
				
				<input type="submit" value="ASIGNAR DATOS" class="btn btn-block btn-success" onclick="marcar_entrega_paqueteria();">
			</div>
		</div>
	</div>
</div>
