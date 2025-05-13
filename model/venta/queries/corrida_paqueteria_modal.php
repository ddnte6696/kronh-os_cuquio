<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
    //Obtengo el dato enviado y lo divido en variables
	$datos=explode('||',campo_limpiado($_POST['datos'],2,0));
	$id=$datos[0];
	$fecha=$datos[1];
	$origen=$datos[2];
	$nombre_envia=$datos[3];
	$destino=$datos[4];
	$nombre_recibe=$datos[5];
?>
<div class="input-group mb-3 input-group-sm">
	<div class="input-group-prepend">
		<span class="input-group-text"><strong>CORRIDA</strong></span>
	</div>
	<select  name='corrida' id='corrida_acp' class="custom-select text-body" required="">
		<option value=''>SELECCIONA UNA CORRIDA</option>
		<?php
      //Busco las rutas que van hacia ese punto
        $sentencia="
          SELECT
            a.id AS id_corrida,
            a.servicio AS servicio,
            a.hora AS hora
          FROM corrida AS a
          JOIN ruta AS b on 
            a.identificador=b.identificador and 
            b.punto_origen='$origen'
          WHERE
            a.estatus=1 and
            b.destino='$destino' and
            a.punto_origen='$origen' and
            a.fecha='".ahora(1)."'
          ORDER BY
            a.hora 
          ASC
        ";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
          //Almceno los datos en una variable
                $id_corrida=$tabla['id_corrida'];
                $servicio=$tabla['servicio'];
                $hora=campo_limpiado(transforma_hora($tabla['hora'],"12"),0,1);
              //Creeo un concatenado con los datos a enviar informacion
                $dato=campo_limpiado($id_corrida,1);
              //Imprimo los datos
                echo "<option value='$dato'>$hora - $servicio</option>";
              //
            }
          //
        }
      //
    ?>
	</select>
</div>
<script type="text/javascript">
	//habilitar buscador en los seleccionadores
    jQuery(document).ready(function($){
      $(document).ready(function() {
        $('#corrida_acp').select2();
      });
    });
</script>