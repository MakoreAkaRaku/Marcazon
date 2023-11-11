<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marcazon";

// Crea connexió
$conn = mysqli_connect($servername, $username, $password, $dbname);
// verifica si la connexió ha sigut exitosa
if (!$conn) {
    die("La connexió ha fallat: " . mysqli_connect_error());
}
?>