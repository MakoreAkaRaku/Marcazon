<?php
include_once("components/reusable.php");
include_once("config/config.php");
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="<?php custom_bg() ?>">
    <?php
    applyMainNavBar();
    displayProducts();
    ?>
</body>

</html>