<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la taquilla del usuario actual
    $taquilla_actual=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
  //Defino el dato a enviar al formulario
    $dato_actual=campo_limpiado($taquilla_actual,1,0);
  //
?>

<div class="modal fade" id="cambiar_taquilla" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<center><h4 class="modal-title" id="myModalLabel">Cambiar la taquilla de trabajo actual</h4></center>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_cambiar_taquilla" id="frm_cambiar_taquilla">
              <div class="form-group">
                <select  name='taquilla' class="custom-select" required="">
                  <?php
		                //Defino la sentencia a ejecutar
		                  $sentencia="SELECT * FROM destinos where punto=true and destino<>'$taquilla_actual'";
		                //Ejecuto la sentencia y almaceno lo obtenido en una variable
		                  $resultado_sentencia=retorna_datos_sistema($sentencia);
		                //Identifico si el reultado no es vacio
		                  if ($resultado_sentencia['rowCount'] > 0) {
		                    //Almaceno los datos obtenidos
		                      $resultado = $resultado_sentencia['data'];
		                    // Recorrer los datos y llenar las filas
		                      foreach ($resultado as $tabla) {
		                        //Creo una variable especial
		                          $id_origen=$tabla['id'];
		                          $origen=$tabla['destino'];
		                        //Creeo un dato especial del destino
		                          $dato=campo_limpiado($origen,1,0);
		                        //Imprimo el campo
		                          echo "<option value='$dato'>$origen</option>";
		                        //
		                      }
		                    //
		                  }
		                //
		              ?>
                </select>
              </div>
					</form>
				</div>
				<div id="respuesta_cambiar_taquilla"></div>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Cambiar de taquilla" class="btn btn-sm btn-block btn-success" onclick="cambiar_taquilla();">
			</div>
		</div>
	</div>
</div>