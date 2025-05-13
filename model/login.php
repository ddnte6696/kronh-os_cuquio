<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Defino el apuntador de la carpeta actual
    define('TARGET', 'kronh-os_cuquio');
  //Asigno la variable de sesion del apuntador definido
    $_SESSION['ubi']=TARGET;
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.TARGET.'/lib/config.php';
  //recupero tanto el usuario como la contareña
    $user=campo_limpiado($_POST['user'],0,0);
    $password=md5(campo_limpiado($_POST['password'],0,0));
  //se crea la sentencia a ejecutar
    $sentencia="
      SELECT 
        a.id,
        a.nombre,
        a.apellido,
        a.correo,
        a.clave,
        a.password,
        a.telefono,
        a.puesto as id_puesto,
        b.puesto,
        a.empresa as id_empresa,
        c.empresa,
        a.f_ingreso,
        a.token,
        a.photo,
        a.permisos,
        a.visual,
        a.admin
      FROM usuarios AS a
        JOIN puestos AS b ON a.puesto=b.id
        JOIN empresas AS c ON a.empresa=c.id
      WHERE a.clave='$user' AND a.password='$password'
    ";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_busqueda_usuario=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_busqueda_usuario['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $datos_usuario = $resultado_busqueda_usuario['data'];
      // Recorrer los datos y llenar las filas
        foreach ($datos_usuario as $fila_usuario) {
          //se busca el estado del usuario
            switch ($fila_usuario['visual']) {
              //null, no hay resultados
              case null:
                echo "<script>alert('Error!, Usuario inexistente o sin permisos')</script>";
              break;
              //0 el usuario fue dado de baja del sistema
              case 0:
                echo "<script>alert('Error!, El usuario fue dado de baja en el sistema')</script>";
              break;
              //1, usuario activo en sistema
              case 1:
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
                    'token'=>campo_limpiado($fila_usuario['token'],1,0),
                    'imagen'=>campo_limpiado($fila_usuario['photo'],1,0),
                    'pag_principal'=>campo_limpiado($dato_permiso[0],1,0),
                    'permisos'=>campo_limpiado($dato_permiso[1],1,0),
                    'visual'=>campo_limpiado($fila_usuario['visual'],1,0),
                    'admin'=>campo_limpiado($fila_usuario['admin'],1,0),
                    'title'=>TITLE,
                  );
                //asigno los datos del arreglo a la variable de sesion
                  $_SESSION[UBI]=$datos;
                //Reviso si existen corridas registradas en el
                  $sentencia="SELECT count(id) as exist FROM corrida WHERE fecha='".ahora(1)."';";
                  $existencia=busca_existencia($sentencia);
                  if ($existencia==0) { genera_corridas(ahora(1),''); }
                //regeso al index para que mande a llamar el body
                  echo "ok";
                //
              break;
              //2, el usuario esta registrado pero aun no se encuentra activo en el sistema
              case 2:
                echo "<script>alert('Error!, El usuario aún no se encuentra aceptado en el sistema')</script>";
              break;
              default:
                echo "<script>alert('Error!, Reviza los datos eh intenta de nuevo')</script>";
              break;
            }
          //
        }
      //
    }
  //
?>