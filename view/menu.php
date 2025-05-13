<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Asigno los datos de sesion a variables especificas
    $id=campo_limpiado($_SESSION[UBI]['id'],2,0);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0);
    $correo=campo_limpiado($_SESSION[UBI]['correo'],2,0);
    $nombre=campo_limpiado($_SESSION[UBI]['nombre'],2,0);
    $apellido=campo_limpiado($_SESSION[UBI]['apellido'],2,0);
    $permisos=campo_limpiado($_SESSION[UBI]['permisos'],2,0);
    $puesto=campo_limpiado($_SESSION[UBI]['puesto'],2,0);
    $empresa=campo_limpiado($_SESSION[UBI]['empresa'],2,0);
  //Divido los menu's por su separador
    $menus=explode("!!", $permisos);
  //Defino character para utilizar en una condicional
    $condicional="(";
  //
?>
<body>
  <div class="sticky-top">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark justify-content-center success">
      <a class="navbar-brand" href="index.php"><img src="img/logos/KRONHOS_ALTER.png" style="width: 200px"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav d-flex flex-wrap">
          <li class="nav-item"></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
              <?php 
                //Imprimo el nombre y el puesto de la persona
                echo "
                  <strong>$nombre $apellido</strong>
                  <br>
                  <small>$puesto</small>
                "; 
              ?>
            </a>
            <div class="dropdown-menu">

              <a class="dropdown-item" href="view/recargar_datos.php">
                <strong>
                  <i class="fas fa-sync" ></i> Recargar datos
                </strong>
              </a>

              <a class="dropdown-item" href="view/logout.php">
                <strong>
                  <i class="fas fa-sign-out-alt" ></i> Cerrar sesión
                </strong>
              </a>
            </div>
          </li>
          <?php
            //Verifico si los campos de permisos no estan vacios
              if ($permisos!=Null) {
                //Si los campos de menu son mayores a uno
                  if (count($menus)>1) {
                    for ($i=0; $i < count($menus) ; $i++) {
                      //Creo el condicional del if para obtener los nombres de los campos
                        $condicional=$condicional."id=".$menus[$i]." or ";
                      //
                    }
                    //Le extraigo el ultimo or que esta de mas
                      $condicional=substr($condicional, 0, -4);
                    //Le agrego un parentesis de cierre
                      $condicional=$condicional.")";
                    //Si no es mayor de uno
                  }else{
                    //Creo un condicional sencillo
                      $condicional="(id=".$permisos.")";
                    //
                  }
                //Creo la sentencia que me va a obtener los nombres de los grupos
                $sentencia_grupo="SELECT nombre_grupo FROM menu where $condicional group by nombre_grupo";
                //Realizo la busqueda del puesto
                  $resultado_busqueda_grupo = retorna_datos_sistema($sentencia_grupo);
                //Si la cantidad de filas obtenidas es mayor a 0
                  if ($resultado_busqueda_grupo['rowCount'] > 0) {
                    //Almaceno los datos obtenidos
                      $datos_busqueda_grupo = $resultado_busqueda_grupo['data'];
                    //Mientras el resultado no este vacio
                      if (!empty($datos_busqueda_grupo)) {
                        // Recorrer los datos y llenar las filas
                          foreach ($datos_busqueda_grupo as $fila_busqueda_grupo) {
                            //Extraigo el nombre del grupo
                              $nombre_grupo=$fila_busqueda_grupo['nombre_grupo'];
                            //Comienzo a definir el encabezado del grupo ?>
                              <li class='nav-item dropdown'>
                                <a class='nav-link btn btn-outline text-light dropdown-toggle' href='#' id='navbardrop' data-toggle='dropdown'>
                                  <strong><?php echo $nombre_grupo?></strong>
                                </a>
                                <div class='dropdown-menu'><?php
                                  //Preparo la sentencia del menu especifico
                                    $sentencia_target="SELECT * FROM menu where $condicional and nombre_grupo='$nombre_grupo'";
                                  //Realizo la busqueda del puesto
                                    $resultado_busqueda_target = retorna_datos_sistema($sentencia_target);
                                  //Si la cantidad de filas obtenidas es mayor a 0
                                    if ($resultado_busqueda_target['rowCount'] > 0) {
                                      //Almaceno los datos obtenidos
                                        $datos_busqueda_target = $resultado_busqueda_target['data'];
                                      //Mientras el resultado no este vacio
                                        if (!empty($datos_busqueda_target)) {
                                          // Recorrer los datos y llenar las filas
                                            foreach ($datos_busqueda_target as $fila_busqueda_target) {
                                              //Extraigo el nombre del archivo
                                                $nombre_menu=$fila_busqueda_target['nombre_menu'];
                                              //Extraigo el nombre del archivo
                                                $id_menu=$fila_busqueda_target['id'];
                                                $descripcion=$fila_busqueda_target['descripcion'];
                                              //Creo el grupo en pantalla ?>
                                                <a class="dropdown-item" data-toggle="tooltip" title="<?php echo $descripcion ?>" onclick="menu('<?php echo campo_limpiado($id_menu,1,0) ?>')">
                                                  <strong>
                                                    <i class="fas fa-chevron-circle-right"></i>
                                                    <?php echo $nombre_menu ?>
                                                  </strong>
                                                </a><?php
                                              //
                                            }
                                          //
                                        }
                                      //
                                    }
                                  //?>
                                </div>
                              </li><?php
                            //
                          }
                        //
                      }
                    //
                  }
                //
              }
            //
          ?>
        </ul>
      </div>
    </nav>
  </div>
  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>