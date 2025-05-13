<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
  	$clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $id_talonario=campo_limpiado($_POST['id_talonario'],2,0);
    $id_liquidacion=campo_limpiado($_POST['dato'],2,0);
  //Defino la sentencia de busqueda
    $sentencia="SELECT * FROM corte where id=$id_liquidacion";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
            $usuario=$tabla['usuario'];
            $fecha=$tabla['fecha'];
        }
      //
    }
  //
  //Obtengo el folio actual y final de la tabla
    $actual = busca_existencia("SELECT actual as exist FROM talonario WHERE id=$id_talonario");
    $final = busca_existencia("SELECT final as exist FROM talonario WHERE id=$id_talonario");
?>
<script type="text/javascript">
  //funcion que calcula la venta, el porcentaje de comision y la comision del operador
    function inserta_boleto(opcion){
      //Valores de la liquidacion actual
        var dato=$("#target").val();
      //Folio
        var folio=opcion;
      //Precio del boleto
        var precio=$("#"+folio).val();
      //Indico la direccion donde se va a insertar los boletos
        var url="model/liquidacion/update/pas_tal_taq_rl.php"
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