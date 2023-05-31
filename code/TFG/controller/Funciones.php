<?php

// if (!isset($root)) $root = '../../../../';

// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Reader/Common/Creator/ReaderEntityFactory.php';
// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Autoloader/autoload.php';
require_once '../Box/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

use Box\Spout\Reader\Common\Creator\ReaderFactory;

/**Cargamos el fichero deseado dentro de la carpeta personal del usuario */
function subirFile($fileName, $id, $fileSize, $conexion)
{
    // Nombre del fichero

    // Localizacion
    if (!is_dir('../resources/partidos' . $id)) {
        mkdir('../resources/partidos' . $id);
    }
    $path = '../resources/partidos' . $id . '/*';
    borrarFiles($path);
    $location = '../resources/partidos' . $id . '/' . $fileName;
    // Extension fichero
    $file_extension = pathinfo($location, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);

    // Extensiones válidas
    $valid_ext = array("xlsx", "csv");

    if (in_array($file_extension, $valid_ext)) {
        // Carga del fichero
        if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
        }
    }

    insertarFile($location, $conexion);

    return $location;
}

/**Borrar ficheros existentes en la carpeta del usuario */
function borrarFiles($path)
{
    if (count(glob($path)) !== 0) {
        foreach (glob($path) as $file) {
            if (is_file($file))
                unlink($file);
        }
    }
}

/**Creamos el reader del fichero */
function crearLectorFile($file)
{
    if ((new SplFileInfo($file))->getExtension() == 'csv') {
        $reader = ReaderEntityFactory::createCSVReader();
        $delimiter = getDelimiter($file);
        $reader->setFieldDelimiter($delimiter);
    } else {
        $reader = ReaderEntityFactory::createXLSXReader();
    }

    return $reader;
}

/** 
 * Funcion para detectar el delimitador de un fichero csv 
 */
function getDelimiter($file)
{
    $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];
    $handle = fopen($file, "r");
    $firstLine = fgets($handle);
    fclose($handle);
    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }
    return array_search(max($delimiters), $delimiters);
}

/**
 * Se colocan las letras que aparecerían en Excel sobre las columnas correspondientes
 * siendo la primera columna un @ en referencia a las filas de la tabla
 */
function arrayLetrasColumnas($numDatos)
{
    $arrayLetras = [];
    for ($i = 0; $i < $numDatos; $i++) {
        $letra = chr(65 + $i);
        array_push($arrayLetras, $letra);
    }
    return $arrayLetras;
}

/**Se coloca una columna por delante de la tabla para colocar el número de fila de la misma */
function arrayNumFilastabla($tabla)
{
    $numFila = 0;
    for ($i = 1; $i < count($tabla); $i++) {
        array_unshift($tabla[$i], $numFila);
        $numFila++;
    }
    return $tabla;
}


/**
 * Lee un fichero XLS/CSV y retorna un array con el contenido
 */
function leerFile($file)
{
    $lector = crearLectorFile($file);
    $lector->open($file);

    $filas = [];
    foreach ($lector->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            $cells = $row->getCells();
            array_push($filas, $cells);
        }
    }
    $lector->close();

    return $filas;
}

/** Para leer un archivo XLSX/CSV e insertarlo en la tabla auxiliar */
function insertarFile($file, $conexion)
{
    deleteTablaTicketsAux($conexion);

    $sizeFilas = 0;
    $filas = leerFile($file);

    $sizeFilas = sizeof($filas);
    $sizeElemento = sizeof($filas[0]);
    $consulta = "INSERT INTO tabla_aux (id";
    for ($k = 1; $k <= $sizeElemento; $k++) {
        $consulta .= ", col" . $k;
    }
    $consultaConColumnas = $consulta;
    $consulta .=  ") VALUES (";
    for ($i = 0; $i < $sizeFilas; $i++) {
        $consulta .= ($i + 1) . ",'";
        for ($j = 0; $j < $sizeElemento; $j++) {
            if ($j < ($sizeElemento - 1)) {
                $consulta = $consulta . $filas[$i][$j] . "','";
            } else {
                $consulta = $consulta . $filas[$i][$j] . "')";
            }
        }
        $conexion->query($consulta);
        $consulta = $consultaConColumnas . ") VALUES (";
    }
}

/**Limpiar tabla auxiliar */
function deleteTablaTicketsAux($conexion)
{
    $consulta = "DELETE FROM tabla_aux";
    $conexion->query($consulta);

}

/**Divide un fichero XLS/CSV a través de la base de datos
 * donde se encuentren los datos de la tabla
 */
function dividirTabla($conexion, $location, $fileName, $fileSize)
{
    $sql = "SELECT CAST(COUNT(*) AS INT) FROM tabla_aux";
    $numeroFilas = $conexion->query($sql);
    $intervaloFilas = intval(mysqli_fetch_row($numeroFilas)[0]) / 1000;
    $parte_entera = intval(explode(".", $intervaloFilas)[0]);
    if (!is_integer($intervaloFilas))
        $parte_decimal = intval(explode(".", $intervaloFilas)[1]);
    else
        $parte_decimal = 0;
    $iteraciones = ($parte_decimal != 0) ? $parte_entera + 1 : $parte_entera;
    $fila_ini = 1;
    $fila_fin = 1000;
    for ($i = 0; $i < $iteraciones; $i++) {
        $sql = "SELECT col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12
        FROM tabla_aux 
        WHERE id BETWEEN $fila_ini AND $fila_fin";
        $filasTabla = $conexion->query($sql);
        $f = fopen($location . $i . "_" . $fileName, "w+") or die("Unable to open file!");
        while ($row = mysqli_fetch_assoc($filasTabla)) {
            fputcsv($f, $row, ";");
        }
        fclose($f);

        $fila_ini += 1000;
        $fila_fin += 1000;
    }

}

/**Relacionar las columnas del fichero a con los datos que reflejan */
function relacionarColumnas($colName)
{
    $array1 = array("col1", "col2", "col3", "col4", "col5", "col6", "col7", "col8", "col9", "col10", "col11", "col12", "col13", "col14", "col15");
    $array2 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P");

    $indiceEquipo = array_search(strtoupper($colName), $array2);
    if ($indiceEquipo !== false) {
        $colNameDB = $array1[$indiceEquipo];
    }else{
        $colNameDB = "nulo";
    }

    return $colNameDB;
}

