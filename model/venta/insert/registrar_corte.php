<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<style type="text/css">
  @media print {
    @page { size: auto; }
    html {
      min-height: 100%;
      position: relative;
      font-family: Verdana; 
    }
    img {
      display: block;
      margin: 1em auto;
    }
    table {
      font-family: Arial, sans-serif;
      font-size: 14px;
      border-collapse: collapse;
    }
  }
</style>
<?php
  //Obtengo los datos del usuario y la taquilla
		$taquilla=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
		$clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
    $nombre_vendedor=campo_limpiado($_SESSION[UBI]['nombre'],2,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],2,0);
	//Obtengo la cantidad de boletos vendidos y el total del importe
  	$sentencia="SELECT count(id) as cuenta_boletos_sistema, SUM(precio) as total_boletos_sistema from boleto where punto_venta='$taquilla' and usuario='$clave' and estado=2 and f_venta='".ahora(1)."';";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
        	if ($tabla['cuenta_boletos_sistema']>0) {
            $cuenta_boletos_sistema=$tabla['cuenta_boletos_sistema'];
            $total_boletos_sistema=$tabla['total_boletos_sistema'];
        	}else{
        		$cuenta_boletos_sistema=0;
						$total_boletos_sistema=0;
        	}
        }
      //
    }
  //Obtengo la cantidad de paquetes vendidos y el total del importe
  	$sentencia="SELECT count(id) as cuenta_paquetes_sistema, SUM(total) as total_paquetes_sistema from paquete where punto_venta='$taquilla' and usuario_vende='$clave' and estado<>5 and fecha='".ahora(1)."';";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
        	if ($tabla['cuenta_paquetes_sistema']>0) {
        		$cuenta_paquetes_sistema=$tabla['cuenta_paquetes_sistema'];
            $total_paquetes_sistema=$tabla['total_paquetes_sistema'];
        	}else{
        		$cuenta_paquetes_sistema=0;
						$total_paquetes_sistema=0;
        	}
        }
      //
    }
  //Busco si existe u corte registrado de este usuario
    $sentencia="SELECT count(id) AS exist FROM corte where punto_venta='$taquilla' and usuario='$clave' and fecha='".ahora(1)."';";
    $retorno=busca_existencia($sentencia);
  //Identifico si ya hay un corte de este usuario registrado
   	if ($retorno>0) {
   		echo "<script>alert('YA EXISTE UN CORTE REGISTRADO PARA ESTE USUARIO EN ESTE PUNTO DE VENTA');</script>";
   	}else{
      $total_corte=$total_boletos_sistema+$total_paquetes_sistema;
   		//Se define la sentencia a ejecutar
     		$sentencia="
          INSERT INTO corte (
            fecha, 
            usuario, 
            punto_venta, 
            cantidad_boletos_sistema, 
            importe_boletos_sistema, 
            cantidad_paquetes_sistema, 
            importe_paquetes_sistema,
            fecha_registra,
            usuario_registra
          ) VALUES (
            '".ahora(1)."', 
            '$clave', 
            '$taquilla', 
            $cuenta_boletos_sistema, 
            $total_boletos_sistema, 
            $cuenta_paquetes_sistema, 
            $total_paquetes_sistema,
            '".ahora(1)."', 
            '$clave'
          )
        ";
   		//Trato de ejecutar la sentencia y sino arrojo el error
				$devuelto=ejecuta_sentencia_sistema($sentencia,"realizado");
			//Evaluo si se realizo la sentencia
				if ($devuelto=="realizado") {
          //Se envia un mensaje de registro
            echo "
              <script>
                alert('CORTE REGISTRADO');
                $(document).ready(imprimirDiv('respuesta_corte'));
              </script>";
          //Se define la tabla de datos
            echo "
              <table class='table table-responsive table-sm'>
                <tbody>
                  <tr>
                    <th colspan='2' class='text-center'>
                      <img src='".LOGO_YAHUALICA."' class='img-fluid' style='width: 200px' />
                    </th>
                  </tr>
                  <tr>
                    <th colspan='2' class='text-center'>OMNIBUS YAHUALICA GUADALAJARA S.A. DE CV.</th>
                  </tr>
                  <tr>
                    <th colspan='2' class='text-center'>REPORTE DE FIN DE TURNO</th>
                  </tr>
                  <tr>
                    <th>FECHA</th>
                    <td>".ahora(1)."</td>
                  </tr>
                  <tr>
                    <th>TAQUILLA</th>
                    <td>$taquilla</td>
                  </tr>
                  <tr>
                    <th>CAJERO</th>
                    <td>$nombre_vendedor</td>
                  </tr>
                  <tr>
                    <th colspan='2' class='text-center'>VENTA DE BOLETOS</th>
                  </tr>
                  <tr>
                    <th class='text-center' >CANTIDAD</th>
                    <th class='text-center' >TOTAL</th>
                  </tr>
                  <tr>
                    <td class='text-center' >$cuenta_boletos_sistema</td>
                    <td class='text-center' >$ ".number_format($total_boletos_sistema)."</td>
                  </tr>
                  <tr>
                    <th colspan='2' class='text-center'>VENTA DE PAQUETERIA</th>
                  </tr>
                  <tr>
                    <th class='text-center' >CANTIDAD</th>
                    <th class='text-center' >TOTAL</th>
                  </tr>
                  <tr>
                    <td class='text-center' >$cuenta_paquetes_sistema</td>
                    <td class='text-center' >$ ".number_format($total_paquetes_sistema)."</td>
                  </tr>
                  <tr>
                    <th colspan='2' class='text-center'>TOTAL DEL CORTE</th>
                  </tr>
                  <tr>
                    <th colspan='2' class='text-center'>$ ".number_format($total_corte,2)."</th>
                  </tr>
                </tbody>
              </table>
            ";

        }
   	}
 	//
?>