<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluye el archivo de conexión
  include_once A_CONNECTION;
?>
<div class="card">
  <div class="card-header"><h5>UNIDADES ACTIVAS</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-sm" id="tabla">
      <thead>
        <tr>
          <th>Numero</th>
          <th>Empresa</th>
          <th>Division</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sentencia="
            SELECT 
              a.id,
              a.numero,
              a.n_motor,
              a.niv,
              a.f_ingreso,
              c.empresa,
              d.division
            FROM unidades as a
              LEFT JOIN empresas as c on c.id=a.empresa
              LEFT JOIN division as d ON d.id=a.division
            WHERE a.visual=1
          ";
          try {
            $query=$conn->prepare($sentencia);
            $query->execute();
            while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
              $id=$tabla['id'];
              $numero=$tabla['numero'];
              $n_motor=$tabla['n_motor'];
              $niv=$tabla['niv'];
              $f_ingreso=$tabla['f_ingreso'];
              $empresa=$tabla['empresa'];
              $division=$tabla['division'];
              $dato="'".campo_limpiado($id,1,0)."||".$numero."'";
              echo "
                <tr>
                  <td>$numero</td>
                  <td>$empresa</td>
                  <td>$division</td>
                  <td>
                    <a onclick=\"inhabilitar_modal($dato)\" class=\"btn btn-warning\" data-toggle=\"tooltip\" title=\"Editar este colaborador\">
                      <i class='fas fa-ban'  style='font-size:24px'></i>
                    </a>
                    <a onclick=\"eliminar_modal($dato)\" class=\"btn btn-danger text-light\" data-toggle=\"tooltip\" title=\"Editar este colaborador\">
                      <i class='fas fa-trash'  style='font-size:24px'></i>
                    </a>
                    <a onclick=\"editar($dato)\" class=\"btn btn-primary text-light\" data-toggle=\"tooltip\" title=\"Editar este colaborador\">
                      <i class='fas fa-edit'  style='font-size:24px'></i>
                    </a>
                  </td>
                </tr>
              ";
            }
          } catch (PDOException $e) {
            //Almaceno el error en una variabLe
            $error=$e->getMessage();
            //Ubico el archivo desde donde se presenta el error
            $archivo=__FILE__;
            //Mando a escribir el mensaje
            escribir_log($error,$sentencia,$archivo);
            //Detengo el procedimiento
            die();
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