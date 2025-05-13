<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
  //
?>
<div class="card">
  <div class="card-header"><h5>TALONARIOS ACTIVOS</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla">
      <thead>
        <tr>
          <th>#</th>
          <th>SERIE</th>
          <th>FOLIO INICIAL</th>
          <th>FOLIO FINAL</th>
          <th>TIPO</th>
          <th>ASIGNACION</th>
          <th>USUARIO</th>
          <th>TAQUILLA</th>
          <th>REGISTRO</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia = "SELECT * FROM talonario WHERE restantes=0 ";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia = retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Identifico el tipo de talonario
                    switch ($tabla['tipo']) {
                      case '1':
                          $tipo="PASAJEROS";
                      break;
                      case '2':
                          $tipo="PAQUETERIA";
                      break;
                    }
                  //Identifico el tipo de asignacion
                    switch ($tabla['uso']) {
                      case '1':
                        //Defino el tipo de asignacion
                          $asignacion="A TAQUILLA";
                        //Busco al usuario asignaddo
                          $usuario=busca_existencia("SELECT CONCAT(nombre,' ',apellido) as exist from usuarios where clave='".$tabla['usuario']."';");
                        //Busco la taquilla asignada
                          $taquilla=busca_existencia("SELECT destino as exist from destinos where id=".$tabla['taquilla'].";");
                        //
                      break;
                      case '2':
                        //Defino el tipo de asignacion
                          $asignacion="A OPERADOR";
                        //Busco al usuario asignaddo
                          $usuario=busca_existencia("SELECT CONCAT(nombre,' ',apellido) as exist from operadores where clave='".$tabla['usuario']."';");
                        //Busco la taquilla asignada
                          $taquilla="VENTA A BORDO";
                        //
                      break;
                    }
                  //Realizo la impresion de los datos
                    echo "
                      <tr>
                        <td>".$tabla['id']."</td>
                        <td>".$tabla['serie']."</td>
                        <td>".$tabla['inicial']."</td>
                        <td>".$tabla['final']."</td>
                        <td>$tipo</td>
                        <td>$asignacion</td>
                        <td>$usuario</td>
                        <td>$taquilla</td>
                        <td>".$tabla['f_registro']."</td>
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
    $(document).ready(function() {
      var table = $('#tabla').DataTable({
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType": "full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [10, 25, 50, -1],
          ['10 Filas', '25 Filas', '50 Filas', 'Mostrar todo']
        ],
        buttons: {
          buttons: [
            {
              extend: 'pageLength',
              text: 'CANTIDAD'
            },
            {
              extend: 'excelHtml5',
              text: 'DESCARGAR EXCEL',
              filename: 'CORRIDAS RESGITRADAS',
              orientation: 'landscape'
            },
            {
              extend: 'print',
              text: 'IMPRIMIR'
            },
            {
              extend: 'copy',
              text: 'COPIAR'
            },
            {
              extend: 'colvis',
              text: 'COLUMNAS'
            },
          ],
        },
      });
      table.on('responsive-resize', function(e, datatable, columns) {
        var count = columns.reduce(function(a, b) {
          return b === false ? a + 1 : a;
        }, 0);
        console.log(count + ' column(s) are hidden');
      });
    });
  //
</script>