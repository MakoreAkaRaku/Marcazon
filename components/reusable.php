<?php
include_once("elements.php");
function head()
{
    echo "<head>
<title>Marcazon</title>
<link rel=\"stylesheet\" href=\"pending\">
<link rel=\"icon\" href=\"./styles/ico.png\">
<script src=\"https://cdn.tailwindcss.com\"></script>
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
</head>";
}
function displayProducts()
{
    $Allproducts = mysqli_query($GLOBALS["conn"], "SELECT * FROM Producte");
    echo '<div class="category-filter">';
    //TODO
    echo '</div>';
    h1("PRODUCTES", "justify-center text-lg text-white");
    echo '<div class="product-list">';
        while ($product = mysqli_fetch_array($Allproducts)) {
            echo "<div class=\"product\">" . $product["nomProd"] . "</div>";
        }
    echo '</div>';
}
?>