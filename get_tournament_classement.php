
<?php
/* require_once 'lib/pdo.php';
require_once 'App/Classements.php';

// Récupération des paramètres
$tournamentName = isset($_GET['tournamentName']) ? $_GET['tournamentName'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

if (!$tournamentName || !$competitionId) {
    echo '<p class="text-muted">Paramètres manquants.</p>';
    exit;
}

try {
    $classements = new App\Classements\Classements($db, $competitionId);
    $classementData = $classements->getClassements();
    
    // Filtrer les classements pour ne garder que ceux correspondant au nom de la coupe
    $tournamentClassements = array_filter($classementData, function($classement) use ($tournamentName) {
        return $classement['name'] === $tournamentName;
    });
    
    if (empty($tournamentClassements)) {
        echo '<p class="text-muted">Aucun classement disponible pour cette coupe.</p>';
        exit;
    }
    
    echo '<div class="classement-list">';
    foreach ($tournamentClassements as $classement) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        
        // Ajouter le nom de la phase si disponible
        if (!empty($classement['name'])) {
            echo '<h5 class="card-title">' . ($classement['name']) . '</h5>';
        }
        
        if ($classement['classement_pdf_url']) {
            echo '<a href="' . ($classement['classement_pdf_url']) . '" 
                      class="btn btn-second" target="_blank">
                     <i class="fas fa-table"></i> Voir le classement
                  </a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    
} catch (Exception $e) {
    echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
} */