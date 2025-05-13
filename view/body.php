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
    include_once A_MODEL.'usuario/modal/cambiar_taquilla.php';
    include_once A_VIEW.'footer.php';
  //
?>
<script>
  //Funcion para cerrar el expediente
    function limpiar(){ $("#expediente").html(""); }
  //funcion para llamada del modal
    function cambiar_taquilla_modal(datos){
      $('#cambiar_taquilla').modal('show');
    }
  //Funcion para asignacion de la corrida
    function cambiar_taquilla(){
    $.ajax({
      type: "POST",
      url: "model/usuario/update/cambiar_taquilla.php",
      data: $("#frm_cambiar_taquilla").serialize(),
      beforeSend: function(){
      $("#respuesta_cambiar_taquilla").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#respuesta_cambiar_taquilla").html(data);
      },
    });
    return false;
  }
  //Funcion para regenerar las corridas
    //Funcion para asignacion de la corrida
    function regenera_corridas(){
    $.ajax({
      type: "POST",
      url: "model/operacion/update/regenera_corridas.php",
      beforeSend: function(){
      $("#mensaje").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#mensaje").html(data);
      },
    });
    return false;
  }
  //
</script>