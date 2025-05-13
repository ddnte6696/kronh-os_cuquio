<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
?>
<table class="table table-bordered table-sm table-hover" id="tabla">
  <thead>
    <tr>
      <th>Puesto</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $orden_sql = "SELECT * FROM puestos where id>1";
      try {
        $query=$conn->prepare($orden_sql);
        $query->execute();
        while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
          $puesto=$tabla['puesto'];
          echo "
            <tr>
              <td>$puesto</td>
            </tr>
          ";
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