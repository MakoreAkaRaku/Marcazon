<?php
include_once("components/reusable.php");
include_once("config/config.php");
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="bg-gray-900 antialiased">
    <?php
    applyMainNavBar(true);
    empty($_POST["idProd"]) ?  "" : modifyProducteComanda($_POST["idProd"]);
    ?>
</body>

</html>