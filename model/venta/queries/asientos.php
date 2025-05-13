<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
?>
<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Funcion para calcular el total
    echo "
    	<script type=\"text/javascript\">
	    	function calcula_diferencia(){
		    	var total=$(\"#total\").val();
		    	var recibido=$(\"#recibido\").val();
		    	var cambio=recibido-total;
		    	$(\"#cambio\").val(cambio);
		    }
    </script>
    ";
	//Verifico que tenga datos insertados, de lo contrario, muestro un mensaje
		 if ($_SESSION['oyg_vb']['boletos']!='') {
		 	//Defino una variable para sumatoria nula
		 		$sumatoria=Null;
			//separo los registros de los boletos 
				$filas=explode('$$',$_SESSION['oyg_vb']['boletos']);
				$numero_filas=count($filas);
			//creo el encabezado de la tabla de pasajeros
				echo "
					<table class='table table-sm table-hover table-bordered'>
	          <thead>
	            <tr>
	              <th>Asiento</th>
	              <th>Nombre</th>
	              <th>Tipo</th>
	              <th>Precio</th>
	            </tr>
	          </thead>
	          <tbody>
				";
			//inicio un cilo for para imprimir la tabla
				for ($i=0; $i <$numero_filas ; $i++) {
					$columnas=explode('||',$filas[$i]);
					$numero_columnas=count($columnas);
					echo "
						<tr>
							<td>".$columnas[0]."</td>
							<td>".$columnas[1]."</td>
							<td>".$columnas[2]."</td>
							<td>$ ".number_format($columnas[3],0)."</td>
						</tr>
					";
					$sumatoria=$sumatoria+number_format($columnas[3],0);
				}
				echo "
					<tr>
						<td colspan='3'><strong>TOTAL</strong></td>
						<td>
							<input type='number' name='total' id='total' class='form-control' value='".number_format($sumatoria,0)."' disabled>
						</td>
					</tr>
					<tr>
						<td colspan='3'><strong>RECIBIDO</strong></td>
						<td>
							<input type='number' name='recibido' id='recibido' class='form-control' onchange='calcula_diferencia();'>
						</td>
					</tr>
					<tr>
						<td colspan='3'><strong>CAMBIO</strong></td>
						<td>
							<input type='number' name='cambio' id='cambio' class='form-control' value='0' disabled>
						</td>
					</tr>
				";
				echo "
					<tbody></table>
			          
				";
				echo "
					<script>
						$('#asientos').modal('hide');
						$('#ta".$columnas[0]."').removeClass('btn-primary text-light');
						$('#ta".$columnas[0]."').addClass('btn-warning disabled');
					</script>
				";
			//imprimo un boton para mandar a l final del proceso
				echo "<a class=\"btn btn-block btn-success\" data-dblclick-disabled onclick=\"imprime_boletos('".$_SESSION['oyg_vb']['referencia']."')\"><strong>IMPRIMIR BOLETOS</strong></a>";
			//
		}else{
			echo "
				<strong>
					Para agregar un pasajero, haz clic sobre alguno de los asientos en azul de la derecha.
					<br>
					Para rainicial el proceso o seleccionar otra corrida, haz clic en el boton \"VER CORRIDAS\" en la parte superior
				</strong>
			";
		}
?>

<script type="text/javascript">
  //Función para registrar una nueva cuenta bancaria
    function imprime_boletos(opcion){
      //Indico la dirección del formulario que quiero llamar
        var url="model/venta/insert/venta_boleto.php"
      //inicio el traspaso de los datos
        $.ajax({
          type: "POST",
          url:url,
          data:{dato:opcion},
          success: function(datos){$('#respuesta_corridas').html(datos);}
        });
      //
    }
  //
</script>