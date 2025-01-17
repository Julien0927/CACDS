/
<?php
/* require_once 'lib/pdo.php';
require_once 'App/Results.php';

// Récupération des paramètres
$tournamentName = isset($_GET['tournamentName']) ? $_GET['tournamentName'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

if (!$tournamentName || !$competitionId) {
    echo '<p class="text-muted">Paramètres manquants.</p>';
    exit;
}

try {
    $results = new App\Results\Results($db, $competitionId);
    $results->setTournamentName($tournamentName);
    $resultData = $results->getResults();
        
    if (empty($resultData)) {
        echo '<p class="text-muted">Aucun résultat disponible pour ce tournoi.</p>';
        exit;
    }
    
    echo '<div class="results-list">';
    foreach ($resultData as $result) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($result['name']) . '</h5>';
                
        if ($result['result_pdf_url']) {
            echo '<a href="' . htmlspecialchars($result['result_pdf_url']) . '" 
                      class="btn btn-original" target="_blank">
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