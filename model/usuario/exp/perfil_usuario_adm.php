<!--Administrador>>Informacion de usuarios-->
<?php
	/*error_reporting(E_ALL);
	ini_set("display_errors", 1);*/
	session_start();
	$puesto_2=$_SESSION['cuquio']['puesto'];
	$id=$_POST['id'];
	include_once '../../connection/cuquio.sql.db.php';
	$sentencia="
		SELECT
			a.nombre,
			a.apellido,
			a.clave,
			a.empresa,
			a.division,
			a.visual,
			a.photo,
			a.telefono,
			a.correo,
			a.puesto,
			a.admin,
			b.empresa as n_empresa,
			c.puesto as n_puesto,
			d.division as n_division
		FROM usuarios as a
			LEFT JOIN empresas as b on a.empresa=b.id
			LEFT JOIN puestos as c on a.puesto=c.id
			LEFT JOIN division as d on a.division=d.id
		WHERE a.id=$id
	";
	$query=$conn->prepare($sentencia);
	$query->execute();
	while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
		//Informacion del operador
			$nombre=$tabla['nombre'];
			$apellido=$tabla['apellido'];
			$clave=$tabla['clave'];
			$id_empresa=$tabla['empresa'];
			$id_division=$tabla['division'];
			$visual=$tabla['visual'];
			$imagen=$tabla['photo'];
			$phone=$tabla['telefono'];
			$mail=$tabla['correo'];
			$id_puesto=$tabla['puesto'];
			$admin=$tabla['admin'];
		//Informacion adicional
			$empresa=$tabla['n_empresa'];
			$puesto=$tabla['n_puesto'];
			$division=$tabla['n_division'];
		//
	}
?>
<input type="submit" value="CERRAR EXPEDIENTE" class="btn btn-sm btn-danger btn-block btn-sm" onclick="limpiar();">
<div class="card text-center">
	<div class="card-header"><h5>DATOS DEL USUARIO N° <?php echo $id ?></h5></div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<img src='img/usuarios/<?php echo $imagen ?>' class='card-img-top' style='width: 50%'>
			</div>
			<div class="col-md-6 col-lg-6">
				<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_perfil_usuario" id="frm_perfil_usuario">
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="customFile" name='imagen'>
						<label class="custom-file-label" for="customFile">Subir nueva Imagen</label>
					</div>
					<table class="table table-sm">
						<tbody>
							<?php
								echo "
									<input type='text' name='id' value='$id' hidden='' >
									<tr>
										<th>Nombre</th>
										<td>
											<input type='text' id='nombre' name='nombre' class='form-control'  value='$nombre' required='' >
											<input type='text' id='apellido' name='apellido' class='form-control'  value='$apellido' required='' >
										</td>
									</tr>
									<tr>
										<th>Telefono</th>
										<td><input type='text' name='phone' class='form-control'  value='$phone' required=''></td>
									</tr>
									<tr>
										<th>Correo electronico</th>
										<td><input type='text' name='mail' class='form-control'  value='$mail' required=''></td>
									</tr>
									<tr>
										<th>Clave</th>
										<td><input type='text' name='clave' class='form-control'  value='$clave' required=''></td>
									</tr>
									<tr>
										<th>Puesto</th>
										<td>
											<select  name='puesto' class='custom-select' required=''>
												<option value='$id_puesto'>$puesto</option>";
												$res=$conn->prepare("SELECT * FROM puestos where id<>$id_puesto");
												$res->execute();
													while ($tabla=$res->fetch(PDO::FETCH_ASSOC)){
													$id_puesto2=$tabla['id'];
													$puesto2=$tabla['puesto'];
													echo "<option value='$id_puesto2'>$puesto2</option>";
												}
												echo "
											</select>
										</td>
									</tr>
									<tr>
										<th>ENTE ADMINISTRADOR</th>
										<td>
											<select  name='admin' class='custom-select'>";
												if ($admin==null) {
													echo "
														<option value='null'>NO</option>
														<option value='1'>SI</option>
													";
												}else{
													echo "
														<option value='1'>SI</option>
														<option value='null'>NO</option>
													";
												}
												echo "
											</select>
										</td>
									</tr>
									<tr>
										<th>Empresa</th>
										<td>
											<select  name='empresa' class='custom-select' required=''>
												<option value='$id_empresa'>$empresa</option>";
												$res=$conn->prepare("SELECT * FROM empresas where id<>$id_empresa");
												$res->execute();
												while ($tabla=$res->fetch(PDO::FETCH_ASSOC)){
													$id_empresa2=$tabla['id'];
													$empresa=$tabla['empresa'];
													echo "<option value='$id_empresa2'>$empresa</option>";
												}
												echo "
											</select>
										</td>
									</tr>
									<tr>
										<th>Division</th>
										<td>
											<select  name='division' class='custom-select' required=''>
												<option value='$id_division'>$division</option>";
												$res=$conn->prepare("SELECT * FROM division where id<>$id_division");
												$res->execute();
												while ($tabla=$res->fetch(PDO::FETCH_ASSOC)){
													$id_division2=$tabla['id'];
													$division=$tabla['division'];
													echo "<option value='$id_division2'>$division</option>";
												}
												echo "
											</select>
										</td>
									</tr>
								";
							?>    
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div id="respuesta_perfil_usuario"></div>
		<input type="submit" value="Actualizar perfil" class='btn btn-success btn-block btn-sm' onclick="actualizar_datos();">
		<button type="button" class="btn btn-sm btn-danger btn-block" onclick="contra('<?php echo $id ?>')">Cambiar contraseña</button>
	</div>
