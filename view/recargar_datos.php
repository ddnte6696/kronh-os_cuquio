<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el ID del usuario activo
		$id_usr=campo_limpiado($_SESSION[UBI]['id'],2,0);
	//Se crea la sentencia a ejecutar
		$sentencia = "
      SELECT 
        a.id,
        a.nombre,
        a.apellido,
        a.correo,
        a.clave,
        a.password,
        a.telefono,
        a.puesto as id_puesto,
        a.taquilla,
        b.puesto,
        a.empresa as id_empresa,
        c.empresa,
        a.f_ingreso,
        a.token,
        a.photo,
        a.permisos,
        a.visual,
        a.admin
      FROM usuarios as a
	      join puestos as b on a.puesto=b.id
	      join empresas as c on a.empresa=c.id
      WHERE a.id=$id_usr;
    ";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_busqueda_usuario=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_busqueda_usuario['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $datos_usuario = $resultado_busqueda_usuario['data'];
      // Recorrer los datos y llenar las filas
        foreach ($datos_usuario as $fila_usuario) {
          //Obtengo el dato de los permisos y los separo por su identificador
            $dato_permiso=explode("||",campo_limpiado($fila_usuario['permisos'],0,0));
          //Extraigo todos los datos y creo un arreglo
            $datos =array (
              'id'=>campo_limpiado($fila_usuario['id'],1,0),
							'nombre'=>campo_limpiado($fila_usuario['nombre'],1,0),
							'apellido'=>campo_limpiado($fila_usuario['apellido'],1,0),
							'correo'=>campo_limpiado($fila_usuario['correo'],1,0),
							'clave'=>campo_limpiado($fila_usuario['clave'],1,0),
							'password'=>campo_limpiado($fila_usuario['password'],1,0),
							'telefono'=>campo_limpiado($fila_usuario['telefono'],1,0),
							'id_puesto'=>campo_limpiado($fila_usuario['id_puesto'],1,0),
							'puesto'=>campo_limpiado($fila_usuario['puesto'],1,0),
							'id_empresa'=>campo_limpiado($fila_usuario['id_empresa'],1,0),
							'empresa'=>campo_limpiado($fila_usuario['empresa'],1,0),
							'f_ingreso'=>campo_limpiado($fila_usuario['f_ingreso'],1,0),
              'taquilla'=>campo_limpiado($fila_usuario['taquilla'],1,0),
							'token'=>campo_limpiado($fila_usuario['token'],1,0),
							'imagen'=>campo_limpiado($fila_usuario['photo'],1,0),
							'pag_principal'=>campo_limpiado($dato_permiso[0],1,0),
							'permisos'=>campo_limpiado($dato_permiso[1],1,0),
							'visual'=>campo_limpiado($fila_usuario['visual'],1,0),
							'admin'=>campo_limpiado($fila_usuario['admin'],1,0),
							'title'=>TITLE,
            );
          //Asigno los datos del arreglo a la variable de sesion
            $_SESSION[UBI]=$datos;
          //Redirijo a la pagina principal
            header('Location:../index.php');
          //
        }
      //
    }
  //
?>