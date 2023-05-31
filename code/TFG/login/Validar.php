<?php
// Obtener los datos enviados desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Realizar la validación de los datos (ejemplo: verificar si el usuario y la contraseña son correctos)
    if ($username == "usuario" && $password == "contraseña") {
        // Acceso válido, redirigir al usuario a la página correspondiente
        header("Location: Principal.php");
        exit(); // Asegurarse de que se detiene la ejecución del script después de redirigir
    } else {
        // Datos inválidos, mostrar mensaje de error
        $error_message = "Usuario o contraseña incorrectos";
    }
}
?>