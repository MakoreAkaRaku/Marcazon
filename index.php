<?php
include_once("components/reusable.php");
include_once("config/config.php");
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="bg-gray-900 antialiased w-screen h-screen">
    <?php
    applyMainNavBar(true);
    empty($_GET["idProd"]) ? displayProducts(): displayProduct($_GET["idProd"]);
    ?>
</body>

</html>