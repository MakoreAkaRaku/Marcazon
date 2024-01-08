<?php
include_once("components/reusable.php");
include_once("config/config.php");
redirectIfSessionNotAlive();
redirectIfUserIsNotRoleType('Comprador');
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="bg-gray-900 antialiased w-screen h-screen">
    <?php
    applyMainNavBar(true);
    if (empty($_POST["comanda"])) {
        header('location: /Marcazon/basket.php');
        exit;
    } else {
        $comanda = $_POST["comanda"];
        $query = "SELECT estatC from Comanda WHERE comanda.idCompr =" . $_SESSION["userid"] . " AND comanda.estatC = 'Comprant';";
        $estat = mysqli_fetch_array(mysqli_query($GLOBALS["conn"], $query))['estatC'];
        if ($estat == 'Comprant') 
        {
            $query = "SELECT COUNT(*) AS qttDomicilis FROM Domicili WHERE propietari=" . $_SESSION['userid'] . ";";
            $qtt = mysqli_fetch_array(mysqli_query($GLOBALS["conn"], $query))['qttDomicilis'];
            if ($qtt == 0) {
                h1("No es pot procedir; no tens cap Domicili","p-20 text-red-600");
                a("options.php","Afegeix un Domicili","p-4 rounded-md bg-blue-700 hover:bg-blue-900 self-center");
            } 
            else 
            {
                $selectClasses="bg-gray-700";
                $optClasses="bg-gray-700";
                $query = "SELECT idDomicili,carrer,numCasa,numPis,nomPoble FROM Domicili JOIN Poblacio ON Poblacio.codiPostal = Domicili.codiPostal WHERE Domicili.propietari = " . $_SESSION["userid"].";";
                $result = mysqli_query($GLOBALS['conn'], $query);
                $domicilis =[];
                while ($domicili = mysqli_fetch_array($result)) 
                {
                    $domicilis[$domicili["carrer"].", ".$domicili["numCasa"].", ".$domicili["numPis"].", ".$domicili["nomPoble"]] = $domicili["idDomicili"];
                }
                echo '<div class="text-white flex flex-col items-center">';
                h1("Tria el domicili on ha d'anar el paquet");
                echo '<form action="query/closeOrder.php" method="POST" class="h-80 flex flex-col justify-between items-center">';
                select($domicilis,"domicili",$selectClasses,$optClasses,true);
                input("hidden","comanda","",$comanda);
                echo '<button type="submit" class="my-4 self-center rounded-md w-40 p-2 bg-blue-700 hover:bg-blue-800">';
                echo 'Efectua la Compra';
                echo '</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            die("La comanda ja ha sigut tramesa");
        }

    }
    ?>
</body>

</html>