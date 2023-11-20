<?php
include_once("../config/config.php");
function login($nick, $pwd, $role): bool
{
    include_once("../config/config.php");
    $query = "select nom from " . $role . " WHERE pwd='" . $pwd . "' AND nickname='" . $nick . "'";
    if (mysqli_query($GLOBALS['conn'], $query)) {
        session_start();
        $_SESSION['user'] = $nick;
        $_SESSION['pwd'] = $pwd;
        $_SESSION['role'] = $role;
        return true;
    }
    return false;
}

function existUserType($nickname, $role)
{
    include_once('../config/config.php');
    $query = "select nickname from " . $role . " where nickname='" . $nickname . "';";
    $answ = mysqli_query($GLOBALS["conn"], $query);
    return mysqli_fetch_array($answ) != null;
}

function redirectIfSessionAlive()
{
    if (!empty($_SESSION)) {
        header('location: /Marcazon/main.php');
        exit;
    }
}
function logout(): bool
{
    include_once("../config/config.php");
    session_start();
    unset($_SESSION['user']);
    unset($_SESSION['pwd']);
    unset($_SESSION['role']);
    return session_destroy();
}
?>