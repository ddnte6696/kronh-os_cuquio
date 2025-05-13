<?php
	//
		if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Verifico que se envie un dato valido
	  if($_POST['estado']!=''){
			//obtengo los datos mandados por el formulario
				$id_corrida=campo_limpiado($_POST['id_corrida_estado'],2,0);
				$estado=campo_limpiado($_POST['estado'],2,0);
	  	//Defino la sentencia para agregar el operador y la unidad a la corrida
	  		$sentencia="
	  			UPDATE 
	  				corrida 
	  			SET 
	  				estatus=$estado
					WHERE 
						id=$id_corrida
					;
				";
			//Realizo la ejecucion de la sentencia
	      $devuelto=ejecuta_sentencia_sistema($sentencia,true);
	    //Si la insercion se realizo correctamente
	      if ($devuelto==true) {
	      	echo "
	      		<script>
	      			alert('ESTADO CAMBIADO CORRECTAMENTE');
	      			$('#cambiar_estado').modal('hide');
	      			mostrar_corridas();
	    			</script>";
	      }
	    //
		}else{
			echo "<script>alert('SELECCIONA UN ESTADO VALIDO');</script>";
			die();
		}
	//
?>