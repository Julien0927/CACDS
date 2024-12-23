<?php
session_start();
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session active

// Redirige vers la page principale avec la navigation classique
header("Location: index.php");
exit;
