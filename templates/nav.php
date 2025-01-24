<?php 
require_once('lib/config_session.php');

// Obtenir le nom de la page courante
$current_page = basename($_SERVER['PHP_SELF']);

// Charger les menus
// Initialisation du menu à afficher 
$menuToShow = $menu;

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $userSport = $_SESSION['user']['sport_id'] ?? null;
    
    // Ajouter le lien vers le dashboard spécifique si un sport est défini
    if ($userSport) {
        $dashboardPages = [
            '1' => 'dashboardTdT.php',
            '2' => 'dashboardBad.php',
            '3' => 'dashboardPetanque.php',
            '4' => 'dashboardVolley.php',
        ];
        
        // Ajouter le dashboard au menu, s'il existe pour cet utilisateur
        if (isset($dashboardPages[$userSport])) {
            $menuToShow = [
                $dashboardPages[$userSport] => 'Mon Dashboard',
            ] + $menu;
        }
    }
}
?>

<nav class="navbar navbar-expand-lg ms-auto py-2">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"></a>
        <button class="custom navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav link-offset-3">
                <!-- Génération dynamique du menu -->
                <?php foreach ($menuToShow as $url => $label): ?>
                    <?php if (!isset($_SESSION['user']) || $url !== 'login.php'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === $url) ? 'active' : '' ?>" href="<?= htmlspecialchars($url) ?>">
                                <?php if ($url === 'login.php'): ?>
                                    <?= $label; // Affiche directement l'icône si c'est login.php ?>
                                <?php else: ?>
                                    <?= htmlspecialchars($label); // Sinon, échappe le texte ?>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <!-- Bouton de déconnexion -->
            </ul>
            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item mb-2" style="list-style-type: none;">
                    <a class="btn btnLogout me-2 <?= ($current_page === 'logout.php') ? 'active' : '' ?>" href="logout.php">Se déconnecter</a>
                </li>
            <?php endif; ?>
        </div>
    </div>
</nav>
