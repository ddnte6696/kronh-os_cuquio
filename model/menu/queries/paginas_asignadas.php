<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el enviado por el formulario
    $dato=campo_limpiado($_POST['dato'],2,0);
  //
?>
<div class="card">
  <div class="card-header"><h5>PAGINA ASIGNADA</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla_pagina_asignada">
      <thead>
        <tr>
          <th></th>
          <th>GRUPO</th>
          <th>MENÚ</th>
          <th>DESCRIPCIÓN</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Extraccion de los permios asignados
            $sentencia = "SELECT permisos FROM usuarios WHERE id=$dato";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia_permisos=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia_permisos['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado_permisos = $resultado_sentencia_permisos['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado_permisos as $tabla_permisos) {
                  //Creeo un arreglo de los permisos
                    $dvision_permisos=explode("||", $tabla_permisos['permisos']);
                  //Creeo un arreglo de los menus
                    $menus=explode("!!", $dvision_permisos[1]);
                  //Defino una variable de condicional nula
                    $condicional=Null;
                  //Defino la sentencia de los menus con la condicional
                    $sentencia="SELECT * FROM menu where id=".$dvision_permisos[0];
                  //Ejecuto la sentencia y almaceno lo obtenido en una variable
                    $resultado_sentencia=retorna_datos_sistema($sentencia);
                  //Identifico si el reultado no es vacio
                    if ($resultado_sentencia['rowCount'] > 0) {
                      //Almaceno los datos obtenidos
                        $resultado = $resultado_sentencia['data'];
                      // Recorrer los datos y llenar las filas
                        foreach ($resultado as $tabla) {
                          //Creeo un dato de asignacion especial
                            $dato="'".campo_limpiado($tabla['id'],1,0)."'";
                          //Impresion de los datos
                            echo "
                              <tr>
                                <td>
                                  <a onclick=\"retirar($dato)\" class=\"btn btn-danger text-light\"><i class=\"far fa-caret-square-left\" style=\"font-size:24px\"></i></a>
                                </td>
                                <td>".$tabla['nombre_grupo']."</td>
                                <td>".$tabla['nombre_menu']."</td>
                                <td>".$tabla['descripcion']."</td>
                              </tr>
                            ";
                          //
                        }
                      //
                    }
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
  //funcion para retirar un permiso al usuario
  function retirar(opcion){
    var usuario=$("#usr").val();
    var url="model/menu/update/retirar_pagina.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{
        id:opcion,
        usuario:usuario
      },
      success: function(datos){$('#respuesta').html(datos);}
    });
  }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_pagina_asignada').DataTable( {
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