<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $pre=explode("||",campo_limpiado($_POST['dato'],2,0));
    $id_talonario=campo_limpiado($_POST['id_talonario'],2,0);
  //Los separo para poder moverlos a variables especificas
    $id_unidad=$pre[0];
    $unidad=$pre[1];
    $fecha_trabajo=$pre[2];
    $clave=$pre[3];
    $enviado=campo_limpiado("$id_unidad||$unidad||$fecha_trabajo||$clave",1,0);
  //Obtengo el folio actual y final de la tabla
    $actual = busca_existencia("SELECT actual as exist FROM talonario WHERE id=$id_talonario");
    $final = busca_existencia("SELECT final as exist FROM talonario WHERE id=$id_talonario");
?>
<script type="text/javascript">
  //funcion que calcula la venta, el porcentaje de comision y la comision del operador
    function inserta_boleto(opcion){
      //Valores de la liquidacion actual
        var dato=<?php echo "'".$enviado."'"; ?>;
      //Folio
        var folio=opcion;
      //Precio del boleto
        var precio=$("#"+folio).val();
      //Indico la direccion donde se va a insertar los boletos
        var url="model/liquidacion/update/pas_tal_op_rl.php"
      //inicio el traspaso de los datos por cada boleto
        $.ajax({
            type: "POST",
            url:url,
            data:{
              dato:dato,
              folio:folio,
              precio:precio,
              tal:<?php echo $id_talonario ?>
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
<div class="card">
  <div class="card-text" id="mensajes"></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-striped" id="tabla">
      <thead>
        <tr>
          <th>FOLIO</th>
          <th>IMPORTE</th>
        </tr>
      </thead>
      <tbody>
        <?php
          for ($i=$actual; $i <=$final ; $i++) {
          echo "
            <tr>
              <td>$i</td>
              <td style=\"width: 150px;\">
                <input type=\"number\"  class='form-control' id=\"$i\" name=\"$i\" onchange=\"inserta_boleto($i);\" class=\"focusNext\" tabindex=\"$i\">
              </td>
            </tr>
          ";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla').DataTable( {
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