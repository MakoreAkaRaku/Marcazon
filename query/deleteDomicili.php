<?php
include_once("../config/config.php");
include_once("../logic/logInOut.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType('Comprador');
if (empty($_POST["idDomicili"])) {
    throw new Exception('Faltan dades per emplenar');
}

//No eliminam el domicili per què al final es un domicili que s'hauria pogut fer servir en un repartiment.
$query = sprintf(
    "UPDATE Domicili SET propietari=NULL WHERE idDomicili=%d AND propietari=%d;",
    $_POST['idDomicili'],
    $_SESSION['userid']
);
try {
    mysqli_query($GLOBALS['conn'], $query);
    header('location: /Marcazon/options.php');
    exit;
} catch (Exception $ex) {
    die("Error:" . $ex->getMessage());
}

?>