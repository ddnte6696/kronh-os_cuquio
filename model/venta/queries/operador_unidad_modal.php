<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
    //
?>
<div class="input-group mb-3 input-group-sm">
	<div class="input-group-prepend">
		<span class="input-group-text"><strong>OPERADOR</strong></span>
	</div>
	<select  name='operador' id='operador' class="custom-select text-body" required="">
		<option value=''>SELECCIONA UN OPERADOR</option>
		<?php
	        //Defino la sentencia a ejecutar
	          $sentencia="SELECT * FROM operadores where visual=1 and empresa=1 and (division=1 or division=3)";
	        //Ejecuto la sentencia y almaceno lo obtenido en una variable
	          $resultado_sentencia=retorna_datos_sistema($sentencia);
	        //Identifico si el reultado no es vacio
	          if ($resultado_sentencia['rowCount'] > 0) {
	            //Almaceno los datos obtenidos
	              $resultado = $resultado_sentencia['data'];
	            // Recorrer los datos y llenar las filas
	              foreach ($resultado as $tabla) {
	                //Creo una variable especial
	                  $id=$tabla['clave'];
	                  $nombre_operador=$tabla['nombre']." ".$tabla['apellido'];
	                //Creeo un dato especial del destino
	                  $dato=campo_limpiado($id,1,0);
	                //Imprimo el campo
	                  echo "<option value='$dato'>$nombre_operador</option>";
	                //
	              }
	            //
	          }
	        //
	      ?>
	</select>
</div>

<div class="input-group mb-3 input-group-sm">
	<div class="input-group-prepend">
		<span class="input-group-text"><strong>UNIDAD</strong></span>
	</div>
	<select  name='unidad' id='unidad' class="custom-select text-body" required="">
		<option value=''>SELECCIONA UNA UNIDAD</option>
		<?php
	        //Defino la sentencia a ejecutar
	          $sentencia="SELECT * FROM unidades where visual=1 and empresa=1 and (division=1 or division=3)";
	        //Ejecuto la sentencia y almaceno lo obtenido en una variable
	          $resultado_sentencia=retorna_datos_sistema($sentencia);
	        //Identifico si el reultado no es vacio
	          if ($resultado_sentencia['rowCount'] > 0) {
	            //Almaceno los datos obtenidos
	              $resultado = $resultado_sentencia['data'];
	            // Recorrer los datos y llenar las filas
	              foreach ($resultado as $tabla) {
	                //Creo una variable especial
	                  $id=$tabla['id'];
	                  $numero=$tabla['numero'];
	                //Creeo un dato especial del destino
	                  $dato=campo_limpiado($id,1,0);
	                //Imprimo el campo
	                  echo "<option value='$dato'>$numero</option>";
	                //
	              }
	            //
	          }
	        //
	      ?>
	</select>
</div>