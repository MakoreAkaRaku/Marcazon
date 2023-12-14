<?php
include_once("components/reusable.php");
include_once("config/config.php");
if (!has_session() || $_SESSION['role'] != "Venedor") {
    header('location: /Marcazon/');
    exit;
}
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="bg-gray-900 antialiased">
    <?php
    applyMainNavBar(false);
    displayProducts();
    ?>
</body>

</html>