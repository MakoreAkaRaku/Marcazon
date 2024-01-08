<?php
include_once("components/reusable.php");
include_once("config/config.php");
redirectIfSessionNotAlive();
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="bg-gray-900 antialiased w-screen h-screen">
    <?php
    applyMainNavBar(true);
    $genericClasses= "bg-black/40 shadow-xl rounded-lg text-white m-0 w-2/3 h-2/3 m-10 justify-start items-start";
    echo '<div class="flex flex-row w-full h-full items-start justify-start px-20 py-20">';
    echo '<div class="'.$genericClasses.'">';
    h1("Dades Personals","p-4");
    echo '<div class = "flex flex-col items-center justify-start">';
    echo '<div class="flex flex-row">';
    p("Categoria:","mr-4 text-xl");
    p($_SESSION['role'],"text-xl");
    echo '</div>';
    modifyPersonalAccount();
    echo '</div>';
    echo '</div>';
    echo '<div class="'.$genericClasses.'">';
    h1("Dades de Domicilis","p-4 text-white");
    echo '<div class="flex flex-row w-full justify-around items-start">';
    echo '<div class="w-1/2">';
    displayDomicilis();
    echo '</div>';
    echo '<div class="inline-block h-96 min-h-[1em] w-0.5 self-stretch bg-neutral-100 opacity-100 dark:opacity-50"></div>'; 
    echo '<div class="w-1/2">';
    addDomicili();
    echo '</div>'; 
    echo '</div>'; 
    echo '</div>';
    echo '</div>';
    ?>
</body>

</html>