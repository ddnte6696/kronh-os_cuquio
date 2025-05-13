<?php
  // Se revisa si la sesión está iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';

  // Obtengo los datos mandados por el formulario
  $numero = campo_limpiado($_POST['id_asiento'], 0, 0);
  $nombre = campo_limpiado($_POST['nombre'], 0, 1);
  $tipo = campo_limpiado($_POST['tipo'], 2, 0);

  // Tomo los datos de la sesión necesarios
  $precio = campo_limpiado($_SESSION['oyg_vb']['precio_destino'], 2, 0);
  $arrar_boletos = $_SESSION['oyg_vb']['boletos'];

  // Defino una variable de búsqueda en 0
  $encontrado = null;

  // Verifico si el nombre no está vacío
  if (!empty($nombre)) {
    // Identifico el tipo de boleto
    if ($tipo != 'ADULTO') {
      $precio *= 0.60;
    }

    // Creo un concatenado con los datos
    $nuevo = "$numero||$nombre||$tipo||$precio";

    // Identifico si ya hay boletos cargados en la variable de sesión
    if (!empty($arrar_boletos)) {
      // Separo los registros por su primer identificador
      $filas = explode('$$', $arrar_boletos);

      // Itero un ciclo para revisar el arreglo
      foreach ($filas as $fila) {
        // Separo los datos de cada fila en columnas
        $columnas = explode('||', $fila);

        // Si el número de asiento ya se encuentra registrado
        if ($columnas[0] == $numero && $columnas[0] != 'DE PIE') {
          // Se marca que ya está ocupado
          $encontrado = 1;
          break;
        }
      }

      // Si no se encontró ese mismo asiento nuevamente
      if (is_null($encontrado)) {
        // Ingreso el valor del boleto a la variable de sesión
        $arrar_boletos .= "$$" . $nuevo;
      }
    } else {
      // Ingreso el valor del boleto a la variable de sesión
      $arrar_boletos = $nuevo;
    }

    // Actualizo la variable de sesión
    $_SESSION['oyg_vb']['boletos'] = $arrar_boletos;

    // Despliego la tabla de pasajeros
    echo "
      <script>
        $('#pasajero').modal('hide');
        muestra_pasajeros('$arrar_boletos');
      </script>
    ";
  } else {
    // Mando una alerta y despliego la tabla de pasajeros
    echo "
      <script>
        alert('ATENCION!, NO SE INGRESO EL NOMBRE DEL PASAJERO');
        muestra_pasajeros();
      </script>
    ";
  }
?>