<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave de quien realiza la liquidacion
    $clave_liquida=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo la fecha de trabajo del operador
    $fecha_trabajo=campo_limpiado($_POST['fecha'],0,0);
  //Defino la fecha en la que se esta realizando la liquidacion
    $fecha_liquida=ahora(1);
  //Defino dos constantes de sumatoria
    $suma_creadas=0;
    $suma_existentes=0;
  //Busco todos los operadores asignados a las corridas de la fecha
    $sentencia="SELECT unidad, operador from corrida where fecha='$fecha_trabajo' and unidad is not null and operador is not null group by unidad, operador";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Almaceno los datos obtenidos
            $id_unidad=$tabla['unidad'];
            $operador=$tabla['operador'];
          //Obtengo el numero de la unidad
            $numero_unidad=busca_existencia("SELECT numero AS exist from unidades WHERE id=$id_unidad");
          //Busco los boletos de sistema asignados
            $boletos_sistema=busca_existencia("SELECT SUM(precio) as exist FROM boleto WHERE operador='$operador' and f_corrida='$fecha_trabajo' and unidad=$id_unidad and estado=2");
            if ($boletos_sistema==Null) {
              $boletos_sistema=0;
            }
          //Busco las paqueterias de sistema asignadas
            $paqueterias_sistema=busca_existencia("SELECT SUM(total) as exist FROM paquete WHERE operador='$operador' and fecha_envio='$fecha_trabajo' and unidad=$id_unidad and estado<>5");
            if ($paqueterias_sistema==Null) {
              $paqueterias_sistema=0;
            }
          //Busco los talonarios de operador liquidados
            $talonarios_operador=busca_existencia("SELECT SUM(importe) as exist FROM talonarios_liquidados WHERE usuario='$operador' and fecha='$fecha_trabajo' and unidad=$id_unidad");
            if ($talonarios_operador==Null) {
              $talonarios_operador=0;
            }
          //Reviso si ya existe una liquidacion en la tabla
            $devuelto=busca_existencia("SELECT sum(id) AS exist FROM liq_op_rl where unidad=$id_unidad and operador='$operador' and fecha='$fecha_trabajo'");
          //Evaluo el resultado
            if ($devuelto == 0) {
              //Calcula las ventas totales
                $ventas_totales=$boletos_sistema+$paqueterias_sistema+$talonarios_operador;
              //Calculo el iva de las ventas totales
                $iva=$ventas_totales*0.16;
              //Calculo las ventas sin iva
                $ventas_sin_iva=$ventas_totales/1.16;
              //Calculo la comision
                $comision=$ventas_sin_iva*0.1;
              //Defino la sentencia para el registro de la liquidacion
                $sentencia="
                  INSERT INTO liq_op_rl (
                    unidad,
                    operador,
                    fecha,
                    boletos_sistema,
                    boletos_talonario,
                    paqueterias_sistema,
                    paqueterias_talonario,
                    talonario,
                    comision,
                    clave_liquida,
                    fecha_liquida,
                    estado
                  ) VALUES (
                    $id_unidad,
                    '$operador',
                    '$fecha_trabajo',
                    $boletos_sistema,
                    0,
                    $paqueterias_sistema,
                    0,
                    $talonarios_operador,
                    $comision,
                    '$clave_liquida',
                    '$fecha_liquida',
                    1
                  );
                ";
              //La ejecuto
                $resultado=ejecuta_sentencia_sistema($sentencia,true);
              //Evaluo el resulado
                if ($resultado === true) {
                  //Sumatoria a las liquidaciones registradas
                    $suma_creadas++;
                  //
                }
              //
            }else{
              //Defino el ID de la liquidacion
                $id_liquidacion=$devuelto;
              //Actualizo los datos de venta de sistema
                $sentencia="
                  UPDATE 
                    liq_op_rl 
                  SET 
                    boletos_sistema=$boletos_sistema, 
                    paqueterias_sistema=$paqueterias_sistema, 
                    talonario=$talonarios_operador
                  WHERE 
                    id=$id_liquidacion
                  ;
                ";
              //La ejecuto
                $resultado=ejecuta_sentencia_sistema($sentencia,true);
              //Evaluo el resulado
                if ($resultado === true) {
                  //Recalculo los importes y la comision
                    recalcular_liq_op_rl($id_liquidacion,FALSE);
                  //Sumatoria a las liquidaciones registradas
                    $suma_existentes++;
                  //
                }
              //
            }
          //
        }
      //
    }
  //Imprimo un mensaje de confirmacion
    echo "
      <script>
        alert('SE GENERARON $suma_creadas LIQUIDACIONES Y SE ACTUALIZARON $suma_existentes LIQUIDACIONES');
        buscar_fecha_liquidacion();
      </script>
    ";
  //
?>