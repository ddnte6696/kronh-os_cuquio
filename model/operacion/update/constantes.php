<?php
	session_start();
	include '../../connection/kronh-os_cuquio.sql.db.php';
	$precio_adblue=htmlspecialchars($_POST['precio_adblue'],ENT_QUOTES);
	$precio_diesel=htmlspecialchars($_POST['precio_diesel'],ENT_QUOTES);

    $hoy = getdate();
    	$ano=$hoy['year'];
    	$month=$hoy['mon'];
    	$day=$hoy['mday'];
	$f_registro="$ano-$month-$day";
		$horas=$hoy['hours'];
		$minutos=$hoy['minutes'];
	$h_registro="$horas:$minutos";

		$sql=$conn->prepare("UPDATE constantes set precio_adblue=$precio_adblue, precio_diesel=$precio_diesel");
		$res=$sql->execute();
		if($res === TRUE){
		echo "
			<div class='alert alert-success alert-dismissible fade show'>
				<button class='close' data-dismiss='alert'>×</button>
				<strong>Realizado!</strong>
				Datos actualizados.
			</div>
	      ";
	    }else{
	      echo "
	        <div class='alert alert-danger alert-dismissible fade show'>
	          <button class='close' data-dismiss='alert'>×</button>
	          <strong>Error!</strong>
	          Revisa los datos eh intenta de nuevo
	        </div>
	      ";
	    }
?>