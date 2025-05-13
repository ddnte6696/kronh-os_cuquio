<!--Operadores>>Expedientes-->
<?php 
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
  $id=campo_limpiado($_POST['form_act'],2,0);
  $sentencia="
    SELECT
      a.id,
      a.nombre,
      a.apellido,
      a.clave,
      a.f_ingreso,
      a.f_reingreso,
      a.visual,
      a.telefono,
      a.direccion,
      a.photo,
      a.num_siniestros,
      a.cuenta_siniestralidad,
      a.recuperacion_infraccion,
      a.cobro_infraccion,
      b.empresa,
      d.division,
      b.id as id_empresa,
      d.id  as id_division
    FROM operadores as a
      LEFT JOIN empresas as b on a.empresa=b.id
      LEFT JOIN division as d on a.division=d.id 
    WHERE a.id=$id
  ";
  //Ejecuto la sentencia y almaceno lo obtenido en una variable
    $resultado_sentencia=retorna_datos_sistema($sentencia);
  //Identifico si el reultado no es vacio
    if ($resultado_sentencia['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $resultado = $resultado_sentencia['data'];
      // Recorrer los datos y llenar las filas
        foreach ($resultado as $tabla) {
          //Almaceno en variables los datos obtenidos
            $id=$tabla['id'];
            $nombre=$tabla['nombre'];
            $apellido=$tabla['apellido'];
            $clave=$tabla['clave'];
            $f_ingreso=$tabla['f_ingreso'];
            $f_reingreso=$tabla['f_reingreso'];
            $visual=$tabla['visual'];
            $telefono=$tabla['telefono'];
            $direccion=$tabla['direccion'];
            if ($tabla['photo']==null) {
              $imagen='common.png';
            }else{
              $imagen=$tabla['photo'];
            }
            $empresa=$tabla['empresa'];
            $division=$tabla['division'];
            $id_empresa=$tabla['id_empresa'];
            $id_division=$tabla['id_division'];
            $num_siniestros=$tabla['num_siniestros'];
            $cuenta_siniestralidad=$tabla['cuenta_siniestralidad'];
            $cobro_infraccion=$tabla['cobro_infraccion'];
            $recuperacion_infraccion=$tabla['recuperacion_infraccion'];
          //
        }
      //
    }
  //Creeo unas variables para traspaso de datos
    $dato_1=$_POST['form_act'];
    $dato_2=$_POST['form_act']."||$clave - $nombre $apellido";
  //
?>
<script type="text/javascript">

  function pantalla_informacion(){
    var clave_usuario=$("#clave_usuario").val();
    var url="model/historia/exp/info_principal.php";
    $.ajax({
      type: "POST",
      url:url,
      data:{ clave_usuario:clave_usuario },
      beforeSend: function(){
        $("#info_prncipal").html("<div class='spinner-border'></div>");
        aparece_tabla();
      },
      success: function(datos){ $('#info_prncipal').html(datos); }
    });
  }

  function formulario_informacion(){
    var clave_usuario=$("#clave_usuario").val();
    var url="model/historia/forms/info_principal.php";
    $.ajax({
      type: "POST",
      url:url,
      data:{ clave_usuario:clave_usuario },
      beforeSend: function(){
        $("#info_prncipal").html("<div class='spinner-border'></div>");
      },
      success: function(datos){ $('#info_prncipal').html(datos); }
    });
  }

  function despliega_pantalla(){
    var opcion=$("#pantalla").val();
    var clave_usuario=$("#clave_usuario").val();
    var url="model/historia/exp/"+opcion+".php";
    $.ajax({
      type: "POST",
      url:url,
      data:{ clave_usuario:clave_usuario },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        aparece_tabla();
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }

  function aparece_tabla(){
    var opcion=$("#pantalla").val();
    var clave_usuario=$("#clave_usuario").val();
    var url="model/historia/queries/"+opcion+".php";
    $.ajax({
      type: "POST",
      url:url,
      data:{ clave_usuario:clave_usuario },
      beforeSend: function(){
        $("#vista_interno").html("<div class='spinner-border'></div>");
      },
      success: function(datos){ $('#vista_interno').html(datos); }
    });
  }

  function aparece_form(){
    var opcion=$("#pantalla").val();
    var clave_usuario=$("#clave_usuario").val();
    var url="model/historia/forms/"+opcion+".php";
    $.ajax({
      type: "POST",
      url:url,
      data:{ clave_usuario:clave_usuario },
      beforeSend: function(){
        $("#vista_interno").html("<div class='spinner-border'></div>");
      },
      success: function(datos){ $('#vista_interno').html(datos); }
    });
  }

</script>
<div class="card">
  <div class="card-header"><h5>EXPEDIENTE N° <?php echo $id ?></h5></div>
  <input type="submit" value="CERRAR EXPEDIENTE" class="btn btn-sm btn-danger btn-block" onclick="limpiar();">
  <div class="card-body">
    <div class="row">
      <!--DATOS GENERALES DEL OPERADOR-->
        <div class="col-lg-4">
          <div class="card text-center">
            <div class="card-body">
              <div id="info">
                <img src='<?php echo "img/operador/$imagen"; ?>' class='card-img-top' style='width: 250px'>
                <table class="table table-sm table-hover card-text">
                  <tbody>
                    <tr>
                      <th>CLAVE</th>
                      <td><?php echo $clave; ?></td>
                    </tr><tr>
                      <th>NOMBRE</th>
                      <td><?php echo "$nombre $apellido"; ?></td>
                    </tr><tr>
                      <th>TELEFONO</th>
                      <td><?php echo $telefono; ?></td>
                    </tr><tr>
                      <th>DIRECCION</th>
                      <td><?php echo $direccion; ?></td>
                    </tr><tr>
                      <th>EMPRESA</th>
                      <td><?php echo $empresa; ?></td>
                    </tr><tr>
                      <th>DIVISION</th>
                      <td><?php echo $division; ?></td>
                    </tr>
                  </tbody>
                </table>
                <a class="btn btn-primary" onclick="form_actualizar_informacion('<?php echo $dato_1 ?>')"><strong>ACTUALIZAR INFORMACION</strong></a>
                <?php if ($visual!=2) { ?>
                  <a class="btn btn-danger" onclick="modal_baja_operador('<?php echo $dato_2 ?>')"><strong>DA DE BAJA</strong></a>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <!--INFORMACION DEL EXPEDIENTE-->
        <div class="col-lg-8">
          <div class="card">
          <div class="card-header">
            <div class="input-group mb-3 input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>PANTALLA</strong></span>
              </div>
              <select name="referencia" id="referencia" class="custom-select" required="" onchange="despliega_pantalla()">
                <?php
                  echo "
                    <option value='".campo_limpiado('DOCUMENTOS',2,0)."'>DOCUMENTOS</option>
                    <option value='".campo_limpiado('INFRACCIONES',2,0)."'>INFRACCIONES</option>
                    <option value='".campo_limpiado('SINIESTRALIDAD',2,0)."'>SINIESTRALIDAD</option>
                  ";
                ?>
              </select>
            </div>
          </div>
          <div class="card-body">
            <div id="pantalla"></div>
          </div>
        </div>
        </div>
      <!-- -->
    </div>
  </div>
</div>
<script>
  //script para datatables
    $(document).ready( function () {
      var table = $('#tabla_docs').DataTable( {
        responsive: true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
          { extend: 'excel', text:'Descargar Excel' },
          { extend: 'print', text:'Imprimir' },{ extend: 'copy', text:'Copiar' },
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
    $(document).ready( function () {
      var table = $('#tabla_bajas').DataTable( {
        responsive: true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
          { extend: 'excel', text:'Descargar Excel' },
          { extend: 'print', text:'Imprimir' },{ extend: 'copy', text:'Copiar' },
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
  //script para vista del archivo cargado
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  //funcion para llamada del modal
    function modal_baja_operador(datos){
      var info=datos.split('||');
      $('#baja_operador').modal('show');
      $('#id_baja').val(info[0]);
      $('#nombre_baja').val(info[1]);
    }
  //actualizacion de datos del operador
    $(function actualizar_datos(){
      $("#frm_usuario").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("frm_usuario"));
        formData.append("dato", "valor");
        //formData.append(f.attr("name"), $(this)[0].files[0]);
        $.ajax({
          url: "model/update/info_op.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $("#response").html("<div class='spinner-border'></div>");
          },
        })
        .done(function(res){
          $("#response").html(res);
          
        });
      });
    });
  //carga de documentos
    $(function cargar_archivo(){
      $("#frm_doc").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("frm_doc"));
        formData.append("dato", "valor");
        //formData.append(f.attr("name"), $(this)[0].files[0]);
        $.ajax({
          url: "model/insert/archivo_local.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $("#response").html("<div class='spinner-border'></div>");
          },
        })
        .done(function(res){
          $("#response").html(res);
          
        });
      });
    });
  //script para vista del archivo cargado
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  //
</script>
