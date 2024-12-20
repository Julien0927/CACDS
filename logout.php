<?php   
session_start();
if(!isset($_SESSION["user"])){
    header("Location: index.php");
    exit;
}

//Supprime une variable de session
unset($_SESSION["user"]);

header("Location: index.php");