<?php 
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
  $inicio=htmlspecialchars($_POST['inicio'],ENT_QUOTES);
  $fin=htmlspecialchars($_POST['fin'],ENT_QUOTES);
?>
<div class="card">
  <div class="card-header"><h5>UNIDADES FUERA DE RUTA</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-sm" id="tabla">
      <thead>
        <tr>
          <th>#</th>
          <th>Numero</th>
          <th>Fecha y hora de inicio</th>
          <th>Fecha y hora de fin</th>
          <th>Motivo</th>
        </tr>
      </thead>
      <tbody>
        <?php
            $query=$conn->prepare("
              SELECT 
                a.id,
                a.fecha_inicio,
                a.hora_inicio,
                a.fecha_fin,
                a.hora_fin,
                a.motivo,

                b.numero,

                c.empresa,
                d.division
              FROM deshabilitacion as a
                LEFT JOIN unidades as b on b.id=a.id_unidad
                LEFT JOIN empresas as c on c.id=b.empresa
                LEFT JOIN division as d ON d.id=b.division
              WHERE a.visual=0 AND (a.fecha_inicio between '$inicio' and '$fin')
            ");
            $query->execute();
            while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
              $id=$tabla['id'];
              $empresa=$tabla['empresa'];
              $division=$tabla['division'];
              $numero=$tabla['numero'];
              $fecha=transforma_fecha($tabla['fecha_inicio']);
              $hora=$tabla['hora_inicio'];
              $fecha_fin=transforma_fecha($tabla['fecha_fin']);
              $hora_fin=$tabla['hora_fin'];
              $motivo=$tabla['motivo'];
            echo 
              "<tr>
                <td id='title'>$id</td>
                <td>$numero</td>
                <td>$fecha a las $hora</td>
                <td>$fecha_fin a las $hora_fin</td>
                <td>$motivo</td>
              </tr>"
            ;
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script>
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
          buttons:[
            { extend: 'excel', text:'DESCARGAR EXCEL' },
            { extend: 'print', text:'IMPRIMIR' },{ extend: 'copy', text:'COPIAR' },
          ],
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