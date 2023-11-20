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

<body>
    <div class="nav-bar flex flex-row bg-teal-400">
        <?php
        $userIconStyle= '';
        if (!empty($_SESSION)) {
            $role = $_SESSION["role"];
            $nick = $_SESSION["nickname"];
            echo "<div class=\"user-icon\">UserIconIdentity</div>";
            echo "Bones, $nick";
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