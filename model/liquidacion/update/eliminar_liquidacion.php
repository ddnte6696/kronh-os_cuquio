<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_liquidacion=campo_limpiado($_POST['dato'],2,0);
  //Obtengo los datos de la liquidacion
    //Defino la sentencia de busqueda
      $sentencia="SELECT * FROM liq_op_rl where id=$id_liquidacion";
    //Ejecuto la sentencia y almaceno lo obtenido en una variable
      $resultado_sentencia=retorna_datos_sistema($sentencia);
    //Identifico si el reultado no es vacio
      if ($resultado_sentencia['rowCount'] > 0) {
        //Almaceno los datos obtenidos
          $resultado = $resultado_sentencia['data'];
        // Recorrer los datos y llenar las filas
          foreach ($resultado as $tabla) {
            //Almaceno los datos en variables
              $id=$tabla['id'];
              $unidad=$tabla['unidad'];
              $dto_unidad=busca_existencia("SELECT numero AS exist FROM unidades WHERE id=$unidad");
              $operador=$tabla['operador'];
              $dto_operador=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM operadores WHERE clave='$operador'");
              $boletos_sistema=number_format(($tabla['boletos_sistema']),2);
              $boletos_talonario=number_format(($tabla['boletos_talonario']),2);
              $paqueterias_sistema=number_format(($tabla['paqueterias_sistema']),2);
              $paqueterias_talonario=number_format(($tabla['paqueterias_talonario']),2);
              $talonario=number_format(($tabla['talonario']),2);
              $comision=number_format(($tabla['comision']),2);
              $anticipo=number_format(($tabla['anticipo']),2);
            //Creo un texto para la sentencia de bitacora
              $texto="ID=>$id, UNIDAD=>$dto_unidad, OPERADOR=>[$operador] - $dto_operador, BOLETOS DE SISTEMA=>$boletos_sistema, BOLETOS DE TALONARIO=>$boletos_talonario, PAQUETERIAS DE SISTEMA=>$paqueterias_sistema, PAQUETERIAS DE TALONARIO=>$paqueterias_talonario, TALONARIOS=>$talonario, COMISION=>$comision, ANTICIPO=>$anticipo";
            //
          }
        //
      }
    //
  //
  //Realizo la actualizacion de la liquidacion
    $sentencia="DELETE FROM liq_op_rl WHERE id=$id_liquidacion;";
  //Ejecuto la sentencia
    $resultado=ejecuta_sentencia_sistema($sentencia,TRUE);
  //Evaluo el resulado
    if ($resultado === true) {
      //Escribo en la bitacora
        registra_bitacora("SE ELIMINA LA LIQUIDACION N° $id_liquidacion CON LOS SIGUIENTES DATOS [$texto]");
      //Imprimo un mensaje de confirmacion y continuo con el proceso
        echo "
          <script>
            alert('LIQUIDACION ELIMINADA');
            buscar_fecha_liquidacion();
          </script>
        ";
      //
    }
  //
?>