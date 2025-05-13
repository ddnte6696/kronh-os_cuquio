<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el ID del usuario activo
    $id_usr=campo_limpiado($_SESSION[UBI]['id'],2,0);
  //Se obtienen los datos de los campos y se les da formato
    $nombre_grupo=campo_limpiado($_POST['nombre_grupo'],0,0);
    $nombre_menu=campo_limpiado($_POST['nombre_menu'],0,0);
    $directorio=campo_limpiado($_POST['directorio'],0,0);
    $sub_directorio=campo_limpiado($_POST['sub_directorio'],0,0);
    $archivo=campo_limpiado($_POST['archivo'],0,0);
    $descripcion=campo_limpiado($_POST['descripcion'],0,0);
  //Reviso si alguno de los campos esta vacio
    if(isset($_POST['nombre_grupo']) || isset($_POST['nombre_menu']) || isset($_POST['directorio']) || isset($_POST['archivo'])){
      //Reviso si el campo de subdirectorio esta vacio o si se definio algun valor
        if (($sub_directorio==Null)||($sub_directorio=="")) {
          $sub_directorio="Null";
        }else{
          $sub_directorio="'$sub_directorio'";
        }
      //Se define la sentencia de revision a ejecutar
        $sentencia="
          SELECT count(id) as exist FROM menu WHERE nombre_grupo='$nombre_grupo'  and nombre_menu='$nombre_menu' and directorio='$directorio' and archivo='$archivo' and descripcion='$descripcion';
        ";
      //Reviso si el dato ya existe
        $dto=busca_existencia($sentencia);
      //Si los datos no existen, se crea un nuevo registro en la base de datos
        if ($dto<1) {
          //Se define la sentencia a ejecutar
            $sentencia="
              INSERT INTO menu (nombre_grupo,nombre_menu,directorio,sub_directorio,archivo,descripcion) VALUES ('$nombre_grupo','$nombre_menu','$directorio',$sub_directorio,'$archivo','$descripcion');
            ";
          //Trato de ejecutar la sentencia y sino arrojo el error
            $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
          //Evaluo si se realizo la sentencia
            if ($devuelto=="realizado") {
              echo "<script>alert('¡AÑADIDO!, MENÚ AGREGADO');</script>";
            }
          //
        }else{
          echo "<script>alert('ESTE MENU YA SE ENCUENTRA REGISTRADO');</script>";
        }
      //
    }else{ echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>"; }
  //  
?>