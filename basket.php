<?php
include_once("components/reusable.php");
include_once("config/config.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType("Comprador");
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="bg-gray-900 antialiased w-screen h-screen">
    <?php
    applyMainNavBar(true);
    $estatComprant = true;
    echo '<div class="flex flex-col w-full h-full justify-start items-center">';
    if (!empty($_POST)) {
        $estatComprant = false;
        //Només utilitzat si es fa servir un check de una comanda antiga.
        $comanda = $_POST['comanda'];
    } else {
        $query = "SELECT idCom from Comanda WHERE comanda.idCompr =" . $_SESSION["userid"] . " AND comanda.estatC = 'Comprant';";
        try {
            if ($ans = mysqli_fetch_array(mysqli_query($GLOBALS["conn"], $query))) {
                $comanda = $ans['idCom'];
                $query = "SELECT COUNT(item.idItem) AS qtt FROM Item JOIN Comanda ON Comanda.idCom = item.idCom WHERE item.idCom=$comanda;";
                if (0 == mysqli_fetch_array(mysqli_query($GLOBALS["conn"], $query))['qtt']) {
                    $comanda = "";
                }
            }
        } catch (Exception $ex) {
            //There is no comanda for shopping.
            $comanda = "";
        }
    }
    if (!empty($comanda)) {
        displayBasket($comanda, $estatComprant);
    } else {
        h1("Encara no has comprat res", "flex self-center text-white");
    }
    if (empty($_POST) && !empty($comanda)) {
        echo '<form method="POST" action="verifyShopping.php" class="flex items-center justify-center">';
        input("hidden", "comanda", "", $comanda);
        echo '<button type="submit" class="p-2 text-white text-2xl bg-blue-700 hover:bg-blue-900 rounded-md">';
        echo 'Confirma';
        echo '</button>';
        echo '</form>';
    }
    echo '</div>';
    echo '<div class="absolute top-32 right-2 text-white">';
    listAllBaskets();
    echo '</div>';
    ?>
</body>

</html>