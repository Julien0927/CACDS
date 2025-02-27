<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

 define ('_IMG_PATH_', 'uploads/photos/');
 define ('_NEWS_IMG_PATH_', 'uploads/news/');
 define ('_MEDIA_PATH_', 'uploads/media/');
// Pages pour les administrateurs (ajoutées dynamiquement selon l'utilisateur connecté)
$admin = [
    'dashboardBad.php' => 'Dashboard Badminton',
    'dashboardPetanque.php' => 'Dashboard Pétanque',
    'dashboardTdT.php' => 'Dashboard Tennis de Table',
    'dashboardVolley.php' => 'Dashboard Volley-Ball',
];
  

$menu = [
    'index.php' => '<span><img src="/assets/icones/accueil-36.png" alt="Accueil" class="mb-2"></span>',
    'cacds.php' => 'CACDS',
    'badminton.php' => 'Badminton',
    'petanque.php' => 'Pétanque',
    'tennisDT.php' => 'Tennis de Table',
    'volley.php' => 'Volley-Ball',
    'contact.php' => 'Contact',
    'login.php' => '<span class="login-icon"><img src="/assets/icones/cadenas-24.png" class="mb-2" alt="Connexion"></span>',

];