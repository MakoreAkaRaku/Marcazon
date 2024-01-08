<?php
include_once("../config/config.php");
include_once("../logic/logInOut.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType('Comprador');
if (empty($_POST["idProd"]) || empty($_POST["amount"])) {
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
} else {

    $query = sprintf(
        "INSERT INTO Comanda (idCompr,haPagat) VALUES(%d, %s);",
        $userid,
        "FALSE"
    );
    mysqli_query($GLOBALS['conn'], $query);
    $idComanda = mysqli_insert_id($GLOBALS['conn']);
}
$query = "CALL afegir_prod_carret($stock,$prodAmount,$idComanda);";
try {
    mysqli_query($GLOBALS['conn'], $query);
    header('location: /Marcazon/basket.php');
    exit;
} catch (Exception $ex) {
    die("Error:" . $ex->getMessage());
}

?>