<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marcazon";
// Crea connexió
$conn = mysqli_connect($servername, $username, $password, $dbname);
$GLOBALS["conn"] = $conn;
// verifica si la connexió ha sigut exitosa
if (!$conn) {
    die("La connexió ha fallat: " . mysqli_connect_error());
}
function has_session()
{
    return !empty($_SESSION);
}
?>