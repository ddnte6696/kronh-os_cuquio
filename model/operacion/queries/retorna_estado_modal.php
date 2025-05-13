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
  $estado=$datos[6];
?>
<div class="input-group mb-3 input-group-sm">
	<div class="input-group-prepend">
		<span class="input-group-text"><strong>ESTADO</strong></span>
	</div>
	<select  name='estado' id='estado_rep' class="custom-select text-body" required="">
		<option value=''>SELECCIONA UN ESTADO</option>
		<?php
      switch ($estado) {
        case '1':
          echo "
            <option value='".campo_limpiado('4',1,0)."'>ENTREGADA</option>
            <option value='".campo_limpiado('5',1,0)."'>CANCELADA</option>
          ";
        break;
        case '2':
          echo "
            <option value='".campo_limpiado('4',1,0)."'>ENTREGADA</option>
            <option value='".campo_limpiado('5',1,0)."'>CANCELADA</option>
          ";
        break;
        case '3':
          echo "
            <option value='".campo_limpiado('1',1,0)."'>VENTA REALIZADA</option>
            <option value='".campo_limpiado('2',1,0)."'>EN RUTA</option>
            <option value='".campo_limpiado('4',1,0)."'>ENTREGADA</option>
            <option value='".campo_limpiado('5',1,0)."'>CANCELADA</option>
          ";
        break;
        case '4':
          echo "
            <option value='".campo_limpiado('1',1,0)."'>VENTA REALIZADA</option>
            <option value='".campo_limpiado('2',1,0)."'>EN RUTA</option>
            <option value='".campo_limpiado('3',1,0)."'>RECIBIDA</option>
            <option value='".campo_limpiado('5',1,0)."'>CANCELADA</option>
          ";
        break;
        case '5':
          echo "
            <option value='".campo_limpiado('1',1,0)."'>VENTA REALIZADA</option>
            <option value='".campo_limpiado('4',1,0)."'>ENTREGADA</option>
          ";
        break;
      }
    ?>
	</select>
</div>