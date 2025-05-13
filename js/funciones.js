
//TRASPASO DE DATOS
  function menu(opcion){
    $.ajax({
      type: "POST", 
      url: "lib/controller.php",
      data: {opt_form: opcion},
      beforeSend: function(){
        $("#page-body").html("<div class='spinner-border'></div>");
      },
      success: function(respuesta){
        $("#page-body").html(respuesta);
      }
    });
    return false;
  }
  
  function solicitud(){
    $.ajax({
      type: "POST", 
      url: "model/insert/usr_s_ext.php",
      data: $("#recoverform").serialize(),
      beforeSend: function(){
        $("#response").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        if ($.trim(data)== 'ok') {
          location.reload(true); 
        }else{
          $("#response").html(data);
        }
      },
    });
    return false;
  }
//FUNCIONES DE INSERSION
//FUNCIONES DE ACTUALIZACION
  function reingreso_op(opcion){
    $.ajax({
      type: "POST", 
      url: "model/update/reingreso_op.php",
      data: {form_reingreso_op: opcion},
      beforeSend: function(){
        $("#response").html("<div class='spinner-border'></div>");
      },
      success: function(respuesta){
        $("#response").html(respuesta);
      },
    });
    return false;
  }
  function baja(){
    $.ajax({
      type: "POST",
      url: "model/update/baja_op.php",
      data: $("#frm_baja").serialize(),
      beforeSend: function(){
      $("#response2").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#response2").html(data);
      },
    });
    return false;
  }

  function liq_tal(){
    $.ajax({
      type: "POST",
      url: "model/update/liq_tal.php",
      data: $("#frm_liq").serialize(),
      beforeSend: function(){
      $("#response2").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#response2").html(data);
      },
    });
    return false;
  }

  function finalizar(){
    $.ajax({
      type: "POST",
      url: "model/update/fin_corrida.php",
      data: $("#frm_fin").serialize(),
      beforeSend: function(){
      $("#response3").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#response3").html(data);
      },
    });
    return false;
  }
  function aumentar(){
    $.ajax({
      type: "POST",
      url: "model/update/aumentar.php",
      data: $("#frm_mas").serialize(),
      beforeSend: function(){
      $("#rest").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#rest").html(data);
      },
    });
    return false;
  }
  function disminuir(){
    $.ajax({
      type: "POST",
      url: "model/update/disminuir.php",
      data: $("#frm_menos").serialize(),
      beforeSend: function(){
      $("#rest2").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        $("#rest2").html(data);
      },
    });
    return false;
  }
  

//