<?php 
require_once('lib/config_session.php');

// Obtenir le nom de la page courante
$current_page = basename($_SERVER['PHP_SELF']);

$menuToShow = $menu;

$dashboardPages = [
    '1' => ['url' => 'dashboardTdT.php', 'name' => 'Tennis de Table', 'sport_id' => 1],
    '2' => ['url' => 'dashboardBad.php', 'name' => 'Badminton', 'sport_id' => 2],
    '3' => ['url' => 'dashboardPetanque.php', 'name' => 'Pétanque', 'sport_id' => 3],
    '4' => ['url' => 'dashboardVolley.php', 'name' => 'Volley-Ball', 'sport_id' => 4],
    '5' => ['url' => 'dashboardAdmin.php', 'name' => 'Messages', 'sport_id' => null],
];

?>

<nav class="navbar navbar-expand-lg ms-auto py-2">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" aria-label="Nav"></a>
        <button class="custom navbar-toggler" style="border-color:rgb(255, 255, 255);" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" ></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav link-offset-3">
                <!-- Menu principal -->
                <?php foreach ($menuToShow as $url => $label): ?>
                    <?php if (!isset($_SESSION['user']) || $url !== 'login.php'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === $url) ? 'active' : '' ?>" href="<?= htmlspecialchars($url) ?>">
                                <?php if ($url === 'login.php'): ?>
                                    <?= $label; ?>
                                    <?php else: ?>
                                        <?=($label); ?>
                                        <?php endif; ?>
                                    </a>
                                </li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Gestion des dashboards -->
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if (isset($_SESSION['is_super_admin']) && $_SESSION['is_super_admin']): ?>
                        <!-- Lien direct pour le super admin -->
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === 'dashboardAdmin.php') ? 'active' : '' ?>" 
                               href="dashboardAdmin.php">
                               Dashboard
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Dashboard unique pour utilisateur normal -->
                        <?php 
                        $userSport = $_SESSION['sport_id'] ?? null;
                        if ($userSport && isset($dashboardPages[$userSport])):
                        ?>
                            <li class="nav-item">
                                <a class="nav-link <?= ($current_page === $dashboardPages[$userSport]['url']) ? 'active' : '' ?>" 
                                href="<?= ($dashboardPages[$userSport]['url']) ?>">
                                Mon Dashboard
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- Bouton de déconnexion -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item mb-2">
                                <a class="btn btnLogout me-2 <?= ($current_page === 'logout.php') ? 'active' : '' ?>" href="logout.php">
                                    Se déconnecter
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
        </div>
    </div>
</nav>
