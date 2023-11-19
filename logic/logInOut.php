<?php
function login($nick, $pwd, $role): bool
{
    include_once("../config/config.php");
    $query = "select nom from " . $role . " WHERE pwd='" . $pwd . "' AND nickname='" . $nick . "'";
    if (mysqli_query($GLOBALS["conn"], $query)) {
        session_start();
        $_SESSION['user'] = $nick;
        $_SESSION['pwd'] = $pwd;
        $_SESSION['role'] = $role;
        return true;
    }

    return false;
}

function exist($nickname,$role)
{
    include_once('../config/config.php');
    $query="select nickname from ".$role." where nickname='".$nickname."';";
    return mysqli_query($GLOBALS["conn"], $query);
}
function logout($nick): bool
{
    include_once("../config/config.php");
    session_start();
    unset($_SESSION['user']);
    unset($_SESSION['role']);
    return session_destroy();
}
?>