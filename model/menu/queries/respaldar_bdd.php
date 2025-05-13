<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  // Mando a llamar la conexion a la BD
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
  //Se define la ruta de la carpeta donde están los archivos
  $nombre_txt=A_DOCS."respaldo_".$dbname."-".$f_registro.".txt";
  $nombre="respaldo_".$dbname."-".$f_registro.".txt";
  // Se crea el archivo y se abre
  $archivo = fopen($nombre_txt,'a+');
  //Escribo en el archivo
  $linea="create database $dbname;";
  fwrite($archivo,$linea);
  fwrite($archivo,"\n");
  //sleep(1);
  //
  //Creo una sentencia para obtener las tablas en la BD
  $identifica_tablas="SHOW TABLES;";
  //Preparo la sentencia para ser ejecutada
  $query_identifica_tablas=$conn->prepare($identifica_tablas);
  //Ejecuto la sentencia
  $query_identifica_tablas->execute();
  //Obtengo lsa cantidad de filas obtenidas en la consulta
  $cuenta_tablas = $query_identifica_tablas->rowCount();
  //Creo un segundo contador y lo defino en ceros
  $cuenta_tablas_2=0;
  //Creo una variable para ir concatenando las tablas encontradas
  $codigo_array_tablas=null;
  //Imprimo un mensaje
  echo "Se encontraron <strong>$cuenta_tablas</strong> tablas(s) en la BD $dbname:<br>";
  //sleep(1);
  //Empiezo el tratamiento de datos
  while ($tabla=$query_identifica_tablas->fetch(PDO::FETCH_ASSOC)) {
    //Creo un stringo con el encabesado de la columna a utilizar
    $encabezado_tablas="Tables_in_".$dbname;
    //Imprimo un mensaje
    echo "-->".$tabla[$encabezado_tablas]."<br>";
    //sleep(1);
    //Reviso si la cuenta de la tabla en la que estoy es menor a la cantidad de tablas en la BDD o si es igual
    if ($cuenta_tablas_2<$cuenta_tablas-1) {
      //Mientras no sea la ultima tabla a escribir, concateno recursivamente la tabla agregandole una coma al final
      $codigo_array_tablas=$codigo_array_tablas."".$tabla[$encabezado_tablas].",";
    }else{
      //Si es la ultima tabla a escribir, concateno recursivamente la tabla sin agregarle una coma al final
      $codigo_array_tablas=$codigo_array_tablas."".$tabla[$encabezado_tablas]."";
    }
    //Aumento en 1 el segundo contador de tablas para pasar a la siguiente
    $cuenta_tablas_2++;
  }
  //Cierro la busqueda de las tablas en la base de datos
  $query_identifica_tablas->closeCursor();
  //Creo un arreglo en base a las tablas encontradas
  $array_tablas = explode(',', $codigo_array_tablas);
  //Creeo un ciclo que comience a hacer el recorrido del array para cada una de las tablas de la BDD
  for ($i=0; $i < $cuenta_tablas; $i++) {
    //Imprimo un mensaje
    echo "<strong>-->Codigo de creacion de la tabla: ".$array_tablas[$i]."</strong><br>";
    //sleep(1);
    //Escribo en el archivo
    $linea="create table $array_tablas[$i] (";
    fwrite($archivo,$linea);
    fwrite($archivo,"\n");
    //sleep(1);
    //Creo una sentencia para obtener los campos de la tabla
    $identifica_campos="describe $array_tablas[$i];";
    //Preparo la sentencia para ser ejecutada
    $query_identifica_campos=$conn->prepare($identifica_campos);
    //Ejecuto la sentencia
    $query_identifica_campos->execute();
    //Obtengo lsa cantidad de filas obtenidas en la consulta
    $cuenta_campos = $query_identifica_campos->rowCount();
    //Creo un segundo contador y lo defino en ceros
    $cuenta_campos_2=0;
    //Creo una variable para ir concatenando los campos encontrados
    $codigo_array_campos=null;
    //Creo una variable para ir concatenando los agregados identificados
    $codigo_array_agregados=null;
    //Empiezo el tratamiento de datos
    while ($campo=$query_identifica_campos->fetch(PDO::FETCH_ASSOC)) {
      //Asigno cada valor extraido
      $columna=$campo['Field'];
      $tipo=$campo['Type'];
      $nulo=$campo['Null'];
      $llave=$campo['Key'];
      $default=$campo['Default'];
      $extra=$campo['Extra'];
      //Comienzo a excribir las columnas de las tablas
      $campo_tabla="$columna $tipo";
      //Defino algunas condicionales
      if ($nulo=='NO') { $campo_tabla=$campo_tabla." not null"; }
      if ($llave=='PRI') { $campo_tabla=$campo_tabla." PRIMARY KEY"; }
      if ($default!='') { $campo_tabla=$campo_tabla." default $default"; }
      if ($extra!='') { $campo_tabla=$campo_tabla." $extra"; }
      //Reviso si la cuenta del campo en el que estoy es menor a la cantidad de campos en la tabla o si es igual
      if ($cuenta_campos_2<$cuenta_campos-1) {
        //Mientras sea menor a la cantidad de campos en la tabla, se le agregara una coma al final 
        $campo_tabla=$campo_tabla.",";
      }
      //Identifico el tipo y defino si necesitara un agregado de comillas o sera sin comillas
      switch ($tipo) {
        case 'int(11)':
          $agregado="no";
          break;
        case 'double':
          $agregado="no";
          break;
        default:
          $agregado="si";
          break;
      }
      //Hago una concatenacion utilizar las columnas posteriormente, excluyendo a la columna id
      if ($columna!='id') {
        //Reviso si la cuenta del campo en el que estoy es menor a la cantidad de campos en la tabla o si es igual
        if ($cuenta_campos_2<$cuenta_campos-1) {
          //Mientras no sea el ultimo campo a escribir, concateno recursivamente la columna agregandole una coma al final
          $codigo_array_campos=$codigo_array_campos."".$columna.",";
          //Mientras no sea el ultimo agregado a escribir, concateno recursivamente el agregado agregandole una coma al final
          $codigo_array_agregados=$codigo_array_agregados."".$agregado.",";
        }else{
          //Si es el ultimo campo a escribir, concateno recursivamente la columna sin agregarle una coma al final
          $codigo_array_campos=$codigo_array_campos."".$columna."";
          //Si es el ultimo agregado a escribir, concateno recursivamente el agregado sin agregarle una coma al final
          $codigo_array_agregados=$codigo_array_agregados."".$agregado."";
        }
      }
      //Aumento en 1 el segundo contador de campos para pasar al siguiente
      $cuenta_campos_2++;
      /*/Imprimo un mensaje
      echo $campo_tabla."<br>";*/
      //Escribo en el archivo
      $linea=$campo_tabla;
      fwrite($archivo,$linea);
      fwrite($archivo,"\n");
      //sleep(1);
    }
    //Escribo en el archivo
    $linea=");";
    fwrite($archivo,$linea);
    fwrite($archivo,"\n");
    //sleep(1);
    //Cierro la busqueda de los campos
    $query_identifica_campos->closeCursor();
    //Creo un arreglo en base a los campos encontrados
    $array_campos = explode(',', $codigo_array_campos);
    //Cuento la cantidad de campos  en el arreglo creado anteriormente
    $cuenta_array_campos = count ($array_campos);
    //Creo un arreglo en base a los agregados identificados
    $array_agregados = explode(',', $codigo_array_agregados);    

    /*Creo una sentencia para obtener los campos de la tabla
    $identifica_registros="select $codigo_array_campos from $array_tablas[$i];";
    //Preparo la sentencia para ser ejecutada
    $query_identifica_registros=$conn->prepare($identifica_registros);
    //Ejecuto la sentencia
    $query_identifica_registros->execute();
    //Obtengo lsa cantidad de filas obtenidas en la consulta
    $cuenta_registros = $query_identifica_registros->rowCount();
    //Creo un segundo contador y lo defino en ceros
    $cuenta_registros_2=0;
    //Creo una variable para ir concatenando los registros encontrados
    $codigo_array_registros=null;
    //Creo una variable para ir concatenando datos
    $datos=null;
    //mprimo un mensaje
    echo "<strong>-->Extraccion de $cuenta_registros registros(s) de la tabla: ".$array_tablas[$i]."</strong><br>";
    //Escribo en el archivo
    $linea="INSERT INTO $array_tablas[$i] ($codigo_array_campos) VALUES ";
    fwrite($archivo,$linea);
    fwrite($archivo,"\n");
    //sleep(1);
    //Empiezo el tratamiento de datos
    while ($registro=$query_identifica_registros->fetch(PDO::FETCH_ASSOC)) {
      //Comienzo el concatenado de los registros encontrados agregandole un parentesis para empezar
      $codigo_array_registros=$codigo_array_registros."(";
      //Reviso si la cuenta del registro en el que estoy es menor a la cantidad de registros en la tabla o si es igual
      if ($cuenta_registros_2<$cuenta_registros-1) {
        //Comienzo un ciclo recursivo hasta que el numero sea menor a la cantidad de campos encontrados
        for ($j=0; $j < $cuenta_array_campos ; $j++) {
          //Reviso si el agregado correspondiente del campo es positivo
          if ($array_agregados[$j]='si') {
            //Si el agregado es positivo, el dato de esa columna se concatena entre comillas
            $datos=$datos."'".$registro[$array_campos[$j]]."'";
          }else{
            //Si el agregado es negativo, al dato de esa columna
            $datos=$datos.$registro[$array_campos[$j]];
          }
          //Verifico si el numero actual en el ciclo es menor a la cantidad de campos en el arreglo y le agrego una coma al final 
          if ($j<$cuenta_array_campos-1) { $datos=$datos.","; }
        }
      }
      //Reviso si la cuenta del registro en el que estoy es menor a la cantidad de registros en la tabla o si es igual
      if ($cuenta_registros_2<$cuenta_registros-1) {
        //Mientras no sea el ultimo registro a escribir, concateno recursivamente la fila, cerrando el parentesis y agregandole una coma al final
        $codigo_array_registros=$codigo_array_registros."".$datos."),<br>";
      }else{
        //Si es el ultimo registro a escribir, concateno recursivamente la fila, cerrando el parentesis y agregandole un punto y coma al final
        $codigo_array_registros=$codigo_array_registros."".$datos.");<br>";
      }
      //Aumento en 1 el segundo contador de registros para pasar al siguiente
      $cuenta_registros_2++;
    }
    //Cierro la busqueda de los registros
    $query_identifica_registros->closeCursor();
    //Imprimo un mensaje
    //echo $codigo_array_registros;
    //Escribo en el archivo
    $linea=$codigo_array_registros;
    fwrite($archivo,$linea);
    fwrite($archivo,"\n");
    //sleep(1);*/
  }
  //Cierro el archivo
  fclose($archivo);
  
  /* Creamos las cabeceras que forzaran la descarga del archivo
  header('Content-Description: File Transfer');
  header("Content-disposition: attachment; filename=".basename($nombre_txt));
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header("Content-Length: ".filesize($nombre_txt));
  header("Content-Type: text/plain");
  ob_end_flush();
  readfile($nombre_txt);
  // Por último eliminamos el archivo temporal creado
  unlink("$nombre_txt");*/

?>