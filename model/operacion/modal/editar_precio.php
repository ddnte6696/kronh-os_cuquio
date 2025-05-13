<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
?>
<div class="modal fade" id="editar_destino" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">EDITAR PRECIO DEL DESTINO</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_editar_destino" id="frm_editar_destino">
					<div class="form-group input-group" hidden>
						<input type="text" class="form-control" id="id_destino_editar" name="id_destino">
						<input type="text" class="form-control" id="nombre_destino_editar" name="nombre_destino">
						<input type="text" class="form-control" id="dato_destino_editar" name="dato">
					</div>
					<div class="form-group">
								<input type="text" class="form-control" id="nombre_destino2_editar" disabled>
							</div>
					<div class="row">
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<input type="text" name='precio_normal' id='precio_normal_editar' class="form-control"  required="" placeholder="Precio normal">
							</div>
						</div>
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<input type="text" name='precio_medio' id='precio_medio_editar' class="form-control"  required="" placeholder="Precio medio">
							</div>
						</div>
					</div>
				</form>
				<div id="respuesta_editar_destino"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">CERRAR</button>
				<input type="submit" value="REGISTRAR" class="btn btn-sm btn-primary" onclick="editar_destino();">
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
		dropdownParent: $('#editar_destino')
	});

</script>