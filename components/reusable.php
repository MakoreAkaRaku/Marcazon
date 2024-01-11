<?php
include_once("elements.php");
function head()
{
    echo "<head>
<title>Marcazon</title>
<link rel=\"stylesheet\" href=\"pending\">
<link rel=\"icon\" href=\"./styles/ico.png\">
<script src=\"https://cdn.tailwindcss.com\"></script>
<script src=\"../path/to/flowbite/dist/flowbite.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js\"></script>
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
</head>";
}
function displayProducts()
{
    $titleText = "PRODUCTES";
    //We must sanitize filters.
    $productClasses = "w-80 h-80 border text-white m-2 shadow-2xl";
    $query = "SELECT stock.idStock, stock.nomProd FROM Stock";
    $numCat = !empty($_GET[$GLOBALS['categoriesId']]) ? count($_GET[$GLOBALS['categoriesId']]) : 0;
    if ($numCat != 0) {
        $query .= " JOIN Producte ON Producte.nomProd = Stock.nomProd";
        $query .= " JOIN ProdCat ON ProdCat.nomProd = Producte.nomProd";
    }
    print_r($_GET);
    $query .= (!empty($_GET['prod-search']) || $numCat != 0) ? " WHERE" : "";
    if ($numCat != 0) {
        $query .= " cat in (";
        $cnt = 0;
        while ($cnt != $numCat) {
            $query .= "'" . $_GET[$GLOBALS['categoriesId']][$cnt] . "'";
            $cnt++;
            $query .= ($cnt != $numCat) ? "," : "";
        }
        $query .= ") ";
        //$query .= "GROUP BY Stock.idStock HAVING COUNT(stock.idStock) >= ".$numCat; //maybe change operator with >= ?
        if (!empty($_GET[$GLOBALS['searchId']]))
            $query .= " AND";
        //SELECT * FROM Stock JOIN Producte ON Producte.nomProd = Stock.nomProd JOIN ProdCat ON ProdCat.nomProd = Producte.nomProd WHERE cat in ('Electrodomestics','Electronica','Informatica')having count(*) = 3 ORDER BY Stock.nomProd ASC;
    }
    if (!empty($_GET[$GLOBALS['searchId']])) {
        //TODO make all of the filter construction
        $query .= " Stock.nomProd LIKE '%" . $_GET[$GLOBALS['searchId']] . "%'";
    }
    if ($numCat != 0) {
        $query .= " GROUP BY idStock";
        $query .= " HAVING COUNT(idStock) = $numCat";
    }
    $query .= " ORDER BY Stock.nomProd ASC";
    $query .= !empty($_GET) ? ";" : "";
    echo $query;
    $Allproducts = mysqli_query($GLOBALS["conn"], $query);
    h1($titleText, "flex justify-center text-center text-white");
    $linkClasses = "transition delay-60 hover:shadow-2xl hover:scale-110";
    echo '<div class="flex self-center flex-wrap items-center justify-center">';
    while ($product = mysqli_fetch_array($Allproducts)) {
        echo '<a class="' . $linkClasses . '" href="/Marcazon/?idProd=' . $product["idStock"] . '">';
        echo '<div class="' . $productClasses . '">' . $product["nomProd"] . "</div>";
        echo '</a>';
    }
    echo '</div>';
}

function displayProductImage($prodName, $imageName, $prodImgClasses = "")
{
    $imguri = $GLOBALS['prodUri'] . $imageName;
    file_exists($imguri) ?
        img("Imatge del producte " . $prodName, $imguri, $prodImgClasses) :
        img("Imatge no trobada del producte " . $prodName, "prodimg/notfound.png", $prodImgClasses);

}

function displayProduct($stock)
{
    $prodClasses = "flex flex-col items-stretch text-white justify-center p-4 w-full h-full";
    $prodQuery = mysqli_query($GLOBALS["conn"], "SELECT * FROM Stock JOIN producte ON producte.nomProd = Stock.nomProd JOIN Venedor ON Venedor.idVen=Stock.propietari WHERE Stock.idStock = $stock");
    echo '<div class="' . $prodClasses . '">';
    if ($product = mysqli_fetch_array($prodQuery)) {
        $prodImgClasses = "w-80 h-80";
        echo '<div class="flex flex-row w-full h-full items-start justify-center self-stretch">';
        echo '<div class ="flex-col mx-8">';
        displayProductImage($product["nomProd"], $product["pathImg"], $prodImgClasses);
        p("Venedor: " . $product["nom"], "text-center");
        echo '</div>';
        if ($product["qttProd"] > 0) {
            $buttonClasses = "text-2xl bg-blue-500 rounded-lg p-2 hover:bg-blue-900";
            $numClasses = "text-2xl text-center w-16 bg-gray-700 p-2 rounded-lg";
            echo '<div class="flex-col justify-between">';
            h1($product["nomProd"], "text-xl");
            p("Preu: " . $product["preu"] . "€/ud", "text-lg my-10");
            echo '<form  method="post" action="query/afegeixProducte.php">';
            echo '<input type="hidden" name="idProd" value="' . $stock . '">';
            echo '<input name="amount" class="' . $numClasses . '" type="number" value="1" min="1" max="' . $product["qttProd"] . '">';
            echo '<button type="submit" class="' . $buttonClasses . '">';
            echo 'Afegeix a la Cistella';
            echo '</button>';
            echo '</form>';
            echo '</div>';
        } else {
            p("Producte acabat, perdoni les molesties!");
        }
    } else {
        h1("EL PRODUCTE QUE CERCA NO S'HA TROBAT :(", "self-center");
        img("error 404 not found random image", "https://picsum.photos/600", "flex self-center justify-center");

    }
    echo '</div>';
}

function modifyProducteComanda($stock)
{
    $prodClasses = "flex flex-col items-stretch text-white justify-center p-4 w-full h-full";
    $query = sprintf(
        "SELECT idCom FROM Comanda WHERE Comanda.idCompr = %d AND Comanda.estatC = 'Comprant';",
        $_SESSION['userid']
    );
    echo '<div class="' . $prodClasses . '">';
    if ($result = mysqli_query($GLOBALS['conn'], $query)) {
        $comanda = mysqli_fetch_array($result)['idCom'];
        $query = sprintf(
            "SELECT Venedor.nom AS nom, COUNT(Item.idStock) AS qttProd,Stock.nomProd AS nomProd,Producte.pathImg AS pathImg FROM Item JOIN Stock ON Stock.idStock=Item.idStock JOIN Venedor ON Venedor.idVen=Stock.propietari JOIN Producte ON Producte.nomProd = Stock.nomProd WHERE item.idStock= %d AND item.idCom = %d;",
            $stock,
            $comanda
        );
        $prodQuery = mysqli_query($GLOBALS["conn"], $query);
        if ($product = mysqli_fetch_array($prodQuery)) {
            $prodImgClasses = "w-80 h-80";
            echo '<div class="flex flex-row w-full h-full items-center justify-center self-stretch">';
            echo '<div class ="flex-col mx-8">';
            displayProductImage($product["nomProd"], $product["pathImg"], $prodImgClasses);
            p("Venedor: " . $product["nom"], "text-center");
            echo '</div>';
            $buttonClasses = "text-2xl bg-green-600 rounded-lg p-2 hover:bg-green-800";
            $numClasses = "text-2xl text-center w-16 bg-gray-700 p-2 rounded-lg";
            echo '<div class="flex-col justify-between">';
            h1($product["nomProd"], "text-xl");
            echo '<form  method="post" action="query/modifyProducte.php">';
            echo '<input type="hidden" name="idProd" value="' . $stock . '">';
            echo '<input name="amount" class="' . $numClasses . '" type="number" value="' . $product["qttProd"] . '" min="0">';
            echo '<button type="submit" class="' . $buttonClasses . '">';
            echo 'Desa';
            echo '</button>';
            echo '</form>';
            echo '</div>';
        } else {
            p("No tens cap comanda activa");
        }
        echo '</div>';
    }
}


function logged_in_gui($nick, $role)
{
    //echo '<a href="" class="self-center w-20 h-20 hover:">';
    img("user icon", "styles/user$role.png", "w-20 h-20 grayscale");
    //echo '</a>';
    $time = date("G", time());
    if ($time >= 20) {
        $welcomeUser = "Bona nit";
    } elseif ($time >= 13) {
        $welcomeUser = "Bona tarda";
    } else {
        $welcomeUser = "Bon dia";
    }
    p($welcomeUser . ", " . $nick, "text-center flex justify-center items-center w-full h-full");
    a('query/logout.php', 'Tanca Sessió', "text-center w-full hover:bg-blue-600"); //FIXME
}
function unlogged_gui()
{
    //echo '<a href="" class="w-20 h-20 hover:">';
    img("user icon", "styles/defaultUser.png", "w-20 h-20 grayscale");
    //echo '</a>';
    echo '<div class="flex flex-col text-center w-full">';
    a('signin.php', "Inicia Sessió", "");
    p("o bé", "text-sm");
    a('signup.php', "Registra't", "");
    echo '</div>';
}

function displayCategories()
{
    $query = "SELECT * FROM Categoria ORDER BY cat ASC";
    $genericClasses = "px-2";
    $buttonClasses = "absolute left-0 top-80 -rotate-90 origin-top-left text-center text-white bg-blue-700 px-2 rounded-b-lg hover:bg-blue-900 flex flex-row";
    $CategoryTagClasses = implode(" ", [
        "flex flex-wrap justify-between rounded-md shadow-2xl bg-black/70",
        "hidden",
        "overflow-scroll overflow-x-clip",
        "py-2 px-4 w-80 w-96 top-20 left-0",
    ]);
    $response = mysqli_query($GLOBALS["conn"], $query);
    echo "<button id=\"dropdownButton\" data-dropdown-placement=\"right-start\" data-dropdown-offset-distance=\"0\" data-dropdown-toggle=\"dropdownDelay\" data-dropdown-trigger=\"hover\" class=\"$buttonClasses\" type=\"button\">";
    h1("Categories");
    echo '<svg class=" right-1 w-2.5 h-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>';
    echo '</button>';
    echo '<ul id="dropdownDelay" class="' . $CategoryTagClasses . '">';
    while ($category = mysqli_fetch_array($response)) {
        echo '<div class="w-fit cursor-pointer rounded-full circle bg-gray-400/40 hover:bg-gray-700/70 p-2 m-2 flex flex-row text-center justify-between items-center">';
        label($category["cat"], $category["cat"], $genericClasses);
        input("checkbox", $GLOBALS['categoriesId'] . "[]", $genericClasses, $category["cat"]);
        echo '</div>';
    }
    echo '</ul>';
}

function addNavSearchBar()
{
    echo '<form class="flex px-4 w-full" action="/Marcazon">';
    input('search', $GLOBALS['searchId'], 'p-4 placeholder-gray-400 text-sm text-white w-full bg-cyan-800 border-none dark:text-white', "", "Cerca productes...");
    echo '<button class="flex p-4 bg-blue-800 hover:bg-blue-600 rounded-md" type="submit">';
    /*echo '<svg class="" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
</svg>';*/
    img("search icon", "styles/search.svg", "w-5 h-5");
    echo '</button>';
    displayCategories();
    echo '</form>';
}

function applyMainNavBar($isIndex)
{

    $mainClasses = "px-2 flex flex-row bg-gradient-to-r from-cyan-500 to-blue-500 items-center justify-between shadow-2xl text-white h-20";
    echo '<nav role="navigation" ';
    applyClasses($mainClasses, '');
    echo '>';
    echo '<div class="flex flex-row items-center justify-stretch shrink-0">';
    has_session() ? logged_in_gui($_SESSION['user'], $_SESSION['role']) : unlogged_gui();
    echo '</div>';
    echo "<div class=\"flex flex-row h-full w-full p-2 items-center justify-end\">";
    if ($isIndex) {
        addNavSearchBar();
    }
    if (has_session()) {
        if ($isIndex) {
            $linkClasses = "px-2 flex items-stretch rounded-full justify-stretch hover:bg-blue-600";
            $role = $_SESSION['role'];
            $srcImg = 'styles/';
            switch ($role) {
                case 'Comprador':
                    echo "<a href=\"basket.php\" class=\"$linkClasses\">";
                    img("carret de compra", "styles/basket.svg", "h-10 w-10");
                    echo "</a>";
                    break;
                case 'Venedor':
                    $srcImg .= "price-tag.svg";
                    echo "<a href=\"addProduct.php\" class=\"$linkClasses\">";
                    img($role . ' options', $srcImg, "h-14 w-10");
                    echo "</a>";
                    break;
                case 'Controlador':
                    $srcImg .= "price-tag.svg";
                    echo "<a href=\"addProduct.php\" class=\"$linkClasses\">";
                    img($role . ' options', $srcImg, "h-14 w-10");
                    echo "</a>";
                    break;
                default:
                    die(":(");
            }
            $srcImg = 'styles/config.svg';
            echo "<a href=\"options.php\" class=\"$linkClasses\">";
            img('config', $srcImg, "h-10 w-10");
            echo "</a>";
        } else {
            a("/Marcazon", "➥", "flex justify-center items-center text-center h-full text-5xl");
        }
    }
    echo "</div>";
    echo '</nav>';
}

function displayBasket($comanda, $estatComprant)
{
    $query = "SELECT COUNT(item.idItem) AS qtt,Comanda.dataModif AS 'darreraModif', Comanda.estatC AS estat FROM Item JOIN Comanda ON Comanda.idCom = item.idCom WHERE item.idCom=$comanda";
    if ($comandaItems = mysqli_fetch_array(mysqli_query($GLOBALS["conn"], $query))) {
        echo '<div class="flex flex-col text-white py-8 w-full justify-start items-center">';
        p($comandaItems["estat"],"text-2xl");
        echo '<div class="flex flex-row justify-between p-4 items-center overflow-visible w-1/4 text-2xl">';
        p('Darrera data de modifiació:');
        echo '<input type="date" name="darrera-modif" disabled class="bg-inherit disabled border-0" value="' . $comandaItems['darreraModif'] . '">';
        echo '</div>';
        if ($comandaItems['qtt'] == 0) {
            p("No tens cap producte");
        } else {
            $cost = 0;
            $query = "SELECT Item.idStock AS idProd, Venedor.nom AS venedor,Stock.nomProd AS nomProducte,COUNT(Item.idItem) AS qtt,Stock.preu AS preu,Producte.pathImg AS pathImg FROM Item JOIN stock ON Item.idStock = Stock.idStock JOIN Venedor ON Venedor.idVen = Stock.propietari JOIN Producte ON Producte.nomProd=Stock.nomProd WHERE item.idCom = $comanda GROUP BY stock.idStock";
            $result = mysqli_query($GLOBALS["conn"], $query);
            while ($itemDetails = mysqli_fetch_array($result)) {
                $cost += $itemDetails['qtt'] * $itemDetails['preu'];
                echo '<div class="flex flex-col my-4 p-4 w-96 border fit bg-black/40">';
                echo '<div class="flex flex-row justify-between items-center">';
                displayProductImage($itemDetails['nomProducte'], $itemDetails['pathImg'], "w-20 h-20");
                p($itemDetails['nomProducte'], "text-2xl fit px-4");
                echo '<div class="flex flex-col justify-around h-16">';
                echo '<div class="flex flex-row justify-between w-32">';
                p("Quantitat:");
                p($itemDetails['qtt']);
                echo '</div>';
                echo '<div class="flex flex-row justify-between w-32">';
                p("Preu(€/ud):");
                p($itemDetails['preu']);
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="flex flex-row justify-between">';
                p("Venedor: " . $itemDetails['venedor'], "text-sm self-center fit");
                if ($estatComprant) {
                    echo '<div class="flex flex-row justify-between w-32">';
                    echo '<form method="POST" action="modifyComanda.php">';
                    input("hidden", "idProd", "", $itemDetails['idProd']);
                    echo '<button type="submit" class="text-sm p-2 rounded-md bg-green-600 hover:bg-green-800">';
                    echo 'Editar';
                    echo '</button>';
                    echo '</form>';
                    echo '<form method="POST" action="query/modifyProducte.php">';
                    input("hidden", "amount", "", "0");
                    input("hidden", "idProd", "", $itemDetails['idProd']);
                    echo '<button type="submit" class="text-sm p-2 rounded-md bg-red-600 hover:bg-red-800">';
                    echo 'Eliminar';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '<hr class="w-1/2 my-8 bg-white-200 border-1">';
            echo '<div class = "text-3xl flex w-80 justify-between items-center">';
            p("COST TOTAL:");
            p($cost . "€");
            echo '</div>';
        }
        echo '</div>';
    }
}

function addDomicili()
{
    $pobles = [];
    $genericClasses = "bg-gray-700 p-1 my-2";
    $poblesTagClasses = $genericClasses . " rounded-md p-1 w-48";
    $buttonClasses = "bg-blue-700 hover:bg-blue-900 rounded-md p-2 my-2";
    $formClasses = "flex flex-col px-2 justify-center items-center text-md text-white";
    $optClasses = "";
    $query = "SELECT nomPoble,codiPostal FROM Poblacio";
    $result = mysqli_query($GLOBALS['conn'], $query);
    while ($poble = mysqli_fetch_array($result)) {
        $pobles[$poble["nomPoble"]] = $poble["codiPostal"];
    }
    h1("Afegeix un Domicili", "text-xl ml-4");
    echo '<form action="query/addDomicili.php" method="POST" class="' . $formClasses . '">';
    input("text", "carrer", $genericClasses, '', "Carrer del Domicili");
    input("number", "numCasa", $genericClasses, '', "NºCasa");
    input("number", "numPis", $genericClasses, '', "NºPis");
    select($pobles, "poble", $poblesTagClasses, $optClasses, true);
    echo '<button type="submit" class="' . $buttonClasses . '">';
    echo 'Afegeix Domicili';
    echo '</button>';
    echo '</form>';
}

function displayDomicilis()
{
    try {
        $query = "SELECT COUNT(idDomicili) AS qttDom FROM Domicili WHERE propietari=" . $_SESSION['userid'] . ";";
        $qttDom = mysqli_fetch_array(mysqli_query($GLOBALS['conn'], $query))['qttDom'];
        if ($qttDom != 0) {
            $query = "SELECT idDomicili,carrer,numPis,numCasa,nomPoble FROM Domicili JOIN Poblacio ON Poblacio.codiPostal=Domicili.codiPostal WHERE propietari=" . $_SESSION['userid'] . ";";
            $response = mysqli_query($GLOBALS['conn'], $query);
            h1("Els teus domicilis", "text-xl ml-4");
            $liClasses = "p-2 rounded-md flex flex-row w-92 justify-between items-center hover:bg-gray-900";
            echo '<ul class="ml-4 rounded-md flex flex-col w-92 h-80 bg-gray-800 overflow-y-scroll">';
            while ($domicili = mysqli_fetch_array($response)) {
                echo '<form method="POST" action="query/deleteDomicili.php" class="' . $liClasses . '">';
                p($domicili['carrer'] . ", " . $domicili['numCasa'] . ", " . $domicili['numPis'] . ", " . $domicili['nomPoble']);
                input("hidden","idDomicili","",$domicili["idDomicili"]);
                echo '<button type="submit" class="p-2 rounded-md bg-red-500 hover:bg-red-700">';
                echo 'Eliminar';
                echo '</button>';
                echo '</form>';
            }
            echo '</ul>';
        } else {
            p("Cap Domicili, de moment!", "ml-4");
        }
    } catch (Exception $ex) {
        p("Hi ha hagut un problema mostrant els domicilis: ");
        p($ex->getMessage());
    }
}

function modifyPersonalAccount()
{
    $labelClasses = "text-lg fit";
    $formClasses = "flex flex-col justify-start items-start";
    $buttonClasses = "self-end bg-green-700 p-2 rounded-md hover:bg-green-800";
    $genericClasses = "w-50 text-lg my-2 bg-gray-800";
    $fullname = empty($_SESSION['name']) ? "none" : $_SESSION['name'];
    $nickname = $_SESSION["user"];
    $pwd = $_SESSION["pwd"];
    echo '<form action="query/modifyuser.php" method="POST" class="' . $formClasses . '">';
    label("Nom: " . $fullname, "name", $labelClasses);
    input("text", "name", $genericClasses, "", "Nou Nom");
    label("Nickname: " . $nickname, "nickname", $labelClasses);
    input("text", "nickname", $genericClasses, "", "nou nickname", true);
    label("Contrasenya: " . $pwd, "pwd", $labelClasses);
    input("password", "pwd", $genericClasses, "", "Nova Contrasenya", true);
    echo '<button class="' . $buttonClasses . '">';
    echo 'Aplica els canvis';
    echo '</button>';

    echo '</form>';
}

function listAllBaskets()
{
    $query = sprintf("SELECT * FROM Comanda WHERE Comanda.idCompr=%d AND Comanda.estatC!='Comprant' ORDER BY dataModif DESC;", $_SESSION['userid']);
    if ($result = mysqli_query($GLOBALS['conn'], $query)) {
        $comandes = [];
        while ($comanda = mysqli_fetch_array($result)) {
            $comandes[$comanda["idCom"]] = $comanda['dataModif'] . " Estat: " . $comanda["estatC"];
        }
        h1("HISTORIAL DE COMANDES");
        createFormList($comandes, "comanda", "POST", "basket.php", " text-lg bg-gray-700 rounded-md overflow-y-scroll h-56", "p-2 my-2 hover:bg-gray-800 rounded-md");
    }
}
?>