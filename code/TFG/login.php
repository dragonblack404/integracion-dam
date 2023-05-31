<?php

// Obtener los datos enviados desde el formulario
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST["username"];
//     $password = $_POST["password"];

//     // Realizar la validación de los datos (ejemplo: verificar si el usuario y la contraseña son correctos)
//     if ($username == "usuario" && $password == "contraseña") {
//         // Acceso válido, redirigir al usuario a la página correspondiente
//         header("Location: Principal.php");
//         exit(); // Asegurarse de que se detiene la ejecución del script después de redirigir
//     } else {
//         // Datos inválidos, mostrar mensaje de error
//         $error = "Usuario o contraseña incorrectos";
//     }
// }


include './header.php';

?>


<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <title>Portfolios - DragonBlack</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="STYLESHEET" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/de1e7074bc.js"></script>

    <link rel="shortcut icon" href="drake.ico" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-grid.min.css" integrity="sha512-JQksK36WdRekVrvdxNyV3B0Q1huqbTkIQNbz1dlcFVgNynEMRl0F8OSqOGdVppLUDIvsOejhr/W5L3G/b3J+8w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-reboot.min.css" integrity="sha512-IS8Z2ZgFvTz/yLxE6H07ip/Ad+yAGswoD1VliOeC2T4WaPFNPC1TwmQ5zomGS+syaR2oO3aXJGKaHv21Dspx0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-utilities.min.css" integrity="sha512-DEGBrwaCF4lkKzMKNwt8Qe/V54bmJctk7I1HyfINGAIugDvsdBeuWzAWZmXAmm49P6EBfl/OeUM01U3r7cW4jg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head> -->

<head>
    <link rel="STYLESHEET" type="text/css" href="./css/login.css">
    <script src="./js/login/login.js"></script>
</head>

<body>
    <!-- 
    <div class="row">
        <div class="col-md-6">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" id="usuario" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="contrasena" class="form-label">Contraseña:</label>
            <input type="password" id="contrasena" class="form-control">
        </div>
    </div>
    </div> -->

    <form action="./Principal.php" method="POST">
        <label for='username' class="form-label"> Usuario: </label>
        <input type='text' name="username" id='username' required class="form-control">
        <label for='password' class="form-label"> Contraseña: </label>
        <input type='password' name="password" id='password' required class="form-control">
        <button type='submit' style="width: 20%;" class="btn btn-outline-primary"> Iniciar sesión </button>
        <a href='/signup.html'> Si no tienes cuenta, ¡crea una! </a>
    </form>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <script type='module' src='./login/login.js'></script>

</body>