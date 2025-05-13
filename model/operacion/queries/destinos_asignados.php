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
  <div class="card-header"><h5>DESTINOS ASIGNADOS</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla_destinos_asignados">
      <thead>
        <tr>
          <th></th>
          <th>DESTINO</th>
          <th>$ NORMAL</th>
          <th>$ MEDIO</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia para extraccion de los detinos
          $orden_sql = "SELECT * FROM ruta WHERE punto_origen='$origen' and identificador='$identificador';";
          try {
            $query=$conn->prepare($orden_sql);
            $query->execute();
            while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
              //extraigo los datos a variables
              $id=$tabla['id_destino'];
              $destino=$tabla['destino'];
              $precio_normal=$tabla['precio_normal'];
              $precio_medio=$tabla['precio_medio'];
              $dato="'".campo_limpiado(("$id||$identificador||$origen"),1,0)."'";
              $dato_modal="'".campo_limpiado($id,1,0)."||$destino||".$_POST['form_act']."||$precio_normal||$precio_medio"."'";
              //ipresion de los datos principales
              echo "
                <tr>
                  <td><a onclick=\"retirar_destino($dato)\" class=\"btn btn-danger text-light\"><i class='far fa-caret-square-left' style=\"font-size:24px\"></i></a></td>
                  <td>$destino</td>
                  <td>$ $precio_normal</td>
                  <td>$ $precio_medio</td>
                  <td>
                    <a onclick=\"editar_destino_modal($dato_modal)\" class=\"btn btn-primary text-light\" data-toggle=\"tooltip\"><i class='fas fa-edit'  style='font-size:24px'></i></a>
                  </td>
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
  //funcion para retirar un permiso al usuario
    function retirar_destino(opcion){
      var url="model/operacion/update/retirar_destino_ruta.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{
          dato:opcion
        },
        success: function(datos){$('#respuesta').html(datos);}
      });
    }
  //Funcion para el modal de entrda de rollo
    function editar_destino_modal(datos){
      d=datos.split('||');
      $('#editar_destino').modal('show');
      $('#id_destino_editar').val(d[0]);
      $('#nombre_destino_editar').val(d[1]);
      $('#nombre_destino2_editar').val(d[1]);
      $('#dato_destino_editar').val(d[2]);
      $('#precio_normal_editar').val(d[3]);
      $('#precio_medio_editar').val(d[4]);
    }
  //funcion para editar un permiso al usuario
    function editar_destino(opcion){
      var url="model/operacion/update/editar_destino_ruta.php"
      $.ajax({
        type: "POST",
        url:url,
        data: $("#frm_editar_destino").serialize(),
        beforeSend: function(){
          $('#respuesta_editar_destino').html("<div class='spinner-border'></div>");
        },
        success: function(datos){$('#respuesta_editar_destino').html(datos);}
      });
    }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_destinos_asignados').DataTable( {
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