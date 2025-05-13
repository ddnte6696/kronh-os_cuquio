<?php
    //Se revisa si la sesión esta iniciada y sino se inicia
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    //Se manda a llamar el archivo de configuración
        include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
    //Obtengo los dato enviado por el formulario
        $serie = campo_limpiado($_POST['serie'],0,1);
        $inicial = campo_limpiado($_POST['inicial'],0,1);
        $final = campo_limpiado($_POST['final'],0,1);
        $tipo = campo_limpiado($_POST['tipo'],2);
        $asignacion = campo_limpiado($_POST['asignacion'],2);
    //Verifico el tipo de boleto y obtengo los campos especificos
        switch ($asignacion) {
            case '1':
                $taquilla = campo_limpiado($_POST['taquilla'],2);
                $usuario = campo_limpiado($_POST['usuario'],2);
                $condicional='$taquilla!=""&&$usuario!=""';
                $campos="taquilla,usuario,";
                $valores="$taquilla,'$usuario',";
            break;
            case '2':
                $operador = campo_limpiado($_POST['operador'],2);
                $condicional='$operador!=""';
                $campos="usuario,";
                $valores="'$operador',";
            break;
        }
    //Verifico que el folio final sea mayor al inicial
        if ($final>$inicial) {
            //Calculo cuantos boletos se van a insertar
                $restantes=($final-$inicial)+1;
            //Verifico si no existe un boletaje con estos datos
                $sentencia="SELECT count(id) as exist from talonario where serie='$serie' and inicial=$inicial and final=$final";
                $existencia=busca_existencia($sentencia);
                if ($existencia<1) {
                    //Procedo a hacer la insercion de los datos del talonario
                        $sentencia="
                            INSERT INTO talonario(
                                serie,
                                inicial,
                                actual,
                                final,
                                restantes,
                                $campos
                                f_registro,
                                tipo,
                                uso
                            ) VALUES (
                                '$serie',
                                $inicial,
                                $inicial,
                                $final,
                                $restantes,
                                $valores
                                '".ahora(1)."',
                                $tipo,
                                $asignacion
                            );
                        ";
                    //Realizo la ejecucion de la sentencia
                        $devuelto=ejecuta_sentencia_sistema($sentencia,true);
                    //Si la insercion se realizo correctamente
                        if ($devuelto==true) {
                            echo "
                                <script>
                                    alert('EL TALONARIO SE REGISTRO Y ASIGNO CORRECTAMENTE');
                                    registrar_talonario();
                                </script>
                            ";
                        }
                    //
                }else{
                    echo "<script>alert('YA EXISTE UN TALONARIO CON LA MISMA SERIE Y FOLIOS');</script>";
                    die();
                }
            //
        }else{
            echo "<script>alert('EL FOLIO INICIAL NO PUEDE SER MAYOR AL FINAL');</script>";
            die();
        }
    //