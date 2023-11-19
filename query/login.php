<?php
include_once("logic/logInOut.php");
if (!empty($_GET)) {
    $nickname = $_GET['nickname'];
    $pwd = $_GET['pwd'];
    $query = "INSERT INTO";
    $query .= ' ' . $_GET["role"];
    $_GET['role'] != 'Venedor' ? $query .= '(nom, nickname, pwd) VALUES(' . $_GET["name"] . ',' . $_GET["nickname"] . ',' . $_GET["pwd"] . ');' :
        $query .= '(nom, nickname, pwd, estatVen) VALUES(' . $_GET["name"] . ',' . $_GET["nickname"] . ',' . $_GET["pwd"] . ',"BO");';
    if (exist($nickname) && mysqli_query($GLOBALS["conn"], $query)) {
        login($nickname, $pwd);
        header('location: /main.php');
        exit;
    } else {
        die(":( WHAT DID YOU DO");
    }
} else {
    exit;
}
?>