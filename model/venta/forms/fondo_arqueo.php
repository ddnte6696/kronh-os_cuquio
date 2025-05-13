<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
?>
<div class="card text-center">
  <div class="card-header"><h5>CALCULADORA DE EFECTIVO</h5></div>
  <div class="card-body">
    <input type="text" id="referencia" value="<?php echo campo_limpiado(referencia_temporal(),1); ?>" name="referencia" hidden>
    <div class="input-group mb-3 input-group-sm">
      <div class="input-group-prepend">
        <span class="input-group-text"><strong>TIPO</strong></span>
      </div>
      <select  name='tipo' id="tipo" class="custom-select" required="">
        <option value="<?php echo campo_limpiado('1',1)?>">FONDO DE CAJA</option>
        <option value="<?php echo campo_limpiado('2',1)?>">ARQUEO/RECOLECCION</option>
        <option value="<?php echo campo_limpiado('3',1)?>">CIERRE DE CAJA</option>
      </select>
    </div>
    <table class="table table-bordered table-sm table-striped">
      <tr><th>Cantidad</th><th>Denominacion</th><th>Subtotal</th></tr>
      <tr>
        <td><strong></strong> <input type="text" id="1000" name="1000" onchange="calcula_billetes();" value="0"></td>
        <th>$ 1,000</th>
        <td><strong>$</strong><input type="text" disabled="" id="t1000" value="0" name="t1000"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="500" name="500" onchange="calcula_billetes();" value="0"></td>
        <th>$ 500</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t500" value="0" name="t500"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="200" name="200" onchange="calcula_billetes();" value="0"></td>
        <th>$ 200</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t200" value="0" name="t200"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="100" name="100" onchange="calcula_billetes();" value="0"></td>
        <th>$ 100</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t100" value="0" name="t100"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="50" name="50" onchange="calcula_billetes();" value="0"></td>
        <th>$ 50</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t50" value="0" name="t50"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="20" name="20" onchange="calcula_billetes();" value="0"></td>
        <th>$ 20</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t20" value="0" name="t20"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="10" name="10" onchange="calcula_billetes();" value="0"></td>
        <th>$ 10</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t10" value="0" name="t10"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="5" name="5" onchange="calcula_billetes();" value="0"></td>
        <th>$ 5</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t5" value="0" name="t5"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="2" name="2" onchange="calcula_billetes();" value="0"></td>
        <th>$ 2</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t2" value="0" name="t2"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="1" name="1" onchange="calcula_billetes();" value="0"></td>
        <th>$ 1</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t1" value="0" name="t1"></td>
      </tr>
      <tr>
        <td><strong></strong> <input type="text" id="050" name="050" onchange="calcula_billetes();" value="0"></td>
        <th>$ 0.50</th>
        <td><strong>$</strong> <input type="text" disabled="" id="t050" value="0" name="t050"></td>
      </tr>
      <tr>
        <th colspan="2">TOTAL</th>
        <td><strong>$</strong> <input type="text" disabled="" id="total" value="0" name="total"></td>
      </tr>
    </table>
    <input type="submit" value="REGISTRAR" class="btn btn-block btn-primary" onclick="registrar_retiro();">
  </div>
  <div class="card-footer"><div id="respuesta_calculadora_billetes"></div></div>
</div>
<script>
  function calcula_billetes(){
    var t1000=($("#1000").val())*1000;
    $('#t1000').val(t1000);
    var t500=($("#500").val())*500;
    $('#t500').val(t500);
    var t200=($("#200").val())*200;
    $('#t200').val(t200);
    var t100=($("#100").val())*100;
    $('#t100').val(t100);
    var t50=($("#50").val())*50;
    $('#t50').val(t50);
    var t20=($("#20").val())*20;
    $('#t20').val(t20);
    var t10=($("#10").val())*10;
    $('#t10').val(t10);
    var t5=($("#5").val())*5;
    $('#t5').val(t5);
    var t2=($("#2").val())*2;
    $('#t2').val(t2);
    var t1=($("#1").val())*1;
    $('#t1').val(t1);
    var t050=($("#050").val())*0.50;
    $('#t050').val(t050);

    total=t1000+t500+t200+t100+t50+t20+t10+t5+t2+t1+t050;
    $('#total').val(total);
  }
  function registrar_retiro(){
    var referencia=$("#referencia").val();
    var tipo=$("#tipo").val();
    var t1000=$("#1000").val();
    var t500=$("#500").val();
    var t200=$("#200").val();
    var t100=$("#100").val();
    var t50=$("#50").val();
    var t20=$("#20").val();
    var t10=$("#10").val();
    var t5=$("#5").val();
    var t2=$("#2").val();
    var t1=$("#1").val();
    var t050=$("#050").val();
    var total=$("#total").val();
    var url= "model/venta/insert/billetes.php"
    $.ajax({
      type:"POST",
      url:url,
      data:{
        referencia:referencia,
        tipo:tipo,
        t1000:t1000,
        t500:t500,
        t200:t200,
        t100:t100,
        t50:t50,
        t20:t20,
        t10:t10,
        t5:t5,
        t2:t2,
        t1:t1,
        t050:t050,
        total:total
      },
      beforeSend: function(){$("#respuesta_calculadora_billetes").html("<div class='spinner-border'></div>");},
      success: function(data){$("#respuesta_calculadora_billetes").html(data);},
    });
    return false;
  }
</script>