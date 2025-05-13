<script type="text/javascript">
  //Formulario para registr de nuevos productos
    function registrar_material(opcion){
      var url="model/almacen/forms/registrar_material.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{ id:opcion },
        beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
          $('#expediente').html("");
        },
        success: function(datos){ $('#muestra').html(datos); }
      });
    }
  //Tabla de productos registrados
    function materiales(opcion){
      var url="model/almacen/queries/almacen.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{ id:opcion },
        beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
          $('#expediente').html("");
        },
        success: function(datos){ $('#muestra').html(datos); }
      });
    }
  //Formulario para visualizar los movimientos realizados
    function movimientos(opcion){
      var url="model/almacen/forms/movimientos.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{ id:opcion },
        beforeSend: function(){
          $("#muestra").html("<div class='spinner-border'></div>");
          $('#expediente').html("");
        },
        success: function(datos){ $('#muestra').html(datos); }
      });
    }
  //
  window.onload(registrar_material());
</script>
<div class="card text-center">
  <div class="card-header">
    <a class="btn btn-sm text-sm btn-primary" onclick="registrar_material();">REGISTRAR PRODUCTO</a>
    <a class="btn btn-sm text-sm btn-primary" onclick="materiales();">INVENTARIO</a>
    <a class="btn btn-sm text-sm btn-primary" onclick="movimientos();">MOVIMIENTOS</a>
  </div>
  <div class="card-body">
    <div id="muestra"></div>
  </div>
</div>
<?php 
  include_once A_MODEL.'almacen/modal/copiar_producto.php';
  include_once A_MODEL.'almacen/modal/reducir_existencia.php';
  include_once A_MODEL.'almacen/modal/eliminar_movimiento.php';
?>
<script type="text/javascript">
  //funcion para llamada del modal
    function copiar_producto_modal(datos){
      //Separo los datos obtenidos por el formulario
        var datos_producto=datos.split("||");
      //Se almacenan los valores en variables
        var id_producto=datos_producto[0];
        var producto=datos_producto[1];
        var cantidad=datos_producto[2];
        var precio=datos_producto[3];
        var proveedor=datos_producto[4];
        var observacion=datos_producto[5];
        var ubicacion=datos_producto[6];
      //Se muestra el modal
        $('#copiar_producto').modal('show');
      //Se llenan los campos del modal con los datos correspondientes
        $('#copiar_datos_producto').val(id_producto);
        $('#copiar_nombre_producto').val(producto);
        $('#copiar_proveedor').val(proveedor);
        $('#copiar_precio').val(precio);
        $('#copiar_ubicacion').val(ubicacion);
        $('#copiar_observacion').val(observacion);
      //Defino valores para algunas casillas
        $('#copiar_nota').val('');
        $('#copiar_cantidad').val('');
      //
    }
  //Funcion para aumentar la existencia del producto
    function copiar_producto(){
      $.ajax({
        type: "POST",
        url: "model/almacen/update/copiar_producto.php",
        data: $("#frm_copiar_producto").serialize(),
        beforeSend: function(){
          $("#respuesta_copiar_producto").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_copiar_producto").html(data);
        },
      });
      return false;
    }
  //funcion para llamada del modal
    function reducir_existencia_modal(datos){
      //Separo los datos obtenidos por el formulario
        var datos_producto=datos.split("||");
      //Se almacenan los valores en variables
        var id_producto=datos_producto[0];
        var producto=datos_producto[1];
        var cantidad=datos_producto[2];
        var precio=datos_producto[3];
        var proveedor=datos_producto[4];
        var observacion=datos_producto[5];
        var ubicacion=datos_producto[6];
      //Se muestra el modal
        $('#reducir_existencia').modal('show');
      //Se llenan los campos del modal con los datos correspondientes
        $('#reducir_datos_producto').val(datos);
        $('#reducir_id_producto').val(id_producto);
        $('#reducir_producto').val(producto);
        $('#reducir_cantidad_existencia').val(cantidad);
        $('#reducir_precio').val(precio);
      //Defino valores para algunas casillas
        $('#reducir_f_salida').val('');
        $('#reducir_nota').val('');
        $('#reducir_cantidad').val('');
        $('#reducir_destino').val('');
        $('#reducir_observacion').val('');
    }
  //Funcion para reducir la existencia del producto
    function reducir_existencia(){
      $.ajax({
        type: "POST",
        url: "model/almacen/update/reducir_existencia.php",
        data: $("#frm_reducir_existencia").serialize(),
        beforeSend: function(){
          $("#respuesta_reducir_existencia").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_reducir_existencia").html(data);
        },
      });
      return false;
    }
  //funcion para llamada del modal
    function eliminar_movimiento_modal(datos){
      //Separo los datos obtenidos por el formulario
        var datos_producto=datos.split("||");
      //Se almacenan los valores en variables
        var datos=datos_producto[0];
        var movimiento=datos_producto[1];
        var producto=datos_producto[2];
        var proveedor=datos_producto[3];
        var cantidad=datos_producto[4];
        var precio=datos_producto[5];
        var fecha=datos_producto[6];
      //Se muestra el modal
        $('#eliminar_movimiento').modal('show');
      //Se llenan los campos del modal con los datos correspondientes
        $('#eliminar_movimiento_datos').val(datos);
        $('#eliminar_movimiento_fecha').val(fecha);
        $('#eliminar_movimiento_tipo').val(movimiento);
        $('#eliminar_movimiento_producto').val(producto);
        $('#eliminar_movimiento_proveedor').val(proveedor);
        $('#eliminar_movimiento_existencia').val(cantidad);
        $('#eliminar_movimiento_precio').val(precio);
      //Defino valores para algunas casillas
        $('#eliminar_movimiento_observacion').val('');
      //
    }
  //Funcion para reducir la existencia del producto
    function eliminar_movimiento(){
      $.ajax({
        type: "POST",
        url: "model/almacen/update/eliminar_movimiento.php",
        data: $("#frm_eliminar_movimiento").serialize(),
        beforeSend: function(){
          $("#respuesta_eliminar_movimiento").html("<div class='spinner-border'></div>");
        },
        success: function(data){
          $("#respuesta_eliminar_movimiento").html(data);
        },
      });
      return false;
    }
  //
</script>
