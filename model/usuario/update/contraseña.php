<?php
  session_start();
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  include '../../connection/kronh-os.sql.db.php';
  $id=htmlspecialchars($_POST['identi'],ENT_QUOTES);
  $pass=md5(htmlspecialchars($_POST['pass'],ENT_QUOTES));

  $hoy = getdate();
    $ano=$hoy['year'];
    $month=$hoy['mon'];
    $day=$hoy['mday'];
  $f_registro="$ano-$month-$day";
    $horas=$hoy['hours'];
    $minutos=$hoy['minutes'];
  $h_registro="$horas:$minutos";
  $usuario=$_SESSION['kronh-os']['id'];
    $sql=$conn->prepare("UPDATE usuarios SET password='$pass' WHERE id=$id");
    $res=$sql->execute();
    if($res === TRUE){
      echo "
        <div class='alert alert-success fade show'>
          <button class='close' data-dismiss='alert'>×</button>
          <strong>Actualizado!</strong>
          Password actualizada
        </div>
      ";
    }else{
      echo "
        <div class='alert alert-danger fade show'>
          <button class='close' data-dismiss='alert'>×</button>
          <strong>Error!</strong>
          Revisa los datos eh intenta de nuevo 
        </div>
      ";
    }
?>