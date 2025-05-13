<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave de quien realiza la liquidacion
    $clave_liquida=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $fecha_trabajo=campo_limpiado($_POST['fecha'],0,0);
    $vendedor=campo_limpiado($_POST['vendedor'],2,0);
    $dato_taquilla=explode("||", campo_limpiado($_POST['dto_taquilla'],2,0));
    $id_origen=$dato_taquilla[0];
    $taquilla=$dato_taquilla[1];
  //Defino la fecha en la que se esta realizando la liquidacion
    $fecha_liquida=ahora(1);
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
              $cuenta_boletos_talonario=number_format($tabla['cuenta_boletos'],2);
              $total_boletos_talonario=number_format($tabla['suma_boletos'],2);
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
              $cuenta_paquetes_talonario=number_format($tabla['cuenta_paquetes'],2);
              $total_paquetes_talonario=number_format($tabla['suma_paquetes'],2);
            //
          }
        //
      }
    //Verifico si esta vacio o no
      if ($cuenta_paquetes_talonario=='') { $cuenta_paquetes_talonario=0; }
    //Verifico si esta vacio o no
      if ($total_paquetes_talonario=='') { $total_paquetes_talonario=0; }
    //
  //Verifico la existencia del corte en el sistema
    $existencia=busca_existencia("SELECT id AS exist FROM corte WHERE fecha='$fecha_trabajo' and usuario='$vendedor' and punto_venta='$taquilla'");


  //Evaluo el resultado
    if ($existencia<1) {
      //Defino la sentencia de insercion
        $sentencia="
          INSERT INTO corte (
            fecha, 
            usuario, 
            punto_venta, 
            cantidad_boletos_sistema, 
            importe_boletos_sistema, 
            cantidad_paquetes_sistema, 
            importe_paquetes_sistema,
            cantidad_boletos_talonario, 
            importe_boletos_talonario, 
            cantidad_paquetes_talonario, 
            importe_paquetes_talonario,
            fecha_registra,
            usuario_registra
          ) VALUES (
            '$fecha_trabajo', 
            '$vendedor', 
            '$taquilla', 
            $cuenta_boletos_sistema, 
            $total_boletos_sistema, 
            $cuenta_paquetes_sistema, 
            $total_paquetes_sistema,
            $cuenta_boletos_talonario, 
            $total_boletos_talonario, 
            $cuenta_paquetes_talonario, 
            $total_paquetes_talonario,
            '".ahora(1)."', 
            '$clave_liquida'
          )
        ";
      //Trato de ejecutar la sentencia y sino arrojo el error
        $devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
      //Evaluo si se realizo la sentencia
        if ($devuelto=="realizado") {
          //Imprimo un mensaje de confirmacion
            echo "
              <script>
                alert('LIQUIDACION GENERADA');
                buscar_fecha_liquidacion();
              </script>
            ";
          //
        }
      //
    }else{
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
          WHERE id=$existencia;
        ";
       //Trato de ejecutar la sentencia y sino arrojo el error
        ejecuta_sentencia_sistema($sentencia,TRUE);
      //Imprimo un mensaje de confirmacion
        echo "
          <script>
            alert('ESTE VENDEDOR YA TIENE UNA LIQUIDACION REGISTRADA PARA LA TAQUILLA $taquilla EL DIA ".campo_limpiado(transforma_fecha($fecha_trabajo,1," de "),0,1)."');
            buscar_fecha_liquidacion();
          </script>
        ";
      //
    }
  //
?>