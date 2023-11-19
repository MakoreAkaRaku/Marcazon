<?php
include_once("../logic/logInOut.php");
if (!empty($_POST)) {
    $name = $_POST["name"];
    $nickname = $_POST['nickname'];
    $pwd = $_POST['pwd'];
    $role = $_POST['role'];
    $query = "INSERT INTO ";
    $query .= $role.' ';
    $role != 'Venedor' ? $query .= '(nom, nickname, pwd) VALUES("' . $name . '","' . $nickname . '","' . $pwd . '");' :
        $query .= '(nom, nickname, pwd, estatVen) VALUES("' . $name . '","' . $nickname . '","' . $pwd . '","BO");';
    if (!exist($nickname,$role) && mysqli_query($GLOBALS["conn"], $query)) {
        login($nickname, $pwd, $role);
        header('location: /main.php');
        exit;
    } else {
        die(":( WHAT DID YOU DO");
    }
} else {
    exit;
}
?>