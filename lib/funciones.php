<?php
  //Defino la zona horaria
    date_default_timezone_set('America/Monterrey');
  //Llamada de librerías de PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
  //Tipos de extensiones de archivos permitidos
    $extensionesPermitidas = array('jpg', 'jpeg', 'pdf');
  //Función para eliminar los acentos y la virgulilla de la Ñ
    function eliminar_tildes($cadena){
      $cadena = str_replace('Á','A',$cadena);
      $cadena = str_replace('É','E',$cadena);
      $cadena = str_replace('Í','I',$cadena);
      $cadena = str_replace('Ó','O',$cadena);
      $cadena = str_replace('Ú','U',$cadena);
      $cadena = str_replace('Ñ','N',$cadena);
      return $cadena;
    }
  //Funcion para poner o quitar comillas de un texto
    function pone_comillas($texto){ return str_replace('çÇç', '"', $texto); }
    function quita_comillas($texto){ return str_replace('"', 'çÇç', $texto); }
  //Elimina caracteres especiales de la cadena
    function elimina_especiales($cadena){
      //Defino un arreglo con los caracteres especiales a eliminar
      $especiales= array(
        '|',
        '°',
        '¬',
        '!',
        '"',
        '#',
        '$',
        '%',
        '&',
        "/",
        '(',
        ')',
        '=',
        '?',
        '¡',
        '¿',
        ',',
        ".",
        ";",
        "*",
        '<',
        '>',
        "\n"
      );
      //Elimino los caracteres listados del texto
      $resultado=str_replace($especiales,"",$cadena);
      //Devuelvo el resultado corregido
      return $resultado;
    }
  //Funcion para transformar la hora a un formato especifico
    function transforma_hora($hora,$formato = "24",$separador = ":"){
      //Identifico si se definio un formato de 12 o 24 horas
        if ($formato=="12") {
          //Transformo la hora a un formato de 12 horas
            $texto=date('g:i a', strtotime($hora));
          //
        }else{
          //Obtengo y separo la hora en horas, minutos y segundos
            $dato=explode(":", $hora);
          //Almaceno las partes obtenidas
            $horas=$dato[0];
            $minutos=$dato[1];
            $segundos=$dato[2];
          //Concateno los datos con el separador definido
            $texto=$horas.$separador.$minutos.$separador.$segundos;
          //
        }
      //Retorno el texto formateado
        return $texto;
      //
    }
  //Devuelve la fecha en palabras
    function transforma_fecha($fecha,$tipo=0,$separador = "-"){
      //Obtenemos la fecha y la separamos por su guion medio
        $dato = explode("-",$fecha);
      //Almaceno cada parte en una variable
        $ano=$dato[0];
        $mes=$dato[1];
        $dia=$dato[2];
      //Evaluamos si se sequiere que se convierta a dexto el mes
        if ($tipo==1) {
          //Obtengo el mes y lo paso a texto
            switch ($dato[1]) {
              case '1':
                $mes='Enero';
              break;
              case '2':
                $mes='Febrero';
              break;
              case '3':
                $mes='Marzo';
              break;
              case '4':
                $mes='Abril';
              break;
              case '5':
                $mes='Mayo';
              break;
              case '6':
                $mes='Junio';
              break;
              case '7':
                $mes='Julio';
              break;
              case '8':
                $mes='Agosto';
              break;
              case '9':
                $mes='Septiembre';
              break;
              case '10':
                $mes='Octubre';
              break;
              case '11':
                $mes='Noviembre';
              break;
              case '12':
                $mes='Diciembre';
              break;
            }
          //
        }
      //Concateno los datos con el separador definido
        $texto=$dia.$separador.$mes.$separador.$ano;
      //Retorno el dato obtenido
        return $texto;
      //
    }
  //Devuelve la fecha y hora actuales
    function ahora($tipo){
      //Se obtiene el timepo actual
        $hoy = getdate();
      //Se evalua el tipo de dato que se desea obtener
        switch ($tipo) {
          //Se solicita la fecha
            case '1':
              $actual=date('Y-m-d');
            break;
          //Se solicita la hora
            case '2':
              $actual=date('H:i:s');
            break;
          //Se solicitan el timestamp actual
            case '3':
              $actual=date('Y-m-d H:i:s');
            break;
          //
        }
      //Retorno el dato formateado
        return $actual;
      //
    }
  //Genera una referecia de fecha
    function ref_fecha(){
      //Obtengo la fecha actual en texto separada por guin bajo
        $texto=transforma_fecha(ahora(1),1,"_")."_";
      //Devuelvo el dato formateado
        return $texto;
      //
    }
  //Devuelve la referencia horaria
    function referencia_horaria(){
      //Obtengo la referencia horaria con base en la funcion de transforma hora
        $texto=transforma_hora(ahora(2),"24","");
      //Retorno el texto formateado
        return $texto;
      //
    }
  //Devuelve la referencia tenporal
    function referencia_temporal(){
      //Obtengo la hora actual
      $time=ahora(2);
      //Le elimino los : y la guardo en la variable
      $tiempo=str_replace(':','',$time);
      //Obtengo la fecha actual
      $date=ahora(1);
      //Le elimino los - y la guardo en la variable
      $dia=str_replace('-','',$date);
      //Realizo cun concatenado
      $referencia_temporal=$dia."-".$tiempo;
      //Devuelvo el valor
      return $referencia_temporal;
    }
  //Devuelve el dato limpiado, le da formato al texto y lo encripta según los identificadores 
    function campo_limpiado($dato,$encript = 0,$mayus = 0,$llenado = 0){
      if (($mayus==1)&&($encript==1)) { //Mayusculas y encriptar
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //Conversion a mayúsculas
          $campo = mb_strtoupper($campo);
        //Encriptacion
          $campo = encriptar_ligero($campo);
        //
      }elseif (($mayus==2)&&($encript==1)) { //Minusculas y encriptar
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //Conversion a minusculas
          $campo = strtolower($campo);
        //Encriptacion
          $campo = encriptar_ligero($campo);
        //
      }elseif (($mayus==0)&&($encript==1)) { //Solo encriptar
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //Encriptacion
          $campo = encriptar_ligero($campo);
        //
      }elseif(($mayus==1)&&($encript==2)) { //Mayusculas y desencriptar
        //Revision de cadena llena
          if (!empty($dato)) {
            //No se realiza nada
          }else{
            //Se imprime un mensaje de alerta
            echo "<script>alert('VALOR O DATO INVALIDO');</script>";
            //Se deiene el procedimiento
            die();
          }
        //Desencriptacion
          $campo = desencriptar_ligero($dato);
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($campo),ENT_QUOTES);
        //Conversion a mayúsculas
          $campo = mb_strtoupper($campo);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //
      }elseif (($mayus==2)&&($encript==2)) { //Minusculas y desencriptar
        //Revision de cadena llena
          if (!empty($dato)) {
            //No se realiza nada
          }else{
            //Se imprime un mensaje de alerta
            echo "<script>alert('VALOR O DATO INVALIDO');</script>";
            //Se deiene el procedimiento
            die();
          }
        //Desencriptacion
          $campo = desencriptar_ligero($dato);
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($campo),ENT_QUOTES);
        //Conversion a minusculas
          $campo = strtolower($campo);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //
      }elseif (($mayus==0)&&($encript==2)) { //Solo desencriptar
        //Revision de cadena llena
          if (!empty($dato)) {
            //No se realiza nada
          }else{
            //Se imprime un mensaje de alerta
            echo "<script>alert('VALOR O DATO INVALIDO');</script>";
            //Se deiene el procedimiento
            die();
          }
        //Desencriptacion
          $campo = desencriptar_ligero($dato);
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($campo),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //
      }elseif(($mayus==1)&&($encript==0)) { //Mayusculas
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //Conversion a mayúsculas
          $campo = mb_strtoupper($campo);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //
      }elseif (($mayus==2)&&($encript==0)) { //Minusculas
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($dato)) {
              //No se realiza nada
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Conversion a minusculas
          $campo = strtolower($campo);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //
      }elseif (($mayus==0)&&($encript==0)) { //Ningun formato
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($dato)) {
              //No se realiza nada
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //Limpieza del campo
          $campo = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (!empty($campo)) {
              $campo = $campo;
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
              //Se deiene el procedimiento
              die();
            }
          }
        //
      }
      //Se regresa el dato ya formateado
      return $campo;
    }
  //Función para enviar correo y dar una respuesta
    function manda_correo($cabecera,$destino,$mensaje,$respuesta){
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->CharSet="utf-8";
        $mail->Host=HOST_SEND;
        $mail->SMTPAuth=true;
        $mail->Username=MAIL_SEND;
        $mail->Password=PASS_SEND ;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port=PORT_SEND;
        $mail->setFrom(MAIL_SEND);
        $mail->addAddress($destino);
        $mail->isHTML(true);
        $mail->Subject=$cabecera;
        $mail->Body=$mensaje;
        $mail->AltBody=$mensaje;
        $mail->send();
        return $respuesta;
      } catch (Exception $e) {
        //Almaceno el error en una variabLe
          $error=$e->getMessage();
        //Ubico el archivo desde donde se presenta el error
          $archivo=__FILE__."::Funcion manda_correo";
        //Mando a escribir el mensaje
          escribir_log($error,$mail,$archivo);
        //Detengo el procedimiento
          die();
        //
      }
    }
  //Función para enviar correo y dar una respuesta
    function notifica_it($cabecera,$mensaje,$respuesta){
      $destino=Null;
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->CharSet="utf-8";
        $mail->Host=HOST_SEND;
        $mail->SMTPAuth=true;
        $mail->Username=MAIL_SEND;
        $mail->Password=PASS_SEND ;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port=PORT_SEND;
        $mail->setFrom(MAIL_SEND);
        //Defino la sentencia que va a buscar los correos
          $sentencia="
            SELECT a.correo from correos as a
              join usuario as b on a.clave_usuario=b.clave
              join puestos as c on b.clave=c.clave_usuario
            where c.departamento='INFORMÁTICA Y TELECOMUNICACIONES' and a.principal=true
          ";
        //ejecuto la sentencia y almaceno lo obtenido en una variable
          $resultado_busqueda_mail=retorna_datos_central($sentencia);
          if ($resultado_busqueda_mail['rowCount'] > 0) {
            //Almaceno los datos obtenidos
              $datos_mail = $resultado_busqueda_mail['data'];
            // Recorrer los datos y llenar las filas
              foreach ($datos_mail as $fila_mail) {
                //Agrego el correo al listado de destinatarios
                $mail->addAddress($fila_mail['correo']);
              }
            //
          }
        //
        $mail->isHTML(true);
        $mail->Subject=$cabecera;
        $mail->Body=$mensaje;
        $mail->AltBody=$mensaje;
        $mail->send();
        return $respuesta;
      } catch (Exception $e) {
        //Almaceno el error en una variabLe
        $error=$e->getMessage();
        //Ubico el archivo desde donde se presenta el error
        $archivo=__FILE__."::Funcion manda_correo";
        //Mando a escribir el mensaje
        escribir_log($error,$sentencia,$archivo);
        //Detengo el procedimiento
        die();
      }
    }
  //Función para escribir el log de fallos
    function escribir_log($error,$sentencia,$archivo) {
      //Le quito los saltos de linea a la sentencia
      $sentencia = str_replace("\n",'',$sentencia);
      //Creeo la referencia de tiempo
      $referencia=referencia_horaria();
      //Evaluo si existe un logueo
      if(isset($_SESSION[UBI]['id'])){
        $usuario=campo_limpiado($_SESSION[UBI]['nombre'],2,0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],2,0,0);
        $linea="$referencia!!$usuario!!$error!!$sentencia!!$archivo";
      }else{
        $linea="$referencia!!NO IDENTIFICADO!!$error!!$sentencia!!$archivo";
      }
      //Encripto el texto
      $texto=campo_limpiado($linea,1,0,0);
      // Ruta y nombre del archivo de log
      $nombre_txt = A_LOGS . ahora(1) . "-ErrorLog.txt";
      // Intentar abrir el archivo
      $archivo = fopen($nombre_txt, 'a+');
      if ($archivo) {
        // Escribir en el archivo
        fwrite($archivo, $texto . "\n");
        // Cerrar el archivo
        fclose($archivo);
        echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (CODIGO: $referencia)');</script>";
      } else {
        echo "<script>alert('¡ERROR!');</script>";
      }
    }
  //Función para obtener los datos de una sentencia ejecutada en la BD central
    function retorna_datos_sistema($sentencia,$direccion = "") {
      include A_CONNECTION;
      try {
        //Preparo la sentencia a ejecutar
          $sql = $conn->prepare($sentencia);
        //Ejecutar la sentencia
          $sql->execute();
        // Obtener el número de filas afectadas
          $rowCount = $sql->rowCount();
        // Obtener los datos de la tabla
          $datos = array();
          while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
              $datos[] = $fila;
          }
        // Cerrar el cursor
          $sql->closeCursor();
        //se cierra la conexion
          $sql=Null;
          $conn=Null;
        // Retornar el resultado
          return array('data' => $datos, 'rowCount' => $rowCount);;
        //
      } catch (PDOException $e) {
        //Almaceno el error en una variabLe
          $error=$e->getMessage();
        //Ubico el archivo desde donde se presenta el error
          $archivo=__FILE__."::Funcion busca_dato_plataforma";
        //Mando a escribir el mensaje
          escribir_log($error,$sentencia,$archivo);
        //Detengo el procedimiento
          die();
        //
      }
    }
  //Función para buscar la cantidad de registros existentes
    function busca_existencia($sentencia,$direccion = "") {
      include A_CONNECTION;
      try {
        //Preparo la sentencia a ejecutar
        $sql = $conn->prepare($sentencia);
        //Ejecutar la sentencia
        $sql->execute();
        //Asocio los datos de la tabla obtenidos
        $tabla=$sql->fetch(PDO::FETCH_ASSOC);
        //Retorna el valor obtenido
        return $tabla['exist'];
        //finalizo el cursor
          $sql->closeCursor();
        //se cierra la conexion
          $sql=Null;
          $conn=Null;
        //
      } catch (PDOException $e) {
        //Almaceno el error en una variabLe
        $error=$e->getMessage();
        //Ubico el archivo desde donde se presenta el error
        $archivo=__FILE__."::Funcion busca_existencia";
        //Mando a escribir el mensaje
        escribir_log($error,$sentencia,$archivo);
        //Detengo el procedimiento
        die();
      }
    }
  //Función para ejecutar sentencias dentro de la base de datos de la plataforma
    function ejecuta_sentencia_sistema($sentencia,$mensaje,$direccion = "") {
      include A_CONNECTION;
      try {
        //Preparo la sentencia a ejecutar
        $sql=$conn->prepare($sentencia);
        //ejecuto la sentencia
        $res=$sql->execute();
        //finalizo el cursor
          $sql->closeCursor();
        //se cierra la conexion
          $sql=Null;
          $conn=Null;
        //Retorna el valor de mensaje dado
        return $mensaje;
        //
      } catch (PDOException $e) {
        //Almaceno el error en una variabLe
        $error=$e->getMessage();
        //Ubico el archivo desde donde se presenta el error
        $archivo=__FILE__."::Funcion ejecuta_sentencia_sistema";
        //Mando a escribir el mensaje
        escribir_log($error,$sentencia,$archivo);
        //Detengo el procedimiento
        die();
      }
    }
  // Función para ejecutar sentencias dentro de la base de datos de la plataforma
    function registra_bitacora($campos,$datos,$direccion = "") {
      //Defino 2 variables vacias
        $texto_campos=Null;
        $texto_datos=Null;
      //Se obtiene datos iniciales
        $fecha=ahora(1);
        $hora=ahora(2);
        $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
      //Procesamiento de arreglo de campos
        //Obtengo la cantidad de elementos en el arreglo
          $cantidad_campos=count($campos);
        //Creeo un ciclo for para recorrer todo el arreglo
          for ($i=0; $i < $cantidad_campos ; $i++) { 
            //Concateno el campo y le agrego una coma y espacio al final
              $texto_campos.=$campos[$i].", ";
            //
          }
        //
      //Procesamiento de arreglo de datos
        //Obtengo la cantidad de elementos en el arreglo
          $cantidad_datos=count($datos);
        //Creeo un ciclo for para recorrer todo el arreglo
          for ($i=0; $i < $cantidad_datos ; $i++) { 
            //Concateno el campo y le agrego una coma y espacio al final
              $texto_datos.="'".$datos[$i]."', ";
            //
          }
        //
      //Se defina la sentencia a ejecutar
        $sentencia="
          INSERT INTO bitacora (
            fecha,
            hora,
            $texto_campos
            usuario
          ) VALUES (
            '$fecha',
            '$hora',
            $texto_datos
            '$usuario'
          );
        ";
      //Se ejecuta la sentencia
        $devuelto=ejecuta_sentencia_sistema($sentencia,true,"::Funcion registra_bitacora");
      //
    }
  //Funcion para generar los sevicios del dia
    function genera_corridas($fecha,$inicio){
      //Defino una variable para contar los registros realizados
        $contador=0;
      //Verifico si se solicitaron las corridas de una taquilla en especifico
        if($inicio!=''){ $agregado=" and punto_origen='$inicio'"; }else{ $agregado=Null; }
      //Defino la sentencia para obtener todas las corridas activas
        $sentencia="SELECT * FROM servicio where estatus=true$agregado";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          // Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla_servicio) {
              //Creo una variable especial
                $id_servicio=$tabla_servicio['id'];
                $nombre_ruta=$tabla_servicio['nombre_ruta'];
                $identificador=$tabla_servicio['identificador'];
                $id_punto_inicial=$tabla_servicio['id_punto_inicial'];
                $punto_inicial=$tabla_servicio['punto_inicial'];
                $id_punto_origen=$tabla_servicio['id_punto_origen'];
                $punto_origen=$tabla_servicio['punto_origen'];
                $id_punto_final=$tabla_servicio['id_punto_final'];
                $punto_final=$tabla_servicio['punto_final'];
                $hora=$tabla_servicio['hora'];
              //Reviso su existencia en la tabla de servicios
                $sentencia="
                  SELECT count(id) as exist FROM corrida WHERE 
                  identificador='$identificador' and 
                  id_servicio=$id_servicio and 
                  servicio='$nombre_ruta' and 
                  fecha='$fecha' and 
                  hora='$hora' and 
                  id_punto_origen=$id_punto_origen and 
                  punto_origen='$punto_origen';
                ";
                $existencia=busca_existencia($sentencia);
                if ($existencia<1) {
                  //Defino la creacion del servicio
                    $sentencia="
                      INSERT INTO corrida (
                        identificador,
                        id_servicio,
                        servicio,
                        fecha,
                        hora,
                        id_punto_origen,
                        punto_origen,
                        estatus
                      ) VALUES (
                        '$identificador',
                        $id_servicio,
                        '$nombre_ruta',
                        '$fecha',
                        '$hora',
                        $id_punto_origen,
                        '$punto_origen',
                        1
                      );
                    ";
                  //Realizo la ejecucion de la sentencia
                    $devuelto=ejecuta_sentencia_sistema($sentencia,true);
                  //Si la insercion se realizo correctamente
                    if ($devuelto==true) { $contador++; }
                  //
                }
              //
            }
          //
        }
      //Retorno la cantidad de registros creados
        return $contador;
      //
    }
  //Funcion para registrar los boletos vendidos
    function registra_boletos($array){
      //Obtengo la clave del usuario en turno
        $clave=campo_limpiado($_SESSION[UBI]['clave'],2);
      //Si viene por venta web, el estado se marca como
        if ($clave=='WEB') { $estado=1; }else{ $estado=2; }
      //Almaceno y separo los datos enviados
        $referencia=campo_limpiado($array['referencia'],2,0);
        $fecha=campo_limpiado($array['fecha'],2);
        $taquilla=campo_limpiado($array['taquilla'],2);
        $origen=campo_limpiado($array['origen'],2);
        $id_destino=campo_limpiado($array['id_destino'],2);
        $nombre_destino=campo_limpiado($array['nombre_destino'],2);
        $precio_destino=campo_limpiado($array['precio_destino'],2);
        $corrida=campo_limpiado($array['corrida'],2);
        $hora_corrida=campo_limpiado($array['hora'],2);
        $f_actual=ahora(1);
        $h_actual=ahora(2);
      //Defino una variable de contador vacio
        $contador=Null;
      //Separo los datos de los boletos
        $datos_boletos=explode('$$',campo_limpiado($array['boletos']));
      //Obtengo la cantidad de boletos a insertar
        $numero_filas=count($datos_boletos);
      //Si la cantidad de filas no es nula
        if ($numero_filas!=Null) {
          //Itero un ciclo para revisar el arreglo
            for ($i=0; $i <$numero_filas ; $i++) {
              //Separo los datos de cada fila en columnas
                $columnas=explode('||',$datos_boletos[$i]);
              //Almaceno cada dato en una variable
                $asiento=$columnas[0];
                $nombre_pasajero=$columnas[1];
                $tipo_boleto=$columnas[2];
                $precio=number_format($columnas[3]);
              //Reviso si ya existe el boleto en la base de datos
                $sentencia="
                  SELECT 
                    count(id) as exist
                  FROM 
                    boleto 
                  where
                    punto_venta='$taquilla' and 
                    origen='$origen' and 
                    destino='$nombre_destino' and 
                    tipo ='$tipo_boleto' and 
                    precio='$precio' and 
                    pasajero='$nombre_pasajero' and 
                    asiento='$asiento' and 
                    f_corrida='$fecha' and 
                    corrida='$corrida' and 
                    hora_corrida='$hora_corrida' and 
                    f_venta='$f_actual'
                ";
              //Se ejecuta la sentencia y se almacena el resutado
                $existencia=busca_existencia($sentencia);
              //Evaluo si ya existe un boleto con esas caracteristicas
                if ($existencia<1) {
                  //Defino la insercion del boleto
                    $sentencia="
                      INSERT INTO boleto (
                        punto_venta,
                        origen,
                        destino,
                        tipo,
                        precio,
                        pasajero,
                        asiento,
                        f_corrida,
                        corrida,
                        hora_corrida,
                        f_venta,
                        h_venta,
                        usuario,
                        referencia,
                        estado
                      ) VALUES (
                        '$taquilla',
                        '$origen',
                        '$nombre_destino',
                        '$tipo_boleto',
                        '$precio',
                        '$nombre_pasajero',
                        '$asiento',
                        '$fecha',
                        '$corrida',
                        '$hora_corrida',
                        '$f_actual',
                        '$h_actual',
                        '$clave',
                        '$referencia',
                        '$estado'

                      );
                    ";
                  //Realizo la ejecucion de la sentencia
                    $devuelto=ejecuta_sentencia_sistema($sentencia,true);
                  //Si la insercion se realizo correctamente
                    if ($devuelto==true) { $contador++; }
                  //
                }
              //
            }
          //
        }
      //Si se elaboro de manera correcta el registro, retorno la referencia
        return $referencia;
      //
    }
  //Funcion para la liquidacion de ruta larga del operador 
    function liquidacion_operador($dto){
      //Obtengo la clave de quien realiza la liquidacion
        $clave_liquida=campo_limpiado($_SESSION[UBI]['clave'],2);
      //Defino la fecha en la que se esta realizando la liquidacion
        $fecha_liquida=ahora(1);
      //Obtengo los datos y los separo
        $pre=explode("||",campo_limpiado($dto,2));
        $id_unidad=$pre[0];
        $numero_unidad=$pre[1];
        $fecha_trabajo=$pre[2];
        $operador=$pre[3];
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
      //Reviso si ya existe una liquidacion en la tabla
        $devuelto=busca_existencia("SELECT sum(id) AS exist FROM liq_op_rl where unidad=$id_unidad and operador='$operador' and fecha='$fecha_trabajo'");
      //Evaluo el resultado
        if ($devuelto == 0) {
          //Calcula las ventas totales
            $ventas_totales=$boletos_sistema+$paqueterias_sistema;
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
                0,
                $comision,
                '$clave_liquida',
                '$fecha_liquida',
                2
              );
            ";
          //La ejecuto
            $resultado=ejecuta_sentencia_sistema($sentencia,true);
          //Evaluo el resulado
            if ($resultado === true) {
              //Imprimo un mensaje de confirmacion y continuo con el proceso
                echo "
                  <script>
                    alert('LIQUIDACION REGISTRADA, FAVOR DE REVISAR LA INFORMACION Y COMPLETAR LOS DATOS FALTANTES');
                  </script>
                ";
              //
            }
          //
        }else{
          //Defino el ID de la liquidacion
            $id_liquidacion=$devuelto;
          //Actualizo los datos de venta de sistema
            $sentencia="UPDATE liq_op_rl SET boletos_sistema=$boletos_sistema, paqueterias_sistema=$paqueterias_sistema WHERE id=$id_liquidacion";
          //La ejecuto
            $resultado=ejecuta_sentencia_sistema($sentencia,true);
          //Evaluo el resulado
            if ($resultado === true) {
              //Recalculo los importes y la comision
                recalcular_liq_op_rl($id_liquidacion,FALSE);
              //Imprimo un mensaje de confirmacion
                echo "
                  <script>
                    alert('YA EXISTE UNA LIQUIDACION DE ESTE OPERADOR CON ESTA UNIDAD');
                  </script>
                ";
              //
            }
          //
        }
      //
    }
  //Funcion para la liquidacion de ruta larga del operador 
    function recalcular_liq_op_rl($id_liquidacion,$mensaje){
      // Defino la sentencia para obtener los datos de la liquidacion
        $sentencia="
          SELECT * FROM liq_op_rl WHERE id=$id_liquidacion;
        ";
      //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos_sistema($sentencia);
      //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
          //Almaceno los datos obtenidos
            $resultado = $resultado_sentencia['data'];
          //Recorrer los datos y llenar las filas
            foreach ($resultado as $tabla) {
              //Creeo las variables del identificador
                $boletos_sistema=$tabla['boletos_sistema'];
                $boletos_talonario=$tabla['boletos_talonario'];
                $paqueterias_sistema=$tabla['paqueterias_sistema'];
                $paqueterias_talonario=$tabla['paqueterias_talonario'];
                $talonario=$tabla['talonario'];
              //
            }
          //
        }
      //Calcula las ventas totales
        $ventas_totales=$boletos_sistema+$boletos_talonario+$paqueterias_sistema+$paqueterias_talonario+$talonario;
      //Calculo el iva de las ventas totales
        $iva=$ventas_totales*0.16;
      //Calculo las ventas sin iva
        $ventas_sin_iva=$ventas_totales/1.16;
      //Calculo la comision
        $comision=$ventas_sin_iva*0.1;
      //Defino la sentencia para actualizar la comision
        $sentencia="UPDATE liq_op_rl SET comision='$comision' WHERE id=$id_liquidacion";
      //La ejecuto
        $resultado=ejecuta_sentencia_sistema($sentencia,true);
      //Evaluo el resulado
        if ($resultado === true) {
          //Evaluo si existe un mensaje a enviar y lo imprimo
            if ($mensaje===TRUE) {
              echo "
                <script>
                  alert('DATOS DE LA LIQUIDACION ACTUALIZADOS');
                </script>
              ";
            }
          //
        }
      //
    }
  //Funcion para obtener el tipo de transaccion de billetes
    function tipo_transaccion_billetes($opcion){
      //Evaluo la opcion dad y le asigno el texto correspondiente
        switch ($opcion) {
          case '1':
            $texto="FONDO DE CAJA";
          break;
          case '2':
            $texto="ARQUEO/RECOLECCION";
          break;
          case '3':
            $texto="CIERRE DE CAJA";
          break;
        }
      //Retorno el texto asignado
        return $texto;
      //
    }
?>