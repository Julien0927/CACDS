<!-- <nav class="navbar navbar-expand-lg ms-auto py-2">
    <div class="container-fluid ">
        <a class="navbar-brand" href="#"></a>
        <button class="custom navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav link-offset-3">
                  <li class="nav-item">
                    <a class="nav-link" href="index.php">CACDS</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="badminton.php">Badminton</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="volley.php">Volley-Ball</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="tennisDT.php">Tennis de table</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="petanque.php">Pétanque</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="login.php"><img src="/assets/icones/cadenas-24.png" class="mb-2" alt=""></a>
                  </li>
                </ul>
        </div>
     </div>
  </nav> -->
  <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('lib/config_session.php'); // Charger les menus

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
                            <a class="nav-link" href="<?= htmlspecialchars($url) ?>">
                                <?php if ($url === 'login.php'): ?>
                                    <?= $label; // Affiche directement l'icône si c'est login.php ?>
                                <?php else: ?>
                                    <?= htmlspecialchars($label); // Sinon, échappe le texte ?>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>                <!-- Bouton de déconnexion -->
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="btn btnLogout me-2" href="logout.php">Se déconnecter</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
