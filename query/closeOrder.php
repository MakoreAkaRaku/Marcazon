<?php
include_once("../config/config.php");
include_once("../logic/logInOut.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType('Comprador');
if(empty($_POST["comanda"]) || empty($_POST["domicili"])) {
    throw new Exception('Faltan dades per emplenar');
}

$query= sprintf("CALL tanca_comanda(%d,%d);",
$_POST['comanda'],
$_POST['domicili']
);
try
{
    mysqli_query($GLOBALS['conn'], $query);
    header('location: /Marcazon/basket.php');
    exit;
}
catch(Exception $ex) 
{
    die("Error:" .$ex->getMessage());
}

?>