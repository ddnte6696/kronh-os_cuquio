<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
?>
<div class="modal fade" id="agregar_destino" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">AGREGAR DESTINO A LA RUTA</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_agregar_destino" id="frm_agregar_destino">
					<div class="form-group input-group" hidden>
						<input type="text" class="form-control" id="id_destino" name="id_destino">
						<input type="text" class="form-control" id="nombre_destino" name="nombre_destino">
						<input type="text" class="form-control" id="dato_destino" name="dato">
					</div>
					<div class="form-group">
								<input type="text" class="form-control" id="nombre_destino2" disabled>
							</div>
					<div class="row">
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<input type="text" name='precio_normal' class="form-control"  required="" placeholder="Precio normal">
							</div>
						</div>
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<input type="text" name='precio_medio' class="form-control"  required="" placeholder="Precio medio">
							</div>
						</div>
					</div>
				</form>
				<div id="respuesta_agregar_destino"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">CERRAR</button>
				<input type="submit" value="REGISTRAR" class="btn btn-sm btn-primary" onclick="agregar_destino();">
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($){
    $(document).ready(function() {
      $('#operador').select2();
    });
  });
	$('#operador').select2({
		dropdownParent: $('#agregar_destino')
	});

</script>