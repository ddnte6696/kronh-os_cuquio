<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se incluyen los archivos de conexión
  include_once A_CONNECTION;
?>
<div class="card">
  <div class="card-header">
    <h5>USUARIOS ACTIVOS</h5>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla">
      <thead>
        <tr>
          <th>Clave</th>
          <th>Telefono</th>
          <th>Nombre</th>
          <th>Puesto</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sentencia="
            SELECT
             a.id,a.clave,a.nombre,a.password,a.apellido,a.telefono,a.f_ingreso,
             b.puesto
            FROM usuarios as a
              LEFT JOIN puestos as b on a.puesto=b.id
            where a.visual=1 and a.id<>1
          ";
          try {
            $query=$conn->prepare($sentencia);
            $query->execute();
            while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
              $id=$tabla['id'];
              $clave=$tabla['clave'];
              $password=$tabla['password'];
              $telefono=$tabla['telefono'];
              $nombre=$tabla['nombre'];
              $apellido=$tabla['apellido'];
              $puesto=$tabla['puesto'];
              $ingreso=$tabla['f_ingreso'];
              $dato="'".campo_limpiado($id,1,0)."'";
              echo "
                <tr>
                  <td>$clave</td>
                  <td>$telefono</td>
                  <td>$nombre $apellido</td>
                  <td>$puesto</td>
                  <td>
                    <a onclick=\"editar($dato)\" class=\"btn btn-primary text-light\" data-toggle=\"tooltip\" title=\"Editar este colaborador\"><i class='fas fa-user-edit'  style='font-size:24px'></i></a>
                    <a onclick=\"baja($dato)\" class=\"btn btn-danger text-light\"><i class='fas fa-user-slash'  style='font-size:24px'></i></a>
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
  //Funcion para marcar la baja del usuario
    function baja(opcion){
      $.ajax({
        type: "POST", 
        url: "model/usuario/update/baja.php",
        data: {form_act: opcion},
        beforeSend: function(){
          $("#respuesta_control_usuario").html("<div class='spinner-border text-center'></div>");
        },
        success: function(respuesta){
          $("#respuesta_control_usuario").html(respuesta);
        }
      });
      return false;
    }
  //Funcion para editar los datos del usuario
    function editar(opcion){
      $.ajax({
        type: "POST", 
        url: "model/usuarios/queries/expediente.php",
        data: {form_act: opcion},
        beforeSend: function(){
          $("#respuesta_control_usuario").html("<div class='spinner-border text-center'></div>");
        },
        success: function(respuesta){
          $("#respuesta_control_usuario").html(respuesta);
        }
      });
      return false;
    }
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