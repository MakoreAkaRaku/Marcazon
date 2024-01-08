<?php
include_once("connectServer.php");
$GLOBALS["roles"] = ['Venedor'=>'Venedor', 'Comprador'=>'Comprador', 'Controlador'=>'Controlador'];
$GLOBALS['searchId'] = "prod-search";
$GLOBALS['categoriesId'] = "categories";
$GLOBALS['prodUri'] = "prodimg/";
session_start();
?>