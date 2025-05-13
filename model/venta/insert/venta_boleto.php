<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
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
	$(document).ready(imprimirDiv('respuesta_corridas'));
</script>
<?php
	//Realizo la insercion de los boletos y obtengo la referencia de retorno;
		$referencia=registra_boletos($_SESSION['oyg_vb']);
	//Reviso si la referencia no es vacia
		if ($referencia==campo_limpiado($_SESSION['oyg_vb']['referencia'],2,0)) {
			//Creeo un contatenado de folios en nulo y un contador
				$folio_concatenador=null;
				$asiento_concatenador=null;
				$folio_contador=null;
				$asiento_contador=null;
				$total=null;
			//Obtengo los folios de los boletos y los concateno en una variable
				$sentencia="SELECT precio,id,asiento FROM boleto where referencia='$referencia'";
			//Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
            	//Concateno el folio
            		$folio_concatenador.=$tabla['id'].", ";
            		$asiento_concatenador.=$tabla['asiento'].", ";
            		$total+=$tabla['precio'];
            	//Sumo la cantidad
            		$folio_contador++;
            		$asiento_contador++;
            	//
            }
          //
        }
      //Verifico si el origen es igual a alguno de los puntos iniciales o finales de la ruta
      //Elimino la ultima coma y espacio del concatenado
        $folio_concatenador=substr($folio_concatenador, 0, -2);
        $asiento_concatenador=substr($asiento_concatenador, 0, -2);
      //Reviso el dato del contador y creeo un texto
        if ($folio_contador==1) { $palabra='FOLIO'; }else{ $palabra='FOLIOS'; }
      //Obtengo los datos complementarios
		    $fecha=campo_limpiado($_SESSION['oyg_vb']['fecha'],2,0);
		    $taquilla=campo_limpiado($_SESSION['oyg_vb']['taquilla'],2,0);
		    $origen=campo_limpiado($_SESSION['oyg_vb']['origen'],2,0);
		    $id_destino=campo_limpiado($_SESSION['oyg_vb']['id_destino'],2,0);
		    $nombre_destino=campo_limpiado($_SESSION['oyg_vb']['nombre_destino'],2,0);
		    $precio_destino=campo_limpiado($_SESSION['oyg_vb']['precio_destino'],2,0);
		    $id_punto_inicial=campo_limpiado($_SESSION['oyg_vb']['id_punto_inicial'],2,0);
		    $punto_inicial=campo_limpiado($_SESSION['oyg_vb']['punto_inicial'],2,0);
		    $id_corrida=campo_limpiado($_SESSION['oyg_vb']['id_corrida'],2,0);
		    $corrida=campo_limpiado($_SESSION['oyg_vb']['corrida'],2,0);
		    $hora=campo_limpiado($_SESSION['oyg_vb']['hora'],2,0);
		  //Identifico si el punto de origen es igual al inicio de la ruta para definir palabras clave
		    if ($folio_contador==1) {
        	if ($origen==$punto_inicial) { $palabra_operador="ASIENTO"; }else{ $palabra_operador="TURNO"; }
        }else{
        	if ($origen==$punto_inicial) { $palabra_operador="ASIENTOS"; }else{ $palabra_operador="TURNOS"; }
        }
		    if ($origen==$punto_inicial) { $palabra_usuario="ASIENTO"; }else{ $palabra_usuario="TURNO"; }
      //Comienzo la impresion del boleto
        //Imprimo la parte principal de la tabla
        	echo "
        		<table class='table table-responsive table-sm'>
        			<tbody>";
				        //Encabezados de operador
				    			echo "
				        		<tr>
											<th colspan='2' class='text-center'>
												<img src='".LOGO_YAHUALICA."' class='img-fluid' style='width: 200px' />
											</th>
										</tr>
										<tr>
											<th colspan='2' class='text-center'>OMNIBUS YAHUALICA GUADALAJARA S.A. DE CV.</th>
										</tr>
										<tr>
											<th colspan='2' class='text-center'>COMPROBANTE DE OPERADOR</th>
										</tr>
				    			";
				    		//Datos principales para el operador
				    			echo "
					    			<tr>
											<th>$palabra</th>
											<td><strong>$folio_concatenador</strong></td>
										</tr>
										<tr>
											<th>CORRIDA</th>
											<td style='font-size: 12px;'>$corrida</td>
										</tr>
										<tr>
											<th>FECHA Y HORA</th>
											<td>$fecha $hora</td>
										</tr>
										<tr>
											<th>$palabra_operador</th>
											<td><strong>$asiento_concatenador</strong></td>
										</tr>
										<tr>
											<th>ORIGEN</th>
											<td>$origen</td>
										</tr>
										<tr>
											<th>DESTINO</th>
											<td>$nombre_destino</td>
										</tr>
										<tr>
											<th>TOTAL</th>
											<td>$ ".number_format($total,0)."</td>
										</tr>
										<tr>
											<td colspan='2' class='text-center'><strong>FECHA Y HORA DE IMPRESION</strong></th>
											</tr>
										<tr>
											<td colspan='2' class='text-center'>".ahora(3)."</td>
										</tr>
										<tr>
											<td colspan=\"2\" >
												<hr media=\"print\" style=\"border-bottom: 6px solid;\">
											</td>
										</tr>
									";
								//Obtengo los folios de los boletos y los concateno en una variable
									$sentencia="SELECT * FROM boleto where referencia='$referencia'";
								//Ejecuto la sentencia y almaceno lo obtenido en una variable
					        $resultado_sentencia=retorna_datos_sistema($sentencia);
					      //Identifico si el reultado no es vacio
					        if ($resultado_sentencia['rowCount'] > 0) {
					          //Almaceno los datos obtenidos
					            $resultado = $resultado_sentencia['data'];
					          // Recorrer los datos y llenar las filas
					            foreach ($resultado as $tabla) {
					            	//Almaceno los datos obtenidos en variables
						            	$id=$tabla['id'];
						            	$tipo=$tabla['tipo'];
						            	$pasajero=$tabla['pasajero'];
						            	$asiento=$tabla['asiento'];
						            	$precio=$tabla['precio'];
					            	//Comienzo la impresion de la informacion
					            		echo "
						            		<tr>
															<th colspan='2' class='text-center'>
																<img src='".LOGO_YAHUALICA."' class='img-fluid' style='width: 200px' />
															</th>
														</tr>
														<tr>
															<th colspan='2' class='text-center'>OMNIBUS YAHUALICA GUADALAJARA S.A. DE CV.</th>
														</tr>
														<tr>
															<th colspan='2' class='text-center'>COMPROBANTE DE PASAJERO</th>
														</tr>
									    			<tr>
															<th>FOLIO</th>
															<td><strong>$id</strong></td>
														</tr>
														<tr>
															<th>CORRIDA</th>
															<td style='font-size: 12px;'>$corrida</td>
														</tr>
														<tr>
															<th>FECHA Y HORA</th>
															<td>$fecha $hora</td>
														</tr>
														<tr>
															<th>$palabra_usuario</th>
															<td><strong>$asiento</strong></td>
														</tr>
														<tr>
															<th>ORIGEN</th>
															<td>$origen</td>
														</tr>
														<tr>
															<th>DESTINO</th>
															<td>$nombre_destino</td>
														</tr>
														<tr>
															<th>PASAJERO</th>
															<td>$pasajero</td>
														</tr>
														<tr>
															<th>TIPO</th>
															<td>$tipo</td>
														</tr>
														<tr>
															<th>TOTAL</th>
															<td>$ ".number_format($precio,0)."</td>
														</tr>
														<tr>
															<td colspan='2' class='text-center'><strong>FECHA Y HORA DE IMPRESION</strong></th>
															</tr>
														<tr>
														<tr>
															<td colspan='2' class='text-center'><strong>VENDIDO EN:</strong></th>
															</tr>
														<tr>
															<td colspan='2' class='text-center'>$taquilla</td>
														</tr>
														<tr>
															<td colspan=\"2\" >
																<hr media=\"print\" style=\"border-bottom: 6px solid;\">
															</td>
														</tr>
													";
					            	//
					            }
					          //
					        }
					      //
								echo "
							<tbody>
						</table>
					";
				//
			//
		}
	//
?>