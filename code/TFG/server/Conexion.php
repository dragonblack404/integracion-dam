<?php
function conectarDB() {
    $servername = "localhost";
    $username = "padel_admin";
    $password = "kiwi1234";
    $database = "padel_web";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    return $conn;
}
?>