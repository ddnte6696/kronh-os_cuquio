<?php
    //Se revisa si la sesión esta iniciada y sino se inicia
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    //Se manda a llamar el archivo de configuración
        include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $_SESSION['ubi'] . '/lib/config.php';
    //
?>
<script type="text/javascript">
    //Función para registrar una nueva cuenta bancaria
        function tipo_asignacion() {
            //Defino y asigno las variables
                var puntero = $("#asignacion").val();
            //Indico la dirección del formulario que quiero llamar
                var url = "model/operacion/forms/asignacion_talonario.php"
            //inicio el traspaso de los datos
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        puntero: puntero
                    },
                    success: function(datos) {
                        $('#div_asignacion').html(datos);
                    }
                });
            //
        }
    //Mando a llamar la funcion al cargar el formularion
        $(document).ready(tipo_asignacion());
    //
</script>
<div class="card">
    <div class="card-header">
        <h3>REGISTRO DE TALONARIO</h3>
    </div>
    <div class="card-body">
        <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_agregar_talonario" id="frm_agregar_talonario">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>LETRA DE SERIE</strong></span>
                        </div>
                        <input type='text' name='serie' id='serie' class='form-control' placeholder="LETRA ASIGNADA A LA SERIE" required='' />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>FOLIO INICIAL</strong></span>
                        </div>
                        <input type='number' name='inicial' id='inicial' class='form-control' placeholder="PRIMER NUMERO DE BOLETO DEL TALONARIO" required='' />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>FOLIO FINAL</strong></span>
                        </div>
                        <input type='number' name='final' id='final' class='form-control' placeholder="ULTIMO NUMERO DE BOLETO DEL TALONARIO" required='' />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>TIPO DE TALONARIO</strong></span>
                        </div>
                        <select name='tipo' id="tipo" class="custom-select" style="color:black" onchange="busca_rutas();" required="">
                            <?php
                                echo "<option value='" . campo_limpiado('1', 1, 0) . "'>DE PASAJERO</option>";
                                echo "<option value='" . campo_limpiado('2', 1, 0) . "'>DE PAQUETERIA</option>";
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>ASIGNACION</strong></span>
                        </div>
                        <select name='asignacion' id="asignacion" class="custom-select" style="color:black" onchange="tipo_asignacion()" required="">
                            <?php
                                echo "<option value='" . campo_limpiado('1', 1, 0) . "'>A TAQUILLA</option>";
                                echo "<option value='" . campo_limpiado('2', 1, 0) . "'>A OPERADOR</option>";
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="div_asignacion"></div>
        </form>
    </div>
    <div class="card-footer">
        <div id="respuesta_agregar_talonario"></div>
        <input type="submit" value="REGISTRAR" class="btn btn-sm btn-success btn-block" onclick="registro_talonario();">
    </div>
</div>
<script>
    //Funcion de registro de talonario
        function registro_talonario() {
            $.ajax({
                type: "POST",
                url: "model/operacion/insertion/talonario.php",
                data: $("#frm_agregar_talonario").serialize(),
                beforeSend: function() {
                    $("#respuesta_agregar_talonario").html("<div class='spinner-border'></div>");
                },
                success: function(data) {
                    $("#respuesta_agregar_talonario").html(data);
                },
            });
            return false;
        }
    //
</script>