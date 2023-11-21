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

function logged_in_gui($nick)
{
    echo "<div class=\"user-icon\">UserIconIdentity</div>";
    $time = date("G", time());
    if ($time >= 20) {
        $welcomeUser = "Bona nit";
    } elseif ($time >= 13) {
        $welcomeUser = "Bona tarda";
    } else {
        $welcomeUser = "Bon dia";
    }
    echo $welcomeUser . ", " . $nick;
    a('query/logout.php', 'Tanca Sessió', ""); //FIXME
}
function unlogged_gui()
{
    echo "<div class=\"user-icon\">UserIconNonIdentity</div>";
    echo '<div class="flex flex-col text-center">';
    a('signin.php', "Inicia Sessió", "");
    p("o bé", "text-sm");
    a('signup.php', "Registra't", "");
    echo '</div>';
}

/**
 * It returns a gradient background look depending on user role.
 */
function custom_bg()
{
    $role = has_session() ? $_SESSION['role'] : '';
    $customClasses = 'bg-gradient-to-b bg-no-repeat bg-full bg-fixed ';
    switch ($role) {
        case 'Venedor':
            $customClasses .= "from-red-500";
            break;
        case 'Comprador':
            $customClasses .= "from-green-500";
            break;
        case 'Controlador':
            $customClasses .= "from-orange-400";
            break;
        default:
            $customClasses .= "from-indigo-400";
    }
    $customClasses .= " to-black";
    echo $customClasses;
}

function applyMainNavBar()
{
    
    $mainClasses = "flex flex-row bg-teal-400 justify-between shadow-xl px-4";
    echo '<nav role="navigation" ';
    applyClasses($mainClasses, '');
    echo '>';
    echo '<div class="flex flex-row inline-flex">';
    has_session() ? logged_in_gui($_SESSION['user']) : unlogged_gui();
    echo '</div>';
    echo "<div>Here there's gonna be the search bar</div>";
    echo "<div>static basket or cesta for the buyer</div>";
    if (has_session()) {
        $role = $_SESSION['role'];
        $srcImg = 'styles/';
        $srcImg .= ($role == 'Venedor') ? "price-tag.svg" : "control-opt.svg";
        echo "<div>";
        img($role . ' options', $srcImg, "h-10");
        echo "</div>";
    }
    echo '</nav>';
}
?>