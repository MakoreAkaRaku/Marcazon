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

function logged_in_gui($nick,$role)
{
    img("user icon","styles/user$role.png","w-20 h-20 grayscale");
    $time = date("G", time());
    if ($time >= 20) {
        $welcomeUser = "Bona nit";
    } elseif ($time >= 13) {
        $welcomeUser = "Bona tarda";
    } else {
        $welcomeUser = "Bon dia";
    }
    p($welcomeUser . ", " . $nick,"w-full");
    a('query/logout.php', 'Tanca Sessió', "w-full"); //FIXME
}
function unlogged_gui()
{
    img("user icon","styles/defaultUser.png","w-20 h-20 grayscale");
    echo '<div class="flex flex-col text-center w-full">';
    a('signin.php', "Inicia Sessió", "");
    p("o bé", "text-sm");
    a('signup.php', "Registra't", "");
    echo '</div>';
}

function addNavSearchBar()
{
    echo '<form class="relative w-full">';
    input('search','product-search','p-4 w-full text-sm text-gray-900 bg-gray-700 border-none dark:bg-gray-700  dark:placeholder-gray-400 dark:text-white',"Cerca productes...");
    echo '<button class="absolute end-0 p-4 bg-lime-950 rounded-md" type="submit">';
    /*echo '<svg class="" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
</svg>';*/
    img("search icon","styles/search.svg","w-5 h-5");
    echo '</button>';
    echo '</form>';
}

function applyMainNavBar()
{
    
    $mainClasses = "px-2 flex flex-row bg-gradient-to-r from-cyan-500 to-blue-500 items-center shadow-2xl text-white h-20";
    echo '<nav role="navigation" ';
    applyClasses($mainClasses, '');
    echo '>';
    echo '<div class="flex flex-row items-center shrink-0">';
    has_session() ? logged_in_gui($_SESSION['user'],$_SESSION['role']) : unlogged_gui();
    echo '</div>';
    addNavSearchBar();
    echo "<div>static basket or cesta for the buyer</div>";
    if (has_session()) {
        $role = $_SESSION['role'];
        $srcImg = 'styles/';
        $srcImg .= ($role == 'Venedor') ? "price-tag.svg" : "control-opt.svg";
        echo "<div>";
        img($role . ' options', $srcImg, "h-10 w-10");
        echo "</div>";
    }
    echo '</nav>';
}
?>