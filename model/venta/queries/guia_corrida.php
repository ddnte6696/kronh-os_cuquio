<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
    /*error_reporting(E_ALL);
  	ini_set("display_errors", 1);*/
?>
<style type="text/css">
  @media print {
  	@page { size: auto; }
	  html {
	    min-height: 100%;
	    position: relative;
	    font-family: Verdana; 
	  }
	  img {
	    display: block;
	    margin: 1em auto;
	  }
  	table {
	    font-family: Arial, sans-serif;
	    font-size: 14px;
	    border-collapse: collapse;
	  }
	}
</style>
<script type="text/javascript">
	$(document).ready(imprimirDiv('response'));
</script>
<?php
  //Defino variables para contador
		$cuenta_pasajeros=0;
		$total_pasajeros=0;
	//Obtengo el dato enviado por el fomulario y lo separo
		$datos=explode("||",$_POST['datos']);
		$id_corrida=campo_limpiado($datos[0],2,0);
		$origen=campo_limpiado($datos[1],0,1);
		$corrida=campo_limpiado($datos[2],0,1);
		$hora=campo_limpiado($datos[3],0,1);
		$fecha=campo_limpiado($datos[4],0,1);
  //Obtengo y separo los datos del origen
		$clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
	//Obtengo datos especificos de la corrida
    $sentencia="SELECT * From corrida where id=$id_corrida";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Creeo las variables del identificador
            $id=$tabla['id'];
            $identificador=$tabla['identificador'];
            $id_servicio=$tabla['id_servicio'];
            $servicio=$tabla['servicio'];
            $hora=$tabla['hora'];
            if ($tabla['operador']==Null) {
              $operador="NO ASIGNADO";
            }else{
              //Defino la sentencia para buscar el nombre del operador
                $sentencia="
                  SELECT 
                  CONCAT(nombre,' ',apellido) AS exist 
                  FROM 
                    operadores 
                  WHERE 
                    clave='".$tabla['operador']."'
                  ;
                ";
              //Obtengo el nombre del operador y lo alamceno en su variable correspodiente
                $operador=busca_existencia($sentencia);
              //
            }
            if ($tabla['unidad']==Null) {
              $unidad="NO ASIGNADO";
            }else{
              //Defino la sentencia para buscar el nombre del operador
                $sentencia="
                  SELECT 
                    numero AS exist 
                  FROM 
                    unidades 
                  WHERE 
                    id=".$tabla['unidad']."
                  ;
                ";
              //Obtengo el nombre del operador y lo alamceno en su variable correspodiente
                $unidad=busca_existencia($sentencia);
              //
            }
          //
        }
      //
    }
  //Defino un sentencia para actualizar el dato de los boletos
    $sentencia="
			UPDATE 
				boleto 
			SET 
				operador='".$tabla['operador']."', 
				unidad=".$tabla['unidad']." 
			WHERE 
				corrida='$servicio' and
				f_corrida='$fecha' and
				hora_corrida='$hora' and
				origen='$origen'
			;
		";
	//Realizo la ejecucion de la sentencia
    $devuelto2=ejecuta_sentencia_sistema($sentencia,true);
  //Defino la sentencia para agregar el operador y la unidad a las paqueterias
		$sentencia="
			UPDATE 
				paquete 
			SET 
				operador='".$tabla['operador']."', 
				unidad=".$tabla['unidad']." 
			WHERE 
				corrida=$id_corrida
			;
		";
	//Realizo la ejecucion de la sentencia
    $devuelto3=ejecuta_sentencia_sistema($sentencia,true);
	//Imprimo la tabla
    echo "
		  <table class='table table-sm table-responsive'>
		  	<tbody>
		  		<tr>
		  			<th colspan='4' class='text-center'>
							<img src='".LOGO_YAHUALICA."' class='img-fluid' style='width: 200px' />
						</th>
					</tr><tr>
						<th colspan='4' class='text-center'>OMNIBUS YAHUALICA GUADALAJARA S.A. DE CV.</th>
					</tr><tr>
						<th colspan='4' class='text-center'>GUIA DE SALIDA DE CORRIDA</th>
					</tr><tr>
						<th>TAQUILLA</th>
						<td colspan='3'>$origen</td>
					</tr><tr>
						<th>CORRIDA</th>
						<td colspan='3' style='font-size: 12px;'>$corrida</td>
					</tr><tr>
						<th>FECHA</th>
						<td colspan='3'>$fecha</td>
					</tr><tr>
						<th>HORA</th>
						<td colspan='3'>$hora</td>
					</tr><tr>
						<th>AUTOBUS</th>
						<td colspan='3'>$unidad</td>
					</tr><tr>
						<th>OPERADOR</th>
						<td colspan='3' style='font-size:small;'>$operador</td>
					</tr>
					<tr>
						<th colspan='4' class='text-center'>PASAJEROS</th>
					</tr><tr>
						<th class='text-center'>ASIENTO</th>
						<th class='text-center'>TIPO</th>
						<th class='text-center'>DESTINO</th>
						<th class='text-center'>IMPORTE</th>
					</tr>";
					//Defino la sentencia para obtener los boletos de esta guia
						$sentencia="
							SELECT * FROM 
								boleto 
							WHERE 
								f_corrida='$fecha' and 
								corrida='$corrida' AND 
								origen='$origen' AND 
								hora_corrida='$hora' AND 
								(estado=2 OR estado=2)
							;
						";
					//Ejecuto la sentencia y almaceno lo obtenido en una variable
				    $resultado_sentencia=retorna_datos_sistema($sentencia);
				  //Identifico si el reultado no es vacio
				    if ($resultado_sentencia['rowCount'] > 0) {
				      //Almaceno los datos obtenidos
				        $resultado = $resultado_sentencia['data'];
				      // Recorrer los datos y llenar las filas
				        foreach ($resultado as $tabla) {
				          //Creeo las variables del identificador
				            $asiento=$tabla['asiento'];
				            $tipo=$tabla['tipo'];
				            $destino=$tabla['destino'];
				            $precio=$tabla['precio'];

				          //Se aumentan las variables de contador y sumatoria
				            $cuenta_pasajeros++;
					        	$total_pasajeros+=$precio;
					        //Imprimo los datos de la tabla
					        	echo "
						        	<tr>
							        	<td class='text-center'>$asiento</td>
							        	<td class='text-center'>$tipo</td>
							        	<td style='font-size:small;'>$destino</td>
							        	<th class='text-center'>$ ".number_format($precio,0)."</th>
						        	</tr>
						        ";
				        }
				      //
				    }
				  //Continuo con los datos faltantes
			    echo "
        	<tr>
	        	<th colspan='2' class='text-center'><strong>PASAJEROS</strong></th>
	        	<th colspan='2' class='text-center'><strong>TOTAL</strong></th>
        	</tr>
        	<tr>
	        	<td colspan='2' class='text-center'><strong>$cuenta_pasajeros</strong></th>
	        	<th colspan='2' class='text-center' style='font-size: 20px;'><strong>$ ".number_format($total_pasajeros,0)."</strong></th>
        	</tr>
        	<tr>
	        	<td colspan='2' class='text-center'>FECHA Y HORA DE IMPRESION</th>
	        	<td colspan='2' class='text-center'>".ahora(3)."</td>
        	</tr>
		    </tbody>
			</table>
		";
	//
?>