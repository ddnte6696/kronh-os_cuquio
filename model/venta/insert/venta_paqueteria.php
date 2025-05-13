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
  //Obtengo datos de la sesion
    $taquilla_actual=campo_limpiado($_SESSION[UBI]['taquilla'],2,0);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Identifico si existe ya un corte registrado
    $sentencia="SELECT count(id) AS exist FROM corte where punto_venta='$taquilla_actual' and usuario='$clave' and fecha='".ahora(1)."';";
    $retorno=busca_existencia($sentencia);
  //Identifico si ya hay un corte de este usuario registrado
    if ($retorno>0) {
      echo "<script>venta_paqueteria();</script>";
    }
  //Verifico si alguno de los campos contiene datos y sino, Imprimo un mensaje de error
    if(isset($_POST['n_envia']) || isset($_POST['t_envia']) || isset($_POST['n_recibe']) || isset($_POST['t_recibe']) || isset($_POST['cantidad']) || isset($_POST['peso']) || isset($_POST['descripcion'])){
      //Obtengo los datos enviados por el formulario
        //Obtengo y separo los datos del origen
          $dat_origen=explode("||",campo_limpiado($_POST['origen'],2,0));
          $id_origen=campo_limpiado($dat_origen[0],0,0);
          $origen=campo_limpiado($dat_origen[1],0,0);
        //Obtengo y separo los datos del destino
          $dat_destino=explode("||",campo_limpiado($_POST['destino'],2,0));
          $id_destino=campo_limpiado($dat_destino[0],0,0);
          $destino=campo_limpiado($dat_destino[1],0,0);
          $precio_destino=campo_limpiado($dat_destino[2],0,0);
        //Defino algunas variables especificas
          $punto_venta=$origen;
          $fecha=ahora(1);
          $hora=ahora(2);
          $referencia="$id_origen-".referencia_temporal()."-$id_destino";
          $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
        //Almaceno los demas datos en variables
          $nombre_envia=campo_limpiado($_POST['n_envia'],0,1);
          $telefono_envia=campo_limpiado($_POST['t_envia'],0,1);
          $nombre_recibe=campo_limpiado($_POST['n_recibe'],0,1);
          $telefono_recibe=campo_limpiado($_POST['t_recibe'],0,1);
          $cantidad=campo_limpiado($_POST['cantidad'],0,0);
          $peso=campo_limpiado($_POST['peso'],0,0);
          $descripcion=campo_limpiado($_POST['descripcion'],0,1);
          $observacion=campo_limpiado($_POST['observacion'],0,1);
        //
      //Reviso la cantidad de paquetes que se van a enviar y sino envio un mensaje de error
        if ($cantidad > 0) {
          //Calculo el multiplicador segun el peso
            if ($peso <= 20) {
              $multiplicador=1;
            }else if($peso > 20 && $peso <= 30){
              $multiplicador=1.6;
            }else if($peso > 30 && $peso <= 40){
              $multiplicador=2;
            }else if($peso > 40 && $peso <= 50){
              $multiplicador=2.6;
            }else if($peso > 50 && $peso <= 60){
              $multiplicador=3;
            }else if($peso > 60 && $peso <= 70){
              $multiplicador=3.6;
            }else if($peso > 70 && $peso <= 80){
              $multiplicador=4;
            }else if($peso > 80 && $peso <= 90){
              $multiplicador=4.6;
            }else if($peso > 90 && $peso <= 100){
              $multiplicador=5;
            }else{
              //Si el peso no entra en ninguna de las categorias
                echo "
                  <script>
                    alert('EL PESO EXCEDE LO PERMITIDO PARA SU TRANSPORTE');
                    document.getElementById('boton_registro').setAttribute('disabled','');
                  </script>
                ";
              //Detengo la ejecucion del programa
                die();
              //
            }
          //Obtengo el precio por paquete
            $precio=number_format(($multiplicador*$precio_destino),0,'');
          //Obtengo el total de la venta
            $total=number_format(($precio*$cantidad),0,'');
          //
        }else{
          //Si el peso no entra en ninguna de las categorias
            echo "
              <script>
                alert('LA CANTIDAD DE PAQUETES NO PUEDE SER 0');
                document.getElementById('boton_registro').setAttribute('disabled','');
              </script>
            ";
          //Detengo la ejecucion del programa
            die();
          //
        }
      //Verifico si existe una paqueteria registrada con los mismos datos
        $sentencia="
          SELECT 
            COUNT(id) as exist 
          FROM 
            paquete 
          where 
            punto_venta='$punto_venta' and 
            origen='$origen' and 
            nombre_envia='$nombre_envia' and 
            telefono_envia='$telefono_envia' and 
            destino='$destino' and 
            nombre_recibe='$nombre_recibe' and 
            telefono_recibe='$telefono_recibe' and 
            precio='$precio' and 
            cantidad='$cantidad' and 
            peso='$peso' and 
            descripcion='$descripcion' and 
            observacion='$observacion' and 
            total='$total' and 
            fecha='$fecha'
          ;
        ";
        $existencia=busca_existencia($sentencia);
        if ($existencia<1) {
          //Se procede a hacer la insercion de la paqueteria
            $sentencia="
              INSERT INTO paquete(
                punto_venta,
                origen,
                nombre_envia,
                telefono_envia,
                destino,
                nombre_recibe,
                telefono_recibe,
                precio,
                cantidad,
                peso,
                descripcion,
                observacion,
                total,
                fecha,
                hora,
                referencia,
                usuario_vende,
                estado
              ) VALUES (
                '$punto_venta',
                '$origen',
                '$nombre_envia',
                '$telefono_envia',
                '$destino',
                '$nombre_recibe',
                '$telefono_recibe',
                $precio,
                $cantidad,
                '$peso',
                '$descripcion',
                '$observacion',
                $total,
                '$fecha',
                '$hora',
                '$referencia',
                '$clave',
                1
              );
            ";
          //Realizo la ejecucion de la sentencia
            $devuelto=ejecuta_sentencia_sistema($sentencia,true);
          //Si la insercion se realizo correctamente
            if ($devuelto==true) {
              //Defino la sntencia para obtener el folio de la paqueteria
                $sentencia="
                  SELECT 
                    id AS exist 
                  FROM 
                    paquete 
                  WHERE 
                    referencia='$referencia'
                  ;
                ";
              //Obtengo el folio de la paqueteria y lo almaceno en su variable
                $folio=busca_existencia($sentencia);
              //Defino el escript para la impresion
                echo "
                  <script type=\"text/javascript\">
                    $(document).ready(imprimirDiv('respuesta_paqueterias'));
                  </script>
                ";
              //Realizo la impresion del comprobante
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
                        <th colspan='2' class='text-center'>COMPROBANTE DE PAQUETE</th>
                      </tr>
                      <tr>
                        <th>Folio</th>
                        <td><h5><strong>$folio</strong></h5></td>
                      </tr>
                      <tr>
                        <th>Fecha</th>
                        <td><strong>$fecha</strong></td>
                      </tr>
                      <tr>
                        <th>Origen</th>
                        <td><strong>$origen<strong></td>
                      </tr>
                      <tr>
                        <th>Envia</th>
                        <td><strong>$nombre_envia<strong></td>
                      </tr>
                      <tr>
                        <th>Telefono</th>
                        <td><strong>$telefono_envia<strong></td>
                      </tr>
                      <tr>
                        <th>Destino</th>
                        <td><strong>$destino<strong></td>
                      </tr>
                      <tr>
                        <th>Recibe</th>
                        <td><strong>$nombre_recibe<strong></td>
                      </tr>
                      <tr>
                        <th>Telefono</th>
                        <td><strong>$telefono_recibe<strong></td>
                      </tr>
                      <tr>
                        <th>Cantidad</th>
                        <td>$cantidad</td>
                      </tr>
                      <tr>
                        <th>Peso</th>
                        <td>$peso Kg</td>
                      </tr>
                      <tr>
                        <th>Descripcion</th>
                        <td>$descripcion</td>
                      </tr>
                      <tr>
                        <th>Observaciones</th>
                        <td>$observacion</td>
                      </tr>
                      <tr>
                        <th>Total</th>
                        <td>$ ".number_format($total,2)."</td>
                      </tr>
                      <tr>
                        <td class='text-center'>Referencia</th>
                        <td class='text-center'>$referencia</td>
                      </tr>
                      <tr>
                        <td class='text-center'>Fecha y hora de impresion</th>
                        <td class='text-center'>".ahora(3)."</td>
                      </tr>
                    </tbody>
                  </table>
                  <hr media='print'>
                  <table>
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
                        <th colspan='2' class='text-center'>COMPROBANTE DE USUARIO</th>
                      </tr>
                      <tr>
                        <th>Folio</th>
                        <td><h5><strong>$folio</strong></h5></td>

                      </tr>
                      <tr>
                        <th>Origen</th>
                        <td><strong>$origen<strong></td>
                      </tr>
                      <tr>
                        <th>Envia</th>
                        <td><strong>$nombre_envia<strong></td>
                      </tr>
                      <tr>
                        <th>Telefono</th>
                        <td><strong>$telefono_envia<strong></td>
                      </tr>
                      <tr>
                        <th>Destino</th>
                        <td><strong>$destino<strong></td>
                      </tr>
                      <tr>
                        <th>Recibe</th>
                        <td><strong>$nombre_recibe<strong></td>
                      </tr>
                      <tr>
                        <th>Telefono</th>
                        <td><strong>$telefono_recibe<strong></td>
                      </tr>
                      <tr>
                        <th>Cantidad</th>
                        <td>$cantidad</td>
                      </tr>
                      <tr>
                        <th>Peso</th>
                        <td>$peso Kg</td>
                      </tr>
                      <tr>
                        <th>Descripcion</th>
                        <td>$descripcion</td>
                      </tr>
                      <tr>
                        <th>Observaciones</th>
                        <td>$observacion</td>
                      </tr>
                      <tr>
                        <th>Importe</th>
                        <td>$ ".number_format($total,2)."</td>
                      </tr>
                      <tr>
                        <td class='text-center'>Referencia</th>
                        <td class='text-center'>$referencia</td>
                      </tr>
                      <tr>
                        <td class='text-center'>Fecha y hora de impresion</th>
                        <td class='text-center'>".ahora(3)."</td>
                      </tr>
                    </tbody>
                  </table>
                  <hr media='print'>
                  <table class='table table-responsive table-sm'>
                    <tbody>
                      <tr>
                        <th colspan='2' class='text-center'>
                          <img src='".LOGO_YAHUALICA."' class='img-fluid' style='width: 200px' />
                        </th>
                      </tr>
                      <tr>
                        <th colspan='2' class='text-center'>
                          OMNIBUS YAHUALICA GUADALAJARA S.A. DE CV.
                        </th>
                      </tr>
                      <tr>
                        <th colspan='2' class='text-center'>
                          COMPROBANTE DE OPERADOR
                        </th>
                      </tr>
                      <tr>
                        <th>Folio</th>
                        <td><h5><strong>$folio</strong></h5></td>
                      </tr>
                      <tr>
                        <th>Origen</th>
                        <td><strong>$origen<strong></td>
                      </tr>
                      <tr>
                        <th>Envia</th>
                        <td><strong>$nombre_envia<strong></td>
                      </tr>
                      <tr>
                        <th>Telefono</th>
                        <td><strong>$telefono_envia<strong></td>
                      </tr>
                      <tr>
                        <th>Destino</th>
                        <td><strong>$destino<strong></td>
                      </tr>
                      <tr>
                        <th>Recibe</th>
                        <td><strong>$nombre_recibe<strong></td>
                      </tr>
                      <tr>
                        <th>Telefono</th>
                        <td><strong>$telefono_recibe<strong></td>
                      </tr>
                      <tr>
                        <th>Importe</th>
                        <td>$ ".number_format($total,2)."</td>
                      </tr>
                      <tr>
                        <td class='text-center'>Referencia</th>
                        <td class='text-center'>$referencia</td>
                      </tr>
                      <tr>
                        <td class='text-center'>Fecha y hora de impresion</th>
                        <td class='text-center'>".ahora(3)."</td>
                      </tr>
                    </tbody>
                  </table>
                ";
              //
            }
          //
        }else{
          echo "<script>alert('YA EXISTE UNA PAQUETERIA REGISTRADA CON ESTOS DATOS');</script>";
          die();
        }
    }else{
       echo "<script>alert('HAY CAMPOS PENDIENTES DE LLENAR');</script>";
       die();
    }
  //
?>