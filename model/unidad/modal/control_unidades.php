<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<div class="modal fade" id="inhabilitar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">INHABILITAR UNIDAD</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_inh" id="frm_inh">
					<div class="form-group input-group">
						<input type="text" class="form-control" id="id_inhabilitar" name="id" hidden="">
					</div>
					<div class="form-group input-group">
						<input type="text" class="form-control" id="numero_inhabilitar" name="name" disabled="">
					</div>
					<div class="form-group">
						<div>Fecha y hora de la inhabilitacion</div>
						<div class="row">
							<div class="col-sm-6">
								<input type="date" value="<?php echo ahora(1); ?>" class="form-control" id="fecha" name="fecha">
							</div>
							<div class="col-sm-6">
								<input type="time" value="<?php echo ahora(2); ?>" class="form-control" id="hora" name="hora">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div>Motivo de la de Inhabilitacion</div>
						<textarea class="form-control" id="motivo" name="motivo"> </textarea>
					</div>
				</form>
				<div id="respuesta_inhabilitar_unidad"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
				<input type="submit" value="INHABILITAR" class="btn btn-primary" onclick="inhabilitar();">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">ELIMINAR UNIDAD</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_del" id="frm_del">
					<div class="form-group input-group">
						<input type="text" class="form-control" id="id_eliminar" name="id" hidden="">
					</div>
					<div class="form-group input-group">
						<input type="text" class="form-control" id="numero_eliminar" name="name" disabled="">
					</div>
				</form>
				<div id="respuesta_eliminar_unidad"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
				<input type="submit" value="ELIMINAR" class="btn btn-primary" onclick="eliminar();">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="habilitar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">HABILITAR UNIDAD</h4></center>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_hab" id="frm_hab">
					<div class="form-group input-group">
						<input type="text" class="form-control" id="id_habilitar" name="id" hidden="">
					</div>
					<div class="form-group input-group">
						<input type="text" class="form-control" id="numero_habilitar" name="name" disabled="">
					</div>
					<div class="form-group">
						<div>Fecha y hora de la habilitacion</div>
						<div class="row">
							<div class="col-sm-6">
								<input type="date" value="<?php echo ahora(1); ?>" class="form-control" id="fecha" name="fecha">
							</div>
							<div class="col-sm-6">
								<input type="time" value="<?php echo ahora(2); ?>" class="form-control" id="hora" name="hora">
							</div>
						</div>
					</div>
				</form>
				<div id="respuesta_habilitar_unidad"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
				<input type="submit" value="HABILITAR" class="btn btn-primary" onclick="habilitar();">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="reactivar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <center><h4 class="modal-title" id="myModalLabel">reactivar UNIDAD</h4></center>
      </div>
      <div class="modal-body">
        <form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_reac" id="frm_reac">
          <div class="form-group input-group">
            <input type="text" class="form-control" id="id_reactivar" name="id" hidden="">
          </div>
          <div class="form-group input-group">
            <input type="text" class="form-control" id="numero_reactivar" name="name" disabled="">
          </div>
          <div class="form-group">
            <div>Fecha y hora de la habilitacion</div>
            <div class="row">
              <div class="col-sm-6">
                <input type="date" value="<?php echo ahora(1); ?>" class="form-control" id="fecha" name="fecha">
              </div>
              <div class="col-sm-6">
                <input type="time" value="<?php echo ahora(2); ?>" class="form-control" id="hora" name="hora">
              </div>
            </div>
          </div>
        </form>
        <div id="respuesta_reactivar_unidad"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
        <input type="submit" value="REACTIVAR" class="btn btn-primary" onclick="reactivar();">
      </div>
    </div>
  </div>
</div>