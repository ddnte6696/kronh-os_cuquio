<?php
	session_start();
	$usuario=$_SESSION['kronh-os']['id'];
	include '../../connection/kronh-os.sql.db.php';
	//obtengo los datos a insertar
		$id=htmlspecialchars($_POST['id'],ENT_QUOTES);
		$target=htmlspecialchars($_POST['target'],ENT_QUOTES);
    	$nombre=htmlspecialchars($_POST['nombre'],ENT_QUOTES);
	//busco el apuntador y asigno la ubicacion local
	    switch ($target) {
	    	case 'id_rta':
	    		$dir="../../docs/rta/$id";
	    		break;
	    	case 'id_rta_cp':
	    		$dir="../../docs/rta_cp/$id";
	    		break;
	    	case 'id_inci':
	    		$dir="../../docs/incidencia/$id";
	    		break;
	    	case 'id_rep':
	    		$dir="../../docs/inspeccion/$id";
	    		break;
	    	case 'id_siniestro':
	    		$dir="../../docs/siniestros/$id";
	    		break;
	    	case 'id_operador':
	    		$dir="../../docs/operadores/$id";
	    		break;
	    	case 'id_respaldo':
	    		$dir="../../docs/respaldo/$id";
	    		break;
	    	case 'id_unidad':
	    		$dir="../../docs/unidad/$id";
	    		break;
	    	case 'id_infraccion':
	    		$dir="../../docs/infraccion/$id";
	    		break;
	    }
    //obtengo el nombre completo del archivo
    	$extension_1=$_FILES['archivo']['name'];
    //divido el nombre por puntos
    	$separador=explode('.', $extension_1);
   	//Obtengo la longitus del array y le resto 1 para obtener el ultimo dato del arreglo
    	$cuenta=count($separador)-1;
    //obtengo la extension
    	$extension=$separador[$cuenta];
    //genero el nombre del archivo, osea como se llamara
    	$final=$nombre.'.'.$extension;
    //datos de la fecha y hora actuales  
	    $hoy = getdate();
	    	$ano=$hoy['year'];
	    	$month=$hoy['mon'];
	    	$day=$hoy['mday'];
		$f_registro="$ano-$month-$day";
			$horas=$hoy['hours'];
			$minutos=$hoy['minutes'];
		$h_registro="$horas:$minutos";
	//busqueda de la existencia del archivo
		$search=$conn->prepare("SELECT count(id) as exist FROM archivos where nombre='$final' and $target=$id ");
		$search->execute();
		$tabla=$search->fetch(PDO::FETCH_ASSOC);
		$dto=$tabla['exist'];

		if ($dto<1) {
			//insersion del archivo
			if(!file_exists($dir)){ mkdir($dir, 0777,true); }
			$tmp_name = $_FILES["archivo"]["tmp_name"];
			if(($nombre!=null) && (move_uploaded_file($tmp_name,"$dir/$final"))){
				chmod("$dir/$final", 0777);
				$sentencia="INSERT INTO archivos(id_usuario,f_registro,$target,nombre,tipo,fuente) values ($usuario,'$f_registro $h_registro',$id,'$final','$extension','Local')";
				$photo=$conn->prepare($sentencia);
				$photo->execute();
				if($photo == TRUE){
					echo "
						<div class='alert alert-info alert-dismissible fade show '>
							<button class='close' data-dismiss='alert'>×</button>
							<strong>Añadido!</strong>
							archivo ".$final." cargado correctamente
						</div>
					";
				}
			}else{
				echo "
					<div class='alert alert-danger alert-dismissible fade show'>
						<button class='close' data-dismiss='alert'>×</button>
				        <strong>Error!</strong>
				        Archivo no encontrado.".$_FILES['archivo']['error']."
				    </div>
				";

			}
		}else{
			echo "
				<div class='alert alert-primary alert-dismissible fade show'>
					<button class='close' data-dismiss='alert'>×</button>
					<strong>Atención!</strong>
					Ya se encuentra adjunta esta evidencia
				</div>
			";
		}
	//

?>