<?php
include_once("../config/config.php");
function login($nick, $pwd, $role): bool
{
    switch($role){
        case 'Venedor':
            $attribute = "idVen";    
            break;
        case'Comprador':
            $attribute = "idCompr";
            break;
        case 'Controlador':
            $attribute = "idContr";
            break;
        default:
            $attribute = "err";
    }
    $query = "select $attribute AS id,nom from " . $role . " WHERE pwd='" . $pwd . "' AND nickname='" . $nick . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($ans=mysqli_fetch_array($result)) {
        session_start();
        $_SESSION["userid"] = $ans["id"];
        $_SESSION["name"] = $ans["name"];
        $_SESSION['user'] = $nick;
        $_SESSION['pwd'] = $pwd;
        $_SESSION['role'] = $role;
        return true;
    }
    return false;
}

function existUserType($nickname, $role)
{
    $query = "select nickname from $role where nickname='$nickname';";
    $answ = mysqli_query($GLOBALS["conn"], $query);
    return mysqli_fetch_array($answ) != null;
}

function logout(): bool
{
    session_start();
    unset($_SESSION["userid"]);
    unset($_SESSION['user']);
    unset($_SESSION['pwd']);
    unset($_SESSION['role']);
    unset($_SESSION['name']);
    return session_destroy();
}
?>