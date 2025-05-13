<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluyen los archivos de conexión
  include_once A_CONNECTION;
  //Obtengo el dato del usuario consultado
  $dato=explode("||", campo_limpiado($_POST['form_act'],2,0));
  $nombre=$dato[0];
  $identificador=$dato[1];
  $origen=$dato[2];
?>
<div class="card">
  <div class="card-header"><h5>DESTINOS DISPONIBLES</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla_destinos_disponibles">
      <thead>
        <tr>
          <th>#</th>
          <th>DESTINO</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia para extraccion de los detinos
          $orden_sql = "SELECT * FROM destinos WHERE destino not in (select destino from ruta where punto_origen='$origen' and identificador='$identificador')";
          try {
            $query=$conn->prepare($orden_sql);
            $query->execute();
            while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
              //extraigo los datos a variables
              $id=$tabla['id'];
              $destino=$tabla['destino'];
              $dato="'".campo_limpiado($id,1,0)."||$destino||".$_POST['form_act']."'";
              //ipresion de los datos principales
              echo "
                <tr>
                  <td>$id</td>
                  <td>$destino</td>
                  <td><a onclick=\"agregar_destino_modal($dato)\" class=\"btn btn-success text-light\"><i class='far fa-caret-square-right' style=\"font-size:24px\"></i></a></td>
                </tr>
              ";
              //
            }
          } catch (PDOException $e) {
            //Almaceno el error en una variabLe
            $error=$e->getMessage();
            //Ubico el archivo desde donde se presenta el error
            $archivo=__FILE__;
            //Mando a escribir el mensaje
            escribir_log($error,$orden_sql,$archivo);
            //Detengo el procedimiento
            die();
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  //Funcion para el modal de entrda de rollo
    function agregar_destino_modal(datos){
      d=datos.split('||');
      $('#agregar_destino').modal('show');
      $('#id_destino').val(d[0]);
      $('#nombre_destino').val(d[1]);
      $('#nombre_destino2').val(d[1]);
      $('#dato_destino').val(d[2]);
    }
  //funcion para agregar un permiso al usuario
    function agregar_destino(opcion){
      var url="model/operacion/update/agregar_destino_ruta.php"
      $.ajax({
        type: "POST",
        url:url,
        data: $("#frm_agregar_destino").serialize(),
        beforeSend: function(){
          $('#respuesta_agregar_destino').html("<div class='spinner-border'></div>");
        },
        success: function(datos){$('#respuesta_agregar_destino').html(datos);}
      });
    }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_destinos_disponibles').DataTable( {
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