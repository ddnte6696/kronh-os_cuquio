<?php
  //Reviso que la sesion este iniciada
	 if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Incluyo el archivo de configuracion
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el usuario actual
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2);
  //Obtengo los datos del destino
    $id=campo_limpiado($_POST['id_talonario_cambiar'],2);
    $nuevo=campo_limpiado($_POST['nuevo_cambiar']);
  //Obtengo el folio final del talonario
    $final=busca_existencia("SELECT final AS exist FROM talonario WHERE id=$id");
  //Evaluo datos
    if ($final==$nuevo) {
      $restantes=1;
    }elseif ($nuevo>$final) {
      echo "
        <script>
          alert('EL NUEVO FOLIO ES MAYOR AL FOLIO FINAL');
        </script>
      ";
      die();
    }else{
      //Obtengo la diferencia entre el folio final y el nuevo
        $restantes=($final-$nuevo)+1;
      //
    }
  //Defino la sentencia
    $sentencia="
      UPDATE 
        talonario 
      SET 
        restantes=$restantes, 
        actual=$nuevo
      WHERE 
        id=$id
      ;
    ";
  //Trato de ejecutar la sentencia y sino arrojo el error
    $devuelto=ejecuta_sentencia_sistema($sentencia,True);
  //Evaluo si se realizo la sentencia
    if ($devuelto==True) {
      echo "
      <script>
        alert('TALONARIO ACTUALIZADO, RECARGA LA TABLA PARA VISUALIZAR LOS CAMBIOS');
        $('#cambiar_talonario').modal('hide');
      </script>";
    }
  //
?>