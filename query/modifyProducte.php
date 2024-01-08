<?php
include_once("../config/config.php");
include_once("../logic/logInOut.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType('Comprador');

if (empty($_POST["idProd"]) || (empty($_POST["amount"])&& $_POST['amount']!= "0")) {
    throw new Exception('Faltan datos por rellenar');
}

$stock = $_POST["idProd"];
$prodAmount = $_POST["amount"];
$userid = $_SESSION["userid"];


$query = sprintf(
    "SELECT idCom FROM Comanda WHERE idCompr = %d AND estatC = 'Comprant';",
    $userid
);

if ($result = mysqli_fetch_array(mysqli_query($GLOBALS['conn'], $query))) {
    $idComanda = $result['idCom'];
} 
else 
{
    throw new Exception("No hi ha cap comanda en compra");
}
$query = "CALL modif_prod_carret($stock,$prodAmount,$idComanda);";
try {
    mysqli_query($GLOBALS['conn'], $query);
    header('location: /Marcazon/basket.php');
    exit;
} catch (Exception $ex) {
    die("Error: " . $ex->getMessage());
}

?>