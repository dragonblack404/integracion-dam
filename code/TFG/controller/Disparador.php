<?php

include_once '../model/TablaModel.php';
include_once './BuscadorRecursos.php';
include_once './Funciones.php';

session_start();
$conexion = conectarDB();


switch ($_REQUEST['accion']) {
    // case 1: //Busca el evento por nombre o id en la DB
    //     (new BuscadorRecursos($conexion))->buscarEvento($_REQUEST['eventName']);
    //     break;
    case 2: //Busca los tickets de X evento en la DB
        // (new BuscadorRecursos($conexion))->buscarTicket($_REQUEST['idEvento']);
        (new BuscadorRecursos($conexion))->buscarTicket();
        break;
    case 3: //Relaciona los nombres de los tickets de la DB con el File
        (new BuscadorRecursos($conexion))->enlazarTickets($_REQUEST['ticketCsv'], $_REQUEST['ticketDB'], $_REQUEST['fieldColTickets']);
        echo 'Enlace completado';
        break;
    case 4: //Carga el combo Box de los tickets del File
        (new BuscadorRecursos($conexion))->cargarTickets($_REQUEST['fieldColTickets']);
        break;
    case 5: //Genera la tabla html a partir del fichero y los ficheros en 'resources'
        if (isset($_FILES['file'])) {
            $path = $_FILES['file']['name'];
            // VOLVER A INTRODUCIR ESTO !!! -> $_SESSION['user_id']
            $path = subirFile($path, 1, $_FILES['file']['size'], $conexion);
            $tabla = (new TablaModel($path))->generarTablaFromFile();
            // echo json_encode($tabla);
            // echo $tabla;
            // (new TablaModel($path))->generarTablaFromFile();
            // insertarFile($path, $conexion);
        }
        break;
    case 7: //Cambiar de fichero <-> 
        $path = $_REQUEST['path'] . $_SESSION['user_id'] . '/' . $_REQUEST['fileName'];
        (new TablaModel($path))->generarTablaFromFile();
        break;
    case 8: //Importar los tickets en la base de datos
        $sql = "SELECT IdSesion FROM ticketsesion 
        INNER JOIN ticketevento
        ON ticketsesion.IdTicket = ticketevento.IdTicket
        WHERE ticketevento.IdEvento = 4123";
        // // $resp = (mysqli_fetch_array($conexion->query($sql)))[0];
        $resp = mysqli_fetch_array(mysqli_query($conexion, $sql))[0];
        echo $resp;

        //Ventas 
        $idSesion = $resp;
        $localizador = (isset($_REQUEST['col_localizador']) && $_REQUEST['col_localizador'] !== '') ? relacionarColumnas($_REQUEST['col_localizador']) : relacionarColumnas('');
        $nombre = (isset($_REQUEST['col_nombre']) && $_REQUEST['col_nombre'] !== '') ? relacionarColumnas($_REQUEST['col_nombre']) : relacionarColumnas('');
        $apellidos = (isset($_REQUEST['col_apellidos']) && $_REQUEST['col_apellidos'] !== '') ? relacionarColumnas($_REQUEST['col_apellidos']) : relacionarColumnas('');
        $documento = (isset($_REQUEST['col_documento']) && $_REQUEST['col_documento'] !== '') ? relacionarColumnas($_REQUEST['col_documento']) : relacionarColumnas('');
        $telefono = (isset($_REQUEST['col_telefono']) && $_REQUEST['col_telefono'] !== '') ? relacionarColumnas($_REQUEST['col_telefono']) : relacionarColumnas('');
        $email = (isset($_REQUEST['col_email']) && $_REQUEST['col_email'] !== '') ? relacionarColumnas($_REQUEST['col_email']) : relacionarColumnas('');
        $fechaVenta = (isset($_REQUEST['col_fecha']) && $_REQUEST['col_fecha'] !== '') ? relacionarColumnas($_REQUEST['col_fecha']) : relacionarColumnas('');
        $idEstadoVenta = 2;
        $idFormaPago = 2;
        $idIdioma = 2;
        $idZonaVenta = 0;

        //Ventas Detalle
        $tickets = (isset($_REQUEST['col_tickets']) && $_REQUEST['col_tickets'] !== '') ? relacionarColumnas($_REQUEST['col_tickets']) : relacionarColumnas('');
        $idEstado = 1;

        $query = "SELECT $nombre, $apellidos, $documento, $telefono, $email, $localizador, $fechaVenta, $tickets FROM padel_aux";
        echo "<hr>";
        echo $query;
        echo "<hr>";
        $result = mysqli_query($conexion, $query);
        while (($row = mysqli_fetch_assoc($result)) !== null) {
            var_dump($row);
            echo "<hr>";
            // $sql = "INSERT INTO tickets_imports_provisional (idVentas, nombre, apellidos, email) 
            $sql = "INSERT INTO tickets_imports_provisional(IdSesion, Localizador, Nombre, Apellidos, Documento, Telefono, Email, FechaVentas, idEstadoVenta, idFormaPago, idIdioma, idZonaVenta) 
            VALUES 
            ($idSesion, '" . $row[$localizador] . "', '" . $row[$nombre] . "', '" . $row[$apellidos] . "', 
            '" . $row[$documento] . "', null, '" . $row[$email] . "', '" . $row[$fechaVenta] . "', $idEstadoVenta, $idFormaPago, $idIdioma, $idZonaVenta)";
            // echo $sql;
            $conexion->query($sql);
            $id = mysqli_insert_id($conexion);
            $sql = "INSERT INTO tickets_imports_provisional(IdVenta, IdTicket, Localizador, Nombre, Apellidos, Documento, Email, IdEstado) 
            VALUES 
            ($id, '" . $row[$tickets] . "', '" . $row[$localizador] . "', '" . $row[$nombre] . "', '" . $row[$apellidos] . "', '" . $row[$documento] . "', '" . $row[$email] . "', $idEstado)";
            $conexion->query($sql);
        }
        ;

        break;
    case 9: //Eliminar encabezado de la tabla_aux
        $sql = "DELETE FROM padel_aux WHERE id = 1";
        $conexion->query($sql);
        break;
    default: //Pruebas


        break;
}

$conexion->close();