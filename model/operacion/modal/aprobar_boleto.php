<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="modal fade" id="aprobar_boleto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">APROBAR BOLETO</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_aprobar_boleto" id="frm_aprobar_boleto">
					<div class="form-group input-group" hidden>
						<input type="text" class="form-control" id="id_boleto_aproba" name="id_boleto">
					</div>
					<div class="form-group">
						<div>FOLIO</div>
						<input type="text" class="form-control" id="folio_boleto_aproba" disabled>
					</div>
					<div class="form-group">
						<div>MOTIVO DE LA APROBACION</div>
						<textarea class="form-control" id="motivo" name="motivo"> </textarea>
					</div>
				</form>
				<div id="respuesta_aprobar_boleto"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">CERRAR</button>
				<input type="submit" value="REGISTRAR" class="btn btn-sm btn-primary" onclick="aprobar_boleto();">
			</div>
		</div>
	</div>
</div>