<?php
include_once("../logic/logInOut.php");
if (!empty($_POST)) {
    $name = $_POST["name"];
    $nickname = $_POST['nickname'];
    $pwd = $_POST['pwd'];
    $role = $_POST['role'];
    $query = "INSERT INTO ";
    $query .= $role . ' ';
    $role != 'Venedor' ? $query .= '(nom, nickname, pwd) VALUES("' . $name . '","' . $nickname . '","' . $pwd . '");' :
        $query .= '(nom, nickname, pwd, estatVen) VALUES("' . $name . '","' . $nickname . '","' . $pwd . '","BO");';
    if (!existUserType($nickname, $role) and mysqli_query($GLOBALS["conn"], $query)) {
        login($nickname, $pwd, $role);
        header('location: /Marcazon/main.php');
        exit;
    } else {
        mysqli_error($GLOBALS["conn"]);
        die("Oops, looks like this nickname is already taken");
    }
} else {
    mysqli_error($GLOBALS["conn"]);
    die("Did you try to come here without registering to the page? >:(");
}
?>