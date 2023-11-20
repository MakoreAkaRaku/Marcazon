<?php
include_once("../logic/logInOut.php");
if (!empty($_POST)) {
    $nickname = $_POST['nickname'];
    $pwd = $_POST['pwd'];
    foreach ($GLOBALS["roles"] as $role) {
        if (existUserType($nickname, $role)) {
            if (login($nickname, $pwd, $role)) {
                header('location: /Marcazon/main.php');
                exit;
            } else {
                die("Wrong password or nickname");
            }
        }
    }
    mysqli_error($GLOBALS["conn"]);
    die("This user does not exist in any loggable group :(");
} else {
    mysqli_error($GLOBALS["conn"]);
    die("Trying to log in without using signin? >:(");
}
?>