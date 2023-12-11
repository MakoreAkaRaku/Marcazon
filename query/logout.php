<?php
include_once("../logic/logInOut.php");
if (!empty($_SESSION)) {
    //If we did not sign out correctly, handle the error.
    if (!logout()) {
        die("Something is of... did you login correctly..?");
    }
}
header('location: /Marcazon/index.php');
exit;
?>