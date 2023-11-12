<?php
include_once("phpQs/config.php");

?>
<!DOCTYPE html>
<html>

<head>
    <title>Marcazon</title>
    <link rel="stylesheet" href="pending">
    <link rel="icon" href="https://www.w3schools.com/favicon.ico">
</head>
<header>
</header>

<body>
    <div class="nav-bar">
        <?php
        if (!empty($_SESSION)) {
            echo "<div class=\"user-icon\">UserIconIdentity</div>";
            
        }
        else {
            echo "<div class=\"user-icon\">UserIconNonIdentity</div>";
        }
        ?>
        <div>Here there's gonna be the search bar</div>
        <div>static basket or cesta for the buyer</div>
        <?php

        switch ($role) {
            case 'vendor':
                echo "<div>Dynamic added button or smt for the vendor</div>";
                break;
            case 'controler':
                echo "<div>Dynamic added button or smt for the vendor</div>";
                break;
            default:
                # code...
                break;
        }
        echo "<div>Config reference for the user</div>";
        ?>

        <div>Dynamic added button or smt for the controler</div>
    </div>
    This is static bloody text, just as Marcazon theme.
    <div class="category-filter">

    </div>
    <div class="product-list">
        <div class="product"> this is a product</div>
        <div class="product"> this is a product</div>
        <div class="product"> this is a product</div>
    </div>
</body>

</html>