<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //obtengo el identificador del menu a agregar
    $id_permiso=campo_limpiado($_POST['id'],2,0);
  //Obtengo la clave del colaborador a editar
    $usuario=campo_limpiado($_POST['usuario'],2,0);
  //Defino la sentencia para descarga de los permisos que tiene el usuario
    $sentencia = "SELECT permisos AS exist FROM usuarios WHERE id=$usuario";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $permisos=busca_existencia($sentencia);
  //Se creea un array separando los datos de pag. principal, permisos y nivel de acces por su identificador
    $dvision_permisos=explode("||", $permisos);
  //defino la uneva pagina inicial
    $pag_principal=$dvision_permisos[0];
  //defino los permisos ya asignados
    $permisos_actuales=$dvision_permisos[1];
  //Defino el nivel de acceso
    $nivel_acceso=$dvision_permisos[2];
  //Separo los permisos en un array para revizarlo
    $datos_permisos=explode("!!", $permisos_actuales);
  //Obtengo la cantidad de registros en el arreglo
    $cantidad=count($datos_permisos);
  //Defino un variable en vacio
    $permiso=Null;
  //Reviso el valor con una condicional
    if ($cantidad==1) {
      $resultado=$pag_principal."||||".$nivel_acceso;
    }else{
      //Creeo un ciclo para recorrer el arreglo
      for ($i=0; $i < $cantidad; $i++) { 
        //Reviso si el arreglo de los permisos en esa posicion, corresponde con el valor buscado
        if ($id_permiso!=$datos_permisos[$i]) {
          //Creo un concatenado con los permisos
          $permisos.=$datos_permisos[$i]."!!";
        }
      }
    }
    //Le quipo el ultimo par de identificadores que esta de mas
    $permiso=substr($permiso, 0, -2);
    //Se concatenan los datos completos
    $resultado=$pag_principal."||".$permiso."||".$nivel_acceso;
    //Defino la sentencia para actualizar al colaborador
    $sentencia="
      UPDATE usuarios set permisos='$resultado' where id=$usuario;
    ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
  //Evaluo si se realizo la sentencia
    if ($devuelto=="realizado") {
      //registra_bitacora("SE LE RETIRA EL MENÚ N° $id_permiso AL USUARIO ID $usuario");
      echo "
        <script>
          tabla_menus_asignados();
          tabla_menus_disponibles();
        </script>
      ";
    }
  //
?>
