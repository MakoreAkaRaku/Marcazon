<?php
include_once("components/elements.php");
include_once("config/config.php");
$Allproducts = mysqli_query($GLOBALS["conn"], "SELECT * FROM Producte");
?>
<!DOCTYPE html>
<html>
<?php
include_once("config/head.php");
?>

<body style="background-image: url('styles/pet-steve.jpg'); background-repeat: repeat;">
    <div class="nav-bar flex flex-row justify-around bg-teal-400">
        <?php
        $userIconStyle= '';
        if (!empty($_SESSION)) {
            $nick = $_SESSION['user'];
            $role = $_SESSION['role'];
            echo "<div class=\"user-icon\">UserIconIdentity</div>";
            echo "Bones, $nick";
            a('query/logout.php','Tanca Sessió',""); //TMP
        } else {
            echo "<div class=\"user-icon\">UserIconNonIdentity</div>";
            echo '<div class="flex flex-col text-center">';
            a('signin.php',"Inicia Sessió","");
            p("o bé","text-sm");
            a('signup.php',"Registra't","");
            echo '</div>';
        }
        ?>
        <div>Here there's gonna be the search bar</div>
        <div>static basket or cesta for the buyer</div>
        <?php
        if (!empty($role))
        {
            $srcImg = ($role == "vendor") ? "price-tag.svg":"control-opt.svg";
            echo "<div> <img alt=\"$role options\" src=$srcImg></div>";
        }
         if (!empty ($nick))
         {
            echo "<div> <img alt=\"user options\" src=styles/config.svg></div>";
         }
        ?>
    </div>
    This is static bloody text, just as Marcazon theme.
    <div class="category-filter">

    </div>
    <div class="product-list">
        <?php
        while ($product = mysqli_fetch_array($Allproducts)) {
            echo "<div class=\"product\">" . $product["nomProd"] . "</div>";
        }
        ?>
    </div>
</body>

</html>