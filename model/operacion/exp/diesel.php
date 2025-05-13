<?php
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
  $id=$_POST['id'];
  $query=$conn->prepare("
    SELECT
      a.id_unidad as select_unidad,
      a.ticket,
      a.folio,
      a.fecha,
      a.litros,
      a.kilometros,
      a.id_operador as select_operador,
      a.adblue,
      b.id as id_unidad,
      b.numero,
      c.id as id_operador,
      c.nombre,
      c.apellidos
    from cargas_diesel as a
      left join unidades as b on a.id_unidad=b.id
      left join operadores as c on a.id_operador=c.id
    where a.id=$id
  ");
  $query->execute();
  while ($tabla=$query->fetch(PDO::FETCH_ASSOC)) {
    //datos de viaje
      $fecha=$tabla['fecha'];
      $folio=$tabla['folio'];
      $ticket=$tabla['ticket'];
      $select_unidad=$tabla['select_unidad'];
      $selct_numero=$tabla['numero'];
      $select_operador=$tabla['select_operador'];
      $select_nombre=$tabla['nombre'];
      $select_apellidos=$tabla['apellidos'];
      $diesel=$tabla['litros'];
      $kilometraje=$tabla['kilometros'];
      $adblue=$tabla['adblue'];
  }
?>
<input type="submit" class="btn btn-danger btn-block btn-sm" value="Cerrar expediente" onclick="limpiar();">
  <form enctype="multipart/form-data" class="form-horizontal text-center" method="post" name="frm_datos_diesel" id="frm_datos_diesel">
    <?php
      echo "
        <div hidden=''>
          <input type='text' name='id' value='$id'>
        </div>
        <table class='table table-bordered  table-sm'>
          <thead>
            <tr><th colspan=2>Actualizacion de datos</th></tr>
          </thead>
          <tbody>
            <tr>
              <th>Fecha de carga</th>
              <td>
                <input type='date' name='fecha' class='form-control'  value='$fecha' required=''>
              </td>
            </tr>
            <tr>
              <th>Folio</th>
              <td>
                <input type='text' name='folio' class='form-control'  value='$folio' required=''>
              </td>
            </tr>
            <tr>
              <th>Ticket</th>
              <td>
                <input type='text' name='ticket' class='form-control'  value='$ticket' required=''>
              </td>
            </tr>
            <tr>
              <th>Unidad</th>
              <td>
                <select  name='unidad' class='custom-select' required=''>
                  <option value='$select_unidad'>$selct_numero</option>";
                    $sql=$conn->prepare("
                      SELECT * FROM unidades where id<>$select_unidad
                      order by numero
                    ");
                    $sql->execute();
                    while($tabla=$sql->fetch(PDO::FETCH_ASSOC)){
                      $id=$tabla['id'];
                      $numero=$tabla['numero'];
                      echo "<option value='$id'>$numero</option>";
                    }
                echo "</select>
              </td>
            </tr>
            <tr>
              <th>Operador</th>
              <td>
                <select  name='operador' class='custom-select' required=''>
                  <option value='$select_operador'>$select_nombre $select_apellidos</option>";
                  $buscar="SELECT * FROM operadores order by nombre";
                  $sql=$conn->prepare($buscar);
                  $sql->execute();
                  while($tabla=$sql->fetch(PDO::FETCH_ASSOC)){
                    $id=$tabla['id'];
                    $nombre=$tabla['nombre'];
                    $apellido=$tabla['apellidos'];
                    echo "<option value='$id'>$nombre $apellido</option>";
                  }
                echo "</select>
              </td>
            </tr>
            <tr>
              <th>Kilometraje</th>
              <td>
                <input type='number' name='kilometraje' class='form-control'  value='$kilometraje' required=''>
              </td>
            </tr>
            <tr>
              <th>Diesel</th>
              <td>
                <input type='number' step='0.01' name='diesel' class='form-control'  value='$diesel' required=''>
              </td>
            </tr>
            <tr>
              <th>AdBlue</th>
              <td>
                <input type='number' step='0.01' name='adblue' class='form-control'  value='$adblue' required=''>
              </td>
            </tr>
          </tbody>
        </table>
      ";
    ?>
    <div class="form-actions">
      <input type="submit" value="Validar datos" class="btn btn-success btn-block btn-sm" onclick="actualizar_datos();">
    </div>
    <div id="respuesta_datos_diesel"></div>
  </form>
<script>
  $(function actualizar_datos(){
    $("#frm_datos_diesel").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_datos_diesel"));
      formData.append("dato", "valor");
      //formData.append(f.attr("name"), $(this)[0].files[0]);
      $.ajax({
        url: "model/operacion/update/datos_diesel.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
          $("#respuesta_datos_diesel").html("<div class='spinner-border'></div>");
        },
      })
      .done(function(res){
        $("#respuesta_datos_diesel").html(res);
        
      });
    });
  });
</script>