<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo la clave del operador que esta logueado
    $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0);
  //Obtengo los datos enviados por el formulario
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
 // Importe y cantidad de paquetes vendidos para el operador
    $sentencia="
    	SELECT 
    		COUNT(id) AS cuenta_paquetes,
    		SUM(total) as suma_paquetes
    	FROM paquete WHERE usuario_vende='$usuario' and fecha='$fecha' and estado<>5
    ";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Creeo las variables del identificador
            $cuenta_paquetes=number_format($tabla['cuenta_paquetes'],2);
            $suma_paquetes=number_format($tabla['suma_paquetes'],2);
          //
        }
      //
    }
  //
?>
<table class="table table-bordered table-sm table-striped text-center" >
  <thead>
    <tr>
      <th>CANTIDAD</th>
      <th>TOTAL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <?php
        echo "
          <th>$cuenta_paquetes</th>
          <th>$suma_paquetes</th>
        ";
      ?>
    </tr>
  </tbody>
</table>
<div class="card">
  <div class="card-header"><h5>PAQUETES DE SISTEMA VENDIDOS</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-striped" id="tabla">
      <thead>
        <tr>
          <th>FOLIO</th>
          <th>ORIGEN</th>
          <th>DESTINO</th>
          <th>DESCRIPCION</th>
          <th>IMPORTE</th>
        </tr>
      </thead>
      <tbody>
        <?php
         // Importe y cantidad de paquetes vendidos para el operador
            $sentencia="SELECT * FROM paquete WHERE usuario_vende='$usuario' and fecha='$fecha' and estado<>5";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                  //Creeo las variables del identificador
                    $id=$tabla['id'];
                    $origen=$tabla['origen'];
                    $destino=$tabla['destino'];
                    $descripcion=$tabla['descripcion'];
                    $total=$tabla['total'];
                    echo "
                      <tr>
                        <td>$id</td>
                        <td>$origen</td>
                        <td>$destino</td>
                        <td>$descripcion</td>
                        <td>$total</td>
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