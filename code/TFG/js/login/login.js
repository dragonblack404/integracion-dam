function irAPagina() {

  var username = document.forms["loginForm"]["username"].value;
  var password = document.forms["loginForm"]["password"].value;

  if (username === "" || password === "") {
    document.getElementById("error").innerText = "Por favor, ingresa un usuario y contraseña válidos.";
    return false;
  } else
    window.location.href = "Principal.php";

}

