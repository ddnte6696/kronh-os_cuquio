<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo los datos del usuario y la taquilla
		$taquilla=campo_limpiado($_SESSION[UBI]['taquilla'],2);
		$clave=campo_limpiado($_SESSION[UBI]['clave'],2);
    $nombre_vendedor=campo_limpiado($_SESSION[UBI]['nombre'],2)." ".campo_limpiado($_SESSION[UBI]['apellido'],2);
  //Defino la fecha y hora de registro
    $fecha_registro=ahora(1);
    $hora_registro=ahora(2);
	//Obtengo los datos enviados por el formulario
    $referencia=campo_limpiado($_POST['referencia'],2)."-".$clave;
    $tipo=campo_limpiado($_POST['tipo'],2);
    $t1000=campo_limpiado($_POST['t1000']);
    $t500=campo_limpiado($_POST['t500']);
    $t200=campo_limpiado($_POST['t200']);
    $t100=campo_limpiado($_POST['t100']);
    $t50=campo_limpiado($_POST['t50']);
    $t20=campo_limpiado($_POST['t20']);
    $t10=campo_limpiado($_POST['t10']);
    $t5=campo_limpiado($_POST['t5']);
    $t2=campo_limpiado($_POST['t2']);
    $t1=campo_limpiado($_POST['t1']);
    $t050=campo_limpiado($_POST['t050']);
    $total=campo_limpiado($_POST['total']);
  //Evaluo el tipo de transaccion a 
  //Busco si ya se realizo un registro de la misma referencia y tipo
    $sentencia="SELECT referencia AS exist from billetes where referencia='$referencia' and tipo=$tipo;";
    $existencia=busca_existencia($sentencia);
  //Evaluo el resultado
    if ($existencia=='') {
      //Defino la sentencia para insertar en la BD
        $sentencia="
          INSERT INTO billetes (
            fecha_registro,
            hora_registro,
            usuario,
            taquilla,
            referencia,
            tipo,
            t1000,
            t500,
            t200,
            t100,
            t50,
            t20,
            t10,
            t5,
            t2,
            t1,
            t050,
            total
          ) VALUES (
            '$fecha_registro',
            '$hora_registro',
            '$clave',
            '$taquilla',
            '$referencia',
            $tipo,
            $t1000,
            $t500,
            $t200,
            $t100,
            $t50,
            $t20,
            $t10,
            $t5,
            $t2,
            $t1,
            $t050,
            '$total'
          );
        ";
      //Ejecuto la sentencia
        $resultado=ejecuta_sentencia_sistema($sentencia,true);
      //Evaluo el resultado
        if ($resultado===TRUE) {
          //Imprimo un mensaje de confirmacion
            echo "
              <script>
                alert('".tipo_transaccion_billetes($tipo)." POR $ ".number_format($total,2)." REGISTRADO');
              </script>
              <a class='btn btn-sm btn-block text-sm btn-primary' href='print_files/recibo_transaccion_billetes.php?ref=".campo_limpiado($referencia,1)."' target='_blank'>IMPRIMIR RECIBO</a>
            ";
          //
        }
      //
    }else{
      echo "
        <script>
          alert('EL ".tipo_transaccion_billetes($tipo)." POR $ ".number_format($total,2)." YA SE ENCUENTRA REGISTRADO');
        </script>
        <a class='btn btn-sm btn-block text-sm btn-primary' href='print_files/recibo_transaccion_billetes.php?ref=".campo_limpiado($existencia,1)."' target='_blank'>IMPRIMIR RECIBO</a>
        
      ";
    }
  //
?>