<?php
  session_start();
  include '../../../connection/kronh-os.sql.db.php';
  $id=htmlspecialchars($_POST['id'],ENT_QUOTES);
  $fecha=htmlspecialchars($_POST['fecha'],ENT_QUOTES);
  $folio=htmlspecialchars($_POST['folio'],ENT_QUOTES);
  $ticket=htmlspecialchars($_POST['ticket'],ENT_QUOTES);
  $unidad=htmlspecialchars($_POST['unidad'],ENT_QUOTES);
  $operador=htmlspecialchars($_POST['operador'],ENT_QUOTES);
  $kilometraje=htmlspecialchars($_POST['kilometraje'],ENT_QUOTES);
  $diesel=htmlspecialchars($_POST['diesel'],ENT_QUOTES);
  $adblue=htmlspecialchars($_POST['adblue'],ENT_QUOTES);
  $dvd="UPDATE cargas_diesel SET id_unidad=$unidad, id_operador=$operador,ticket='$ticket', folio='$folio', fecha='$fecha', litros=$diesel, kilometros=$kilometraje, litros=$diesel, adblue=$adblue WHERE id=$id;";
  $sql=$conn->prepare($dvd);
  $res=$sql->execute();
  if($res === TRUE){
    echo "
      <div class='alert alert-success fade show'>
        <button class='close' data-dismiss='alert'>×</button>
        Datos actualizados
      </div>
    ";
  }else{
    echo "
      <div class='alert alert-danger fade show'>
        <button class='close' data-dismiss='alert'>×</button>
        <strong>Error!</strong>
      </div>
    ";
  }
?>