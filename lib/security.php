<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session uniquement si elle n'est pas active
}

// Fonction pour générer un jeton CSRF
function generateCSRFToken() {
    try {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
} catch (Exception $e) {
    error_log('Erreur lors dela génération du token CSRF : ' . $e->getMessage());
}
}

// Fonction pour ajouter un champ CSRF à un formulaire
function addCSRFTokenToForm() {
    generateCSRFToken();
    if (!empty($_SESSION['csrf_token'])){
    echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    }else {
        echo "Une erreur est survenue. Veuillez réessayer plus tard.";
    }
}
// Fonction pour vérifier le jeton CSRF
function verifyCSRFToken() {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        return true;
    }
    return false;
}

// Vérifier automatiquement le jeton CSRF pour chaque formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken()) {
        die('Tentative d\'attaque CSRF détectée.');
    }
}

//Définition d'un gestionnaire d'erreur global
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	echo "Nous sommes désolés, un problème vient de survenir :/ \nNous vous invitons à revenir plus tard." . PHP_EOL;
	if ($errno === E_WARNING)
		exit();
});
restore_error_handler();

//Définition d'un gestionnaire d'exception global
set_exception_handler(function(Exception $e){
	echo 'Une exception a été détectée. Nous mettons fin au programme.' .$e->getMessage() . PHP_EOL;
		exit();
});
restore_exception_handler();

// Fonction pour nettoyer les entrées utilisateur
function sanitizeUserInput($input) {
    if (is_string($input)) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    return $input;
}
// Nettoyer toutes les données POST
foreach ($_POST as $key => $value) {
    $_POST[$key] = sanitizeUserInput($value);
}

// Nettoyer toutes les données GET (si nécessaire)
foreach ($_GET as $key => $value) {
    $_GET[$key] = sanitizeUserInput($value);
}