</div>
<?php if (($id_puesto==4)||($id_puesto==7)) { ?>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="card">
				<div class="card-header"><h5>Pendientes</h5></div>
				<div class="card-body">
					<table class="table table-bordered table-sm" id="tabla2">
						<thead>
							<tr>
								<th>Registro</th>
								<th>Descripcion</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$query=$conn->prepare("SELECT * FROM pendientes where id_usuario=$id and visual=1");
								$query->execute();
								while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
									$fecha=$tabla['fecha_registro'];
									$hora=$tabla['hora_registro'];
									$descripcion=$tabla['descripcion'];
									echo "
										<tr>
											<td>$fecha - $hora</td>
											<td>$descripcion</td>
										</tr>
									";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="card">
				<div class="card-header"><h5>Pendientes finalizados</h5></div>
				<div class="card-body">
					<table class="table table-bordered table-sm" id="tabla3">
						<thead>
							<tr>
								<th>Registro / Finalizacion</th>
								<th>Descripcion</th>
								<th>Comentarios</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$query=$conn->prepare("SELECT * FROM pendientes where id_usuario=$id and visual=0");
								$query->execute();
								while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
									$fecha=$tabla['fecha_registro'];
									$hora=$tabla['hora_registro'];
									$fecha2=$tabla['fecha_fin'];
									$hora2=$tabla['hora_fin'];
									$descripcion=$tabla['descripcion'];
									$comentario=$tabla['comentario'];
									echo "
										<tr>
											<td>$fecha $hora / $fecha2 $hora2</td>
											<td>$descripcion</td>
											<td>$comentario</td>
										</tr>
									";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<div class="col-md-12 col-lg-12">
			<div class="card">
				<div class="card-header"><h5>Ingresos a sistema</h5></div>
				<div class="card-body">
					<table class="table table-bordered table-sm" id="tabladesc">
						<thead>
							<tr>
								<th>Ultimo ingreso</th>
								<th>Direccion</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$query=$conn->prepare("SELECT * FROM registros where usuario=$id");
								$query->execute();
								while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
									$descripcion=$tabla['ingreso'];
									$comentario=$tabla['direccion'];
									echo "
										<tr>
											<td>$descripcion</td>
											<td>$comentario</td>
										</tr>
									";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?php include_once('../modales/contrasena.php'); ?>
<script>
	//funcion para llamada del modal
		function contra(datos){
			$('#cambio_contraseña').modal('show');
			$('#id').val(datos);
		}
	//Funcion para actualizacion de datos
		function actualizar_datos(){
			$.ajax({
				type: "POST",
				url: "model/update/info_user_adm.php",
				data: $("#frm_perfil_usuario").serialize(),
				beforeSend: function(){$("#respuesta_perfil_usuario").html("<div class='spinner-border'></div>");},
				success: function(data){$("#respuesta_perfil_usuario").html(data);},
			});
			return false;
		}
	//Funcion de cambio de contraseña
		function contraseña(){
			$.ajax({
				type: "POST",
				url: "model/update/contraseña.php",
				data: $("#frm_cambio_contraseña").serialize(),
				beforeSend: function(){$("#respuesta_perfil_usuario_2").html("<div class='spinner-border'></div>");},
				success: function(data){$("#respuesta_perfil_usuario_2").html(data);},
			});
			return false;
		}
	//script para vista del archivo cargado
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
	//Script de datatables
		$(document).ready( function () {
			var table = $('#tabla2').DataTable( {
				responsive: true,
				"pagingType":"full_numbers",
				dom: 'Bfrtip',
				buttons:{
					buttons:[
						{ extend: 'excel', text:'Descargar Excel' },
						{ extend: 'print', text:'Imprimir' },
						{ extend: 'copy', text:'Copiar' },
					],
				},
			});
			table.on( 'responsive-resize', function ( e, datatable, columns ) {
				var count = columns.reduce( function (a,b) {return b === false ? a+1 : a;}, 0 );
				console.log( count +' column(s) are hidden' );
			});
		});

		$(document).ready( function () {
			var table = $('#tabla3').DataTable( {
				responsive: true,
				"pagingType":"full_numbers",
				dom: 'Bfrtip',
				buttons:{
					buttons:[
						{ extend: 'excel', text:'Descargar Excel' },
						{ extend: 'print', text:'Imprimir' },
						{ extend: 'copy', text:'Copiar' },
					],
				},
			});
			table.on( 'responsive-resize', function ( e, datatable, columns ) {
				var count = columns.reduce( function (a,b) {return b === false ? a+1 : a;}, 0 );
				console.log( count +' column(s) are hidden' );
			});
		});

		$(document).ready( function () {
			var table = $('#tabladesc').DataTable( {
				responsive: true,
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
				},
				"order": [ 0, 'desc' ],
				"pagingType":"full_numbers",
				dom: 'Bfrtip',
				buttons:{
					buttons:[
					{ extend: 'excel', text:'Descargar Excel' },
					{ extend: 'print', text:'Imprimir' },{ extend: 'copy', text:'Copiar' },
					],
				},
			} );
			table.on( 'responsive-resize', function ( e, datatable, columns ) {
				var count = columns.reduce( function (a,b) {
					return b === false ? a+1 : a;
				}, 0 );
				console.log( count +' column(s) are hidden' );
			} );
		} );
	//
</script>