<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

 define ('_IMG_PATH_', 'uploads/photos/');
 define ('_NEWS_IMG_PATH_', 'uploads/news/');
// Pages pour les administrateurs (ajoutées dynamiquement selon l'utilisateur connecté)
$admin = [
    'dashboardBad.php' => 'Dashboard Badminton',
    'dashboardVolley.php' => 'Dashboard Volley-Ball',
    'dashboardTdT.php' => 'Dashboard Tennis de Table',
    'dashboardPetanque.php' => 'Dashboard Pétanque',
];
  

$menu = [
    'index.php' => '<span><img src="/assets/icones/accueil-36.png" class="mb-2"></span>',
    'cacds.php' => 'CACDS',
    'badminton.php' => 'Badminton',
    'volley.php' => 'Volley-Ball',
    'tennisDT.php' => 'Tennis de Table',
    'petanque.php' => 'Pétanque',
    'contact.php' => 'Contact',
    'login.php' => '<span class="login-icon"><img src="/assets/icones/cadenas-24.png" class="mb-2" alt="Connexion"></span>',

];