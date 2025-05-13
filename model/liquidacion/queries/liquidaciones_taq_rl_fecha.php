<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave de quien realiza la liquidacion
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
    $fecha_trabajo=campo_limpiado($_POST['fecha'],0,0);
    $dato_taquilla=explode("||", campo_limpiado($_POST['dto_taquilla'],2,0));
    $id_origen=$dato_taquilla[0];
    $taquilla=$dato_taquilla[1];
  //
?>
<script type="text/javascript">
  
</script>
<div class="card">
  <div class="card-header">
    <h5><?php echo "CORTES DEL DIA ".campo_limpiado(transforma_fecha($fecha_trabajo,1," de "),0,1)." DE LA TAQUILLA $taquilla"; ?></h5>
  </div>
  <div class="card-text" id="mensajes"></div>
  <div class="card-body">
    <table class="table table-bordered table-sm text-sm table-striped" id="tabla_liquidaciones_registradas">
      <thead>
        <tr>
          <th>#</th>
          <th>VENDEDOR</th>
          <th>BOLETOS DE SISTEMA</th>
          <th>BOLETOS DE TALONARIO</th>
          <th>PAQUETERIAS DE SISTEMA</th>
          <th>PAQUETERIAS DE TALONARIO</th>
          <th>TOTAL</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia de busqueda
            $sentencia="SELECT * FROM corte where fecha='$fecha_trabajo' AND punto_venta='$taquilla'";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  $id=$tabla['id'];
                  $usuario=$tabla['usuario'];
                  $dto_usuario=busca_existencia("SELECT CONCAT(nombre,' ',apellido) AS exist FROM usuarios WHERE clave='$usuario'");
                  $importe_boletos_sistema=$tabla['importe_boletos_sistema'];
                  if ($importe_boletos_sistema<1) {
                    $importe_boletos_sistema="0.00";
                  }else{
                    $importe_boletos_sistema=number_format($importe_boletos_sistema,2);
                  }
                  $importe_boletos_talonario=$tabla['importe_boletos_talonario'];
                  if ($importe_boletos_talonario<1) {
                    $importe_boletos_talonario="0.00";
                  }else{
                    $importe_boletos_talonario=number_format($importe_boletos_talonario,2);
                  }
                  $importe_paquetes_sistema=$tabla['importe_paquetes_sistema'];
                  if ($importe_paquetes_sistema<1) {
                    $importe_paquetes_sistema="0.00";
                  }else{
                    $importe_paquetes_sistema=number_format($importe_paquetes_sistema,2);
                  }
                  $importe_paquetes_talonario=$tabla['importe_paquetes_talonario'];
                  if ($importe_paquetes_talonario<1) {
                    $importe_paquetes_talonario="0.00";
                  }else{
                    $importe_paquetes_talonario=number_format($importe_paquetes_talonario,2);
                  }
                  $total=number_format($tabla['importe_boletos_sistema']+$tabla['importe_boletos_talonario']+$tabla['importe_paquetes_sistema']+$tabla['importe_paquetes_talonario'],2);
                  $dato="'".campo_limpiado($id,1,0)."'";
                  echo "
                    <tr>
                      <td>$id</td>
                      <td>$dto_usuario</td>
                      <td>$importe_boletos_sistema</td>
                      <td>$importe_boletos_talonario</td>
                      <td>$importe_paquetes_sistema</td>
                      <td>$importe_paquetes_talonario</td>
                      <td>$total</td>
                      <td>
                        <a onclick=\"editar_liquidacion($dato)\" class=\"btn btn-primary text-light\" data-toggle=\"tooltip\" title='EDITAR'>
                          <i class='fas fa-edit'  style='font-size:24px'></i>
                        </a>
                        <a onclick=\"eliminar_liquidacion($dato)\" class=\"btn btn-danger text-light\" data-toggle=\"tooltip\" title='ELIMINAR'>
                          <i class='fas fa-trash'  style='font-size:24px'></i>
                        </a>
                      </td>
                    </tr>
                  ";
                }
              //
            }
          //
        ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    <div id="expediente"></div>
  </div>
</div>
<script type="text/javascript">
  //Funcion para cargar el formulario de liquidacion especifico
    function editar_liquidacion(dato){
      var url="model/liquidacion/forms/paginas_liq_taq_rl.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Funcion para cargar el formulario de liquidacion especifico
    function eliminar_liquidacion(dato){
      var url="model/liquidacion/update/eliminar_liquidacion_taquilla.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{dato:dato},
        beforeSend: function(){
          $("#expediente").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#expediente').html(datos);
        }
      });
    }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_liquidaciones_registradas').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 Filas', '25 Filas', '50 Filas', 'Mostrar todo' ]
        ],
        buttons:{
          buttons:[
            { extend: 'pageLength', text:'CANTIDAD' },
            { extend: 'excel', text:'DESCARGAR EXCEL' },
            { extend: 'print', text:'IMPRIMIR' },
            { extend: 'copy', text:'COPIAR' },
            { extend: 'colvis', text:'COLUMNAS' },
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