<?php
	//
		if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Obtengo los datos enviados por el formulario
		$peso=campo_limpiado($_POST['peso'],0,0);
		$cantidad=campo_limpiado($_POST['cantidad'],0,0);
		$dat_destino=explode("||",campo_limpiado($_POST['destino'],2,0));
    $id_destino=campo_limpiado($dat_destino[0],0,0);
    $destino=campo_limpiado($dat_destino[1],0,0);
    $precio=campo_limpiado($dat_destino[2],0,0);
  //Reviso la cantidad de paquetes que se van a enviar y sino envio un mensaje de error
    if ($cantidad > 0) {
		  //Calculo el multiplicador segun el peso
		    if ($peso <= 20) {
		      $multiplicador=1;
		    }else if($peso > 20 && $peso <= 30){
		      $multiplicador=1.6;
		    }else if($peso > 30 && $peso <= 40){
		      $multiplicador=2;
		    }else if($peso > 40 && $peso <= 50){
		      $multiplicador=2.6;
		    }else if($peso > 50 && $peso <= 60){
		      $multiplicador=3;
		    }else if($peso > 60 && $peso <= 70){
		      $multiplicador=3.6;
		    }else if($peso > 70 && $peso <= 80){
		      $multiplicador=4;
		    }else if($peso > 80 && $peso <= 90){
		      $multiplicador=4.6;
		    }else if($peso > 90 && $peso <= 100){
		      $multiplicador=5;
		    }else{
		    	//Si el peso no entra en ninguna de las categorias
			    	echo "
				    	<script>
				      	alert('EL PESO EXCEDE LO PERMITIDO PARA SU TRANSPORTE');
				      	document.getElementById('boton_registro').setAttribute('disabled','');
				    	</script>
			      ";
			    //Detengo la ejecucion del programa
		      	die();
		      //
		    }
		  //Multiplico el precio por el mmultiplicador y despues por la cantidad de paquetes
		    $total=number_format((($multiplicador*$precio)*$cantidad),0,'');
		  //Imprimo el mensaje de resultado
	    	echo "
		    	<script>
		      	document.getElementById('boton_registro').removeAttribute('disabled');
		      	$('#importe').val($total);
		    	</script>
	      ";
	    //
    }else{
    	//Si el peso no entra en ninguna de las categorias
	    	echo "
		    	<script>
		      	alert('LA CANTIDAD DE PAQUETES NO PUEDE SER 0');
		      	document.getElementById('boton_registro').setAttribute('disabled','');
		    	</script>
	      ";
	    //Detengo la ejecucion del programa
      	die();
      //
    }
  //
?>