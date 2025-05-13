<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="modal fade" id="eliminar_carga_diesel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">ELIMINAR CARGA DE DIESEL</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_eliminar_carga_diesel" id="frm_eliminar_carga_diesel">
					<div class="form-group input-group" hidden>
						<input type="text" class="form-control" id="id_carga_elimina" name="id_carga">
					</div>
					<div class="form-group">
						<div><strong># DE CARGA</strong></div>
						<input type="text" class="form-control" id="folio_carga_elimina" disabled>
					</div>
				</form>
				<div id="respuesta_eliminar_carga_diesel"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">CANCELAR</button>
				<input type="submit" value="ELIMINAR" class="btn btn-sm btn-primary" onclick="eliminar_carga_diesel();">
			</div>
		</div>
	</div>
</div>