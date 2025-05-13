<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Incluyo el menu a la pagina
    include_once A_VIEW.'menu.php';
  //Reviso el indicador de pantalla principal
    $pantalla=campo_limpiado($_SESSION[UBI]['pag_principal'],2,0);
  //Si tiene una pantalla de inicio definida
    if ($pantalla>0) {
      //Defino el numero del menu a llamar
        $numero="'".campo_limpiado($pantalla,1,0)."'";
      //mando a llamar el menu
        echo "
          <script>
            $(document).ready(menu($numero));
          </script>
        ";
      //
    }
  //

?>
<div id="mensaje"></div>
<div id="page-body"><div id="muestra"></div></div>
<?php
  //Incluyo el archivo para los datatables y el archivo para el pie de pagina
    include_once A_LIB.'datatables.php';
    include_once A_VIEW.'footer.php';
  //
?>
<script>
  //Funcion para cerrar el expediente
    function limpiar(){ $("#expediente").html(""); }
  //
</script>