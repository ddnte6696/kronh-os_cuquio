<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  	$usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_liquidacion=campo_limpiado($_POST['dato'],2,0);
    $dato=$_POST['dato'];
  //Defino la sentencia de busqueda
    $sentencia="SELECT * FROM corte where id=$id_liquidacion";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Almaceno los datos obtenidos en sus variables
            $id=$tabla['id'];
            $usuario=$tabla['usuario'];
            $fecha=$tabla['fecha'];
            $punto_venta=$tabla['punto_venta'];
            $importe_boletos_sistema=$tabla['importe_boletos_sistema'];
            $importe_boletos_talonario=$tabla['importe_boletos_talonario'];
            $importe_paquetes_sistema=$tabla['importe_paquetes_sistema'];
            $importe_paquetes_talonario=$tabla['importe_paquetes_talonario'];
          //Obtengo los datos de la persona que vendio
            $dto_usuario=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM usuarios WHERE clave='$usuario'");
          //Calculo el total de la liquidacion
            $total=number_format($tabla['importe_boletos_sistema']+$tabla['importe_boletos_talonario']+$tabla['importe_paquetes_sistema']+$tabla['importe_paquetes_talonario'],2);
        }
      //
    }
  //
?>
<script type="text/javascript">
	//Funcion para cargar el formulario de liquidacion especifico
    function cambia_pagina(){
      var pagina=$("#pagina").val();
      var dato=<?php echo "'".$dato."'"; ?>;
      var url="model/liquidacion/forms/"+pagina+"_liq_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#respuesta_pagina").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#respuesta_pagina').html(datos);
        }
      });
    }
	window.onload(cambia_pagina());
</script>
<div class="card">
  <div class="card-header">
    <h5><?php echo "LUIQUIDACION DEL DIA ".campo_limpiado(transforma_fecha($fecha),0,1)." DE $dto_usuario EN $punto_venta"; ?></h5>
  </div>
  <div class="card-body">
  	<div class="input-group mb-3 input-group-sm">
  		<select name='pagina' id="pagina" class="custom-select" style="color:black"  onchange="cambia_pagina()" required="">
  			<option value='boletos'>BOLETOS</option>
  			<option value='paquetes'>PAQUETERIA</option>
        <option value='final'>FINAL</option>
  		</select>
  		<input type="text" name="target" id="target" value="<?php echo $dato ?>"hidden>
  	</div>
  	<div id="respuesta_pagina"></div>
  </div>
</div>