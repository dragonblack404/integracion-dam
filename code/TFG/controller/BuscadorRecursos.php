<?php


// use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
// use Box\Spout\Common\Helper\Escaper\CSV;
// use Box\Spout\Reader\Common\Creator\ReaderFactory;

// $root = '../../../../../';

// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Autoloader/autoload.php';
// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Reader/Common/Creator/ReaderFactory.php';
// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Reader/CSV/Reader.php';
// include_once $root . 'ini/server.php';
// include_once $root . 'inc/connection.php';
include_once '../server/Conexion.php';
// $conexion = connectLocalDB();

class BuscadorRecursos
{
    private $conexion;

    public function __construct($conn)
    {
        $this->conexion = $conn;
        // $this->buscarEvento($eventName);
    }

    public function buscarEvento($eventName)
    {
        // $conn = $conexion;

        if (!isset($eventName) || $eventName == null)
            $eventName = '';

        // $eventName = mb_convert_encoding($eventName, 'UTF-8', 'ISO-8859-1');
        $json['results'] = array();
        // $events = forward_static_call([self::class, 'getEventsByName'], $eventName);
        $events = $this->getEventsByName($eventName);

        foreach ($events as $event) {
            $data = array(
                'id' => $event['IdEvento'],
                'text' => $event['IdEvento'] . '-' . $event['Titulo']
            );
            array_push($json['results'], $data);
        }
        // $json = mb_convert_encoding($json, 'ISO-8859-1', 'UTF-8');
        // echo "<pre>";
        // var_dump($json);
        // echo "</pre>";

        echo json_encode($json);
        // echo json_last_error_msg();
    }

    // public function buscarTicket($ticketName)
    public function buscarTicket()
    {
        // $conn = $conexion;

        if (!isset($ticketName) || $ticketName == null)
            $ticketName = '';

        // $eventName = mb_convert_encoding($eventName, 'UTF-8', 'ISO-8859-1');
        $json['results'] = array();
        // $events = forward_static_call([self::class, 'getEventsByName'], $eventName);
        $events = $this->getNameTicket(1);

        foreach ($events as $event) {
            $data = array(
                'id' => $event['id_player'],
                'text' => $event['name_player']
            );
            array_push($json['results'], $data);
        }
        // $json = mb_convert_encoding($json, 'ISO-8859-1', 'UTF-8');
        // echo "<pre>";
        // var_dump($json);
        // echo "</pre>";

        echo json_encode($json);
        // echo json_last_error_msg();
    }

    public function enlazarTickets($ticketCsv, $ticketDB, $colTickets)
    {
        $palabraTicket = $this->relacionarColumnas($colTickets);

        //Enlazar el nombre de los ticket del fichero con los de la base de datos
        $query = 'UPDATE tabla_aux SET ' . $palabraTicket . ' = "' . $ticketDB . '" WHERE ' . $palabraTicket . ' = "' . $ticketCsv . '"';
        mysqli_query($this->conexion, $query);
        //Seleccionar el IdSession del ticket para añadirlo a los tickets en la tabla aux
        $query = 'SELECT IdSesion FROM ticketsesion WHERE IdTicket = '.$ticketDB;
        $resultado = mysqli_fetch_array(mysqli_query($this->conexion, $query))[0];
        //Actualizacion de la tabla aux con el IdSession correspondiente al ticket enlazado
        $query = 'UPDATE tabla_aux SET IdSesion = ' . $resultado;
        mysqli_query($this->conexion, $query);
    }

    public function cargarTickets($colTicket)
    {
        $palabraTicket = $this->relacionarColumnas($colTicket);

        // Consultar los tickets del evento seleccionado
        $sql = "SELECT DISTINCT $palabraTicket FROM tabla_aux";
        $resultado = mysqli_query($this->conexion, $sql);
        // echo $sql;

        // Crear un array con los tickets
        $tickets = array();
        while ($fila = mysqli_fetch_array($resultado)) {
            array_push($tickets, $fila["0"]);
        }

        $respuesta = array($tickets);

        //Conteo de tickets
        $conteoTickets = array();
        $contador = 1;
        foreach ($tickets as $value) {
            // $sql = "SELECT COUNT(*) AS cont FROM tabla_aux WHERE $palabraTicket = '$value'";
            // $resultado = mysqli_query($this->conexion, $sql);
            // $texto = mysqli_fetch_assoc($resultado)['cont'];
            // array_push($conteoTickets, "$texto equipo con: $value. victorias");

            array_push($conteoTickets, "Equipo - $contador con: $value victorias.");
            $contador++;
        }

        array_push($respuesta, $conteoTickets);

        // Devolver los tickets como JSON
        echo json_encode($respuesta);
    }

    private function relacionarColumnas($colName){
        $array1 = array("col1", "col2", "col3", "col4", "col5", "col6", "col7", "col8", "col9", "col10", "col11", "col12", "col13", "col14", "col15");
        $array2 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P");

        $indiceTicket = array_search(strtoupper($colName), $array2);
        if ($indiceTicket !== false) {
            $colNameDB = $array1[$indiceTicket];
        }

        return $colNameDB;
    }

    private function getEventsByName($name)
    {
        $where = ' WHERE IdIdioma = 2 
        AND e.ModalidadEvento = 1 ';
        if ($name !== null && !is_numeric($name)) {
            $where .= " AND ei.Titulo LIKE '%$name%'";
        } else {
            $where .= " AND ei.IdEvento LIKE '$name%' ";
        }

        $query = 'SELECT DISTINCT ei.IdEvento, ei.Titulo
        FROM eventoidiomas AS ei 
        INNER JOIN eventos AS e
        ON ei.IdEvento = e.IdEvento ';
        $query .= $where;
        $query .= 'ORDER BY ei.IdEvento DESC 
        LIMIT 15';

        $result = mysqli_query($this->conexion, $query);
        $data = array();
        while (($row = mysqli_fetch_assoc($result)) !== null) {
            $data[] = $row;
        }

        return $data;
    }

    private function getNameTicket($idUser)
    {
        // $whereId = " AND te.IdEvento = $idEvento ";

        $query = 'SELECT id_player, name_player
        FROM jugadores
        WHERE id_user = 1';
        // $query .= $whereId;

        $result = mysqli_query($this->conexion, $query);
        $data = array();
        while (($row = mysqli_fetch_assoc($result)) !== null) {
            $data[] = $row;
        }

        return $data;
    }
}
