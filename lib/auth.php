<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Récupère les informations de l'utilisateur connecté
$userSport = $_SESSION['user']['sport_id'] ?? null;

// Correspondance des sports avec les dashboards
$dashboardPages = [
    '1' => 'dashboardTdT.php',
    '2' => 'dashboardBad.php',
    '3' => 'dashboardPetanque.php',
    '4' => 'dashboardVolley.php',
];

// Redirige vers le bon dashboard si l'utilisateur n'est pas déjà dessus
if (isset($dashboardPages[$userSport]) && basename($_SERVER['PHP_SELF']) !== $dashboardPages[$userSport]) {
    header("Location: " . $dashboardPages[$userSport]);
    exit;
}
?>
