<?php
	error_reporting(E_ALL);
  ini_set("display_errors", 1);
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
    $precio=campo_limpiado($_POST['precio']);
    $id_talonario=campo_limpiado($_POST['id_talonario']);
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
    $clave=$pre[3];
  //Obtengo el ID de la liquidacion
    $id_liquidacion=busca_existencia("SELECT id AS exist FROM liq_op_rl where unidad=$id_unidad and operador='$clave' and fecha='$fecha_trabajo'");
  //Genero la sentencia para actualizar el precio del boleto
    $sentencia="UPDATE talonarios_liquidados SET importe='$precio' where id=$id_talonario";
  //Ejecuto la sentencia
		$resultado=ejecuta_sentencia_sistema($sentencia,true);
	//Evaluo el resultado
		if ($resultado===true) {
			//Obtengo el total de los talonarios
		    $sentencia="
		      SELECT SUM(a.importe) as exist
		      FROM talonarios_liquidados as a
		      JOIN talonario as b on a.talonario=b.id 
		      WHERE 
		        a.usuario='$clave' and 
		        a.fecha='$fecha_trabajo' and 
		        b.tipo=1 and 
		        b.uso=2 and 
		        a.unidad=$id_unidad
		      ;
		    ";
		  //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $suma_talonario=busca_existencia($sentencia);
		  //Actualizo la liquidacion
		    $sentencia="UPDATE liq_op_rl SET talonario=$suma_talonario where id=$id_liquidacion;";
		  //Ejecuto la sentencia
		    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
		  //Evaluo el resulado
		    if ($resultado === true) {
		    	//Imprimo un mensaje de confirmacion
		    		 echo "<script>alert('IMPORTE DE TALONARIO ACTUALIZADO');</script>";
		      //Recalculo los importes y la comision
		        recalcular_liq_op_rl($id_liquidacion,TRUE);
		      //
		    }
		  //
		}
	//
?>