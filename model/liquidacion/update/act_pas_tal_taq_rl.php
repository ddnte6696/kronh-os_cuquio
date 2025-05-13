<?php
	/*error_reporting(E_ALL);
  ini_set("display_errors", 1);*/
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_corte=campo_limpiado($_POST['dato'],2,0);
    $precio=campo_limpiado($_POST['precio']);
    $id_talonario=campo_limpiado($_POST['id_talonario']);
  //Defino la sentencia de busqueda
    $sentencia="SELECT * FROM corte where id=$id_corte";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          $id_corte=$tabla['id'];
          $vendedor=$tabla['usuario'];
          $taquilla=$tabla['punto_venta'];
          $fecha_trabajo=$tabla['fecha'];
        }
      //
    }
	//Inserto el paquete en la liquidacion
		$sentencia="
			UPDATE talonarios_liquidados SET importe=$precio where id=$id_talonario
		";
	//Ejecuto la sentencia
		$resultado=ejecuta_sentencia_sistema($sentencia,true);
	//Evaluo el resultado
		if ($resultado===true) {
      //BOLETOS DE SISTEMA
        //Busco la cantidad de boletos de taquilla vendidos
          $sentencia="SELECT COUNT(id) as exist FROM boleto WHERE punto_venta='$taquilla' AND usuario='$vendedor' AND estado=2 AND f_venta='$fecha_trabajo';";
        //Almaceno lo obtenido en una variable
          $cuenta_boletos_sistema=busca_existencia($sentencia);
        //Verifico si esta vacio o no
          if ($cuenta_boletos_sistema=='') { $cuenta_boletos_sistema=0; }
        //Busco el importe de boletos de taquilla vendidos
          $sentencia="SELECT SUM(precio) as exist FROM boleto WHERE punto_venta='$taquilla' AND usuario='$vendedor' AND estado=2 AND f_venta='$fecha_trabajo';";
        //Almaceno lo obtenido en una variable
          $total_boletos_sistema=busca_existencia($sentencia);
        //Verifico si esta vacio o no
          if ($total_boletos_sistema=='') { $total_boletos_sistema=0; }
        //
      //PAQUETERIAS DE SISTEMA
        //Busco la cantidad de paquetes de taquilla vendidos
          $sentencia="SELECT COUNT(id) AS exist FROM paquete WHERE punto_venta='$taquilla' AND usuario_vende='$vendedor' AND estado<>5 AND fecha='$fecha_trabajo';";
        //Almaceno lo obtenido en una variable
          $cuenta_paquetes_sistema=busca_existencia($sentencia);
        //Verifico si esta vacio o no
          if ($cuenta_paquetes_sistema=='') { $cuenta_paquetes_sistema=0; }
        //Busco el importe de paquetes de taquilla vendidos
          $sentencia="SELECT SUM(total) AS exist FROM paquete WHERE punto_venta='$taquilla' AND usuario_vende='$vendedor' AND estado<>5 AND fecha='$fecha_trabajo';";
        //Almaceno lo obtenido en una variable
          $total_paquetes_sistema=busca_existencia($sentencia);
        //Verifico si esta vacio o no
          if ($total_paquetes_sistema=='') { $total_paquetes_sistema=0; }
        //
      //BOLETOS DE TALONARIO
        //Defino lña sentencia a ejecutar
          $sentencia="
            SELECT 
              COUNT(a.id) AS cuenta_boletos,
              SUM(a.importe) as suma_boletos
            FROM talonarios_liquidados as a
            JOIN talonario as b on a.talonario=b.id WHERE a.usuario='$vendedor' and a.fecha='$fecha_trabajo' and b.tipo=1 and b.uso=1
          ";
        //Ejecuto la sentencia y almaceno lo obtenido en una variable
          $resultado_sentencia=retorna_datos_sistema($sentencia);
        //Identifico si el reultado no es vacio
          if ($resultado_sentencia['rowCount'] > 0) {
            //Almaceno los datos obtenidos
              $resultado = $resultado_sentencia['data'];
            // Recorrer los datos y llenar las filas
              foreach ($resultado as $tabla) {
                //Creeo las variables del identificador
                  $cuenta_boletos_talonario=$tabla['cuenta_boletos'];
                  $total_boletos_talonario=$tabla['suma_boletos'];
                //
              }
            //
          }
        //Verifico si esta vacio o no
          if ($cuenta_boletos_talonario=='') { $cuenta_boletos_talonario=0; }
        //Verifico si esta vacio o no
          if ($total_boletos_talonario=='') { $total_boletos_talonario=0; }
        //
      //PAQUETERIAS DE TALONARIO
        //Defino lña sentencia a ejecutar
          $sentencia="
            SELECT 
              COUNT(a.id) AS cuenta_paquetes,
              SUM(a.importe) as suma_paquetes
            FROM talonarios_liquidados as a
            JOIN talonario as b on a.talonario=b.id WHERE a.usuario='$vendedor' and a.fecha='$fecha_trabajo' and b.tipo=2 and b.uso=1
          ";
        //Ejecuto la sentencia y almaceno lo obtenido en una variable
          $resultado_sentencia=retorna_datos_sistema($sentencia);
        //Identifico si el reultado no es vacio
          if ($resultado_sentencia['rowCount'] > 0) {
            //Almaceno los datos obtenidos
              $resultado = $resultado_sentencia['data'];
            // Recorrer los datos y llenar las filas
              foreach ($resultado as $tabla) {
                //Creeo las variables del identificador
                  $cuenta_paquetes_talonario=$tabla['cuenta_paquetes'];
                  $total_paquetes_talonario=$tabla['suma_paquetes'];
                //
              }
            //
          }
        //Verifico si esta vacio o no
          if ($cuenta_paquetes_talonario=='') { $cuenta_paquetes_talonario=0; }
        //Verifico si esta vacio o no
          if ($total_paquetes_talonario=='') { $total_paquetes_talonario=0; }
        //
      //Defino una sentencia de actualizacion del corte
        $sentencia="
          UPDATE corte SET 
            cantidad_boletos_sistema=$cuenta_boletos_sistema, 
            importe_boletos_sistema=$total_boletos_sistema, 
            cantidad_paquetes_sistema=$cuenta_paquetes_sistema, 
            importe_paquetes_sistema=$total_paquetes_sistema,
            cantidad_boletos_talonario=$cuenta_boletos_talonario, 
            importe_boletos_talonario=$total_boletos_talonario, 
            cantidad_paquetes_talonario=$cuenta_paquetes_talonario, 
            importe_paquetes_talonario=$total_paquetes_talonario
          WHERE id=$id_corte;
        ";
      //Trato de ejecutar la sentencia y sino arrojo el error
        $retorno=ejecuta_sentencia_sistema($sentencia,True);
      //Evaluo el resultado
        if ($retorno==True) {
          echo "
            <script>
              alert('FOLIO ACTUALIZADO');
            </script>
          ";
        }
      //
		}
	//
?>