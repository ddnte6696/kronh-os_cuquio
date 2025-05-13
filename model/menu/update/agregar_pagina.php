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
    $pag_principal=$id_permiso;
  //defino los permisos ya asignados
    $permisos=$dvision_permisos[1];
  //Defino el nivel de acceso
    $nivel_acceso=$dvision_permisos[2];
  //Se concatenan los datos completos
    $resultado=$pag_principal."||".$permisos."||".$nivel_acceso;
  //Defino la sentencia para actualizar al colaborador
    $sentencia="UPDATE usuarios set permisos='$resultado' where id=$usuario;";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
  //Evaluo si se realizo la sentencia
    if ($devuelto=="realizado") {
      /*registra_bitacora("SE LE ASIGNA EL MENÚ N° $id_permiso AL USUARIO ID $usuario COMO PAGINA PRINCIPAL");*/
      echo "
        <script>
          tabla_paginas_asignadas();
          tabla_paginas_disponibles();
        </script>
      ";
    }
  //
?>