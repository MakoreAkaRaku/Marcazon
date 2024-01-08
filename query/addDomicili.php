<?php
include_once("../config/config.php");
include_once("../logic/logInOut.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType('Comprador');
if(empty($_POST["carrer"]) || empty($_POST["numCasa"]) || empty($_POST["numPis"]) || empty($_POST["poble"])) {
    throw new Exception('Faltan dades per emplenar');
}

$query= sprintf("INSERT INTO Domicili (propietari,numCasa,numPis,carrer,codiPostal) VALUES(%s,%d,%d,'%s',%d);",
$_SESSION['userid'],
$_POST['numCasa'],
$_POST['numPis'],
$_POST['carrer'],
$_POST['poble']);
try
{
    mysqli_query($GLOBALS['conn'], $query);
    header('location: /Marcazon/options.php');
    exit;
}
catch(Exception $ex) 
{
    die("Error:" .$ex->getMessage());
}

?>