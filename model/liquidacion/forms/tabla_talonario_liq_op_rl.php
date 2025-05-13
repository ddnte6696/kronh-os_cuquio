<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
    $clave=$pre[3];
    $enviado=campo_limpiado("$id_unidad||$unidad||$fecha_trabajo||$clave",1);
  // Importe y cantidad de boletos vendidos para el operador
    $sentencia="
      SELECT 
        COUNT(a.id) AS cuenta_boletos,
        SUM(a.importe) as suma_boletos
      FROM talonarios_liquidados as a
      JOIN talonario as b on a.talonario=b.id 
      WHERE 
        a.usuario='$clave' and 
        a.fecha='$fecha_trabajo' and 
        b.tipo=1 and 
        b.uso=2 and 
        a.unidad=$id_unidad
      ;
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
            $cuenta_boletos=$tabla['cuenta_boletos'];
            $suma_boletos=number_format($tabla['suma_boletos'],2);
          //
        }
      //
    }
  //

?>
<script type="text/javascript">
  //funcion que calcula la venta, el porcentaje de comision y la comision del operador
    function actualiza_boleto(opcion){
      //Valores de la liquidacion actual
        var dato=<?php echo "'".$enviado."'"; ?>;
      //Folio
        var id_talonario=opcion;
      //Precio del boleto
        var precio=$("#"+id_talonario).val();
      //Indico la direccion donde se va a insertar los boletos
        var url="model/liquidacion/update/act_pas_tal_op_rl.php"
      //inicio el traspaso de los datos por cada boleto
        $.ajax({
            type: "POST",
            url:url,
            data:{
              dato:dato,
              id_talonario:id_talonario,
              precio:precio,
            },
            success: function(datos){$('#mensajes').html(datos);}
        });
      //
    }
  //funcion para brincar al siguiente input al dar enter
    document.addEventListener('keypress', function(evt) {
      // Si el evento NO es una tecla Enter
        if (evt.key !== 'Enter') { return; }
        let element = evt.target;
      // Si el evento NO fue lanzado por un elemento con class "focusNext"
        if (!element.classList.contains('focusNext')) { return; }
      // AQUI logica para encontrar el siguiente
        let tabIndex = element.tabIndex + 1;
        var next = document.querySelector('[tabindex="'+tabIndex+'"]');
      // Si encontramos un elemento
        if (next) {
          next.focus();
          event.preventDefault();
        }
      //
    });
  //
</script>
<table class="table table-bordered table-sm table-striped text-center" >
  <thead>
    <tr>
      <th>CANTIDAD</th>
      <th>TOTAL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <?php
        echo "
          <th>$cuenta_boletos</th>
          <th>$suma_boletos</th>
        ";
      ?>
    </tr>
  </tbody>
</table>
<div class="card">
  <div class="card-text" id="mensajes"></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-striped" id="tabla_talonario">
      <thead>
        <tr>
          <th>FOLIO</th>
          <th>IMPORTE</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // Importe y cantidad de boletos vendidos para el operador
            $sentencia="
              SELECT 
                a.id as id_boleto,
                a.folio as folio,
                a.importe as importe,
                b.serie as serie 
              FROM talonarios_liquidados as a
              JOIN talonario as b on a.talonario=b.id 
              WHERE 
                a.usuario='$clave' and 
                a.fecha='$fecha_trabajo' and
                b.tipo=1 and 
                b.uso=2 and 
                a.unidad=$id_unidad
              ;
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
                    $id_boleto=$tabla['id_boleto'];
                    $folio=$tabla['serie']." ".$tabla['folio'];
                    $importe=$tabla['importe'];
                    echo "
                      <tr>
                        <td>$folio</td>
                        <td style=\"width: 150px;\">
                          <input type=\"number\"  class='form-control' id=\"$id_boleto\" name=\"$id_boleto\" value=\"$importe\" onchange=\"actualiza_boleto($id_boleto);\" class=\"focusNext\" tabindex=\"$id_boleto\">
                        </td>
                      </tr>
                    ";
                  //
                }
              //
            }
          //
        ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_talonario').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[],
        },
      } );
      table.on( 'responsive-resize', function ( e, datatable, columns ) {
        var count = columns.reduce( function (a,b) {
          return b === false ? a+1 : a;
        }, 0 );
        console.log( count +' column(s) are hidden' );
      } );
    } );
  //
</script>