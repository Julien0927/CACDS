<?php
require_once('lib/pdo.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Vérification de l'adresse ip de l'utilisateur
$_SESSION['ip_adresse'] = $_SERVER['REMOTE_ADDR'];
if(isset($_SESSION['ip_adresse']) AND $_SESSION['ip_adresse'] !== $_SERVER['REMOTE_ADDR']){
    session_unset();
    session_destroy();
    header('location: login.php');
}

//Vérification du navigateur de l'utilisateur
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

if(isset($_SESSION['user_agent']) AND $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']){
    session_unset();
    session_destroy();
    header('location: login.php');
}


require_once('App/Users.php');

$errors = [];
$messages = [];

if (isset($_SESSION['user'])) {

    if (isset($_SESSION['user']) && $_SESSION['user'] === 'ROLE_ADMIN') {
        header('location: index.php');
        exit;
    }
} else {
    $errors[] = 'Email ou mot de passe incorrect';
}
?>