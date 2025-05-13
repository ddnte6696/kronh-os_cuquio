<?php
	session_start();
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
	include '../../connection/kronh-os_cuquio.sql.db.php';
	$id=htmlspecialchars($_POST['id'],ENT_QUOTES);
	$clave=htmlspecialchars($_POST['clave'],ENT_QUOTES);
	$empresa=htmlspecialchars($_POST['empresa'],ENT_QUOTES);
	$division=htmlspecialchars($_POST['division'],ENT_QUOTES);
    $nombre=htmlspecialchars($_POST['nombre'],ENT_QUOTES);
    $puesto=htmlspecialchars($_POST['puesto'],ENT_QUOTES);
    $admin=htmlspecialchars($_POST['admin'],ENT_QUOTES);
    $apellidos=htmlspecialchars($_POST['apellido'],ENT_QUOTES);
    $correo=htmlspecialchars($_POST['mail'],ENT_QUOTES);
    $telefono=htmlspecialchars($_POST['phone'],ENT_QUOTES);
    $extension=$_FILES['imagen']['name'];
    $separador=explode('.', $extension);
    $final=$clave.'.'.$separador[1];
    $hoy = getdate();
    	$ano=$hoy['year'];
    	$month=$hoy['mon'];
    	$day=$hoy['mday'];
	$f_registro="$ano-$month-$day";
		$horas=$hoy['hours'];
		$minutos=$hoy['minutes'];
	$h_registro="$horas:$minutos";
	$REDEX=$conn->prepare("SELECT * FROM usuarios WHERE clave='$clave'");
	$REDEX->execute();
	$search=$REDEX->fetch(PDO::FETCH_ASSOC);
	$dto=$search['id'];
	if (($dto==$id) || ($dto==NULL)) {
		$sql=$conn->prepare("UPDATE usuarios set clave='$clave', nombre='$nombre', apellido='$apellidos', puesto=$puesto, admin=$admin, empresa=$empresa, division=$division, correo='$correo', telefono='$telefono' where id=$id");
		$res=$sql->execute();
		if($res === TRUE){
		echo "
			<div class='alert alert-success alert-dismissible fade show'>
				<button class='close' data-dismiss='alert'>×</button>
				<strong>Realizado!</strong>
				Datos del perfil actualizados.
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
		$dir="../../../img/usuarios";
		if(!file_exists($dir)){ mkdir($dir, 0777,true); }
		$tmp_name = $_FILES["imagen"]["tmp_name"];
		if(($clave!=null) && (move_uploaded_file($tmp_name,"$dir/$final"))){
			chmod("$dir/$final", 0777);
			$photo=$conn->prepare("UPDATE usuarios SET photo='$final' where id=$id;");
			$photo->execute();
			if($photo === TRUE){
				echo "
					<div class='alert alert-info alert-dismissible fade show '>
						<button class='close' data-dismiss='alert'>×</button>
						<strong>Añadido!</strong>
						Imagen acualizada
					</div>
				";
			}
		}else{
			echo "
				<div class='alert alert-primary alert-dismissible fade show'>
					<button class='close' data-dismiss='alert'>×</button>
					No se a seleccionado ninguna imagen
				</div>
			";
		}
	}else{
		echo "
	        <div class='alert alert-primary alert-dismissible fade show'>
	          <button class='close' data-dismiss='alert'>×</button>
	          <strong>Atención!</strong>
	          Esta clave ya se encuentra registrada con otro perfil
	        </div>
	      ";
	}
?>