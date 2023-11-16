<?php
include_once("config.php");
$query = "INSERT INTO";
if(!empty($_GET));
switch($_GET["role"]){
    case 'venedor':
        $query.= 'venedor';
        break;
        case'controlador':
            break;
        case 'comprador':
            break;

}
$_GET['role'] == 'venedor' ? $query.= '(nom, nickname, pwd) VALUES('.$_GET["name"].','.$_GET["nickname"].','.$_GET["pwd"].');' : 
$query.= '(nom, nickname, pwd, estatVen) VALUES('.$_GET["name"].','.$_GET["nickname"].','.$_GET["pwd"].',"BO");';
if (mysqli_query($conn,$query)) {
    echo 'ALL GOOD!';
    exit;
}else {
    die(":( WHAT DID YOU DO");
}
?>