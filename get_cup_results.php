<?php
require_once 'lib/pdo.php';
require_once 'App/Results.php';

// Récupération des paramètres
$cupName = isset($_GET['cupName']) ? $_GET['cupName'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

if (!$cupName || !$competitionId) {
    echo "Aucun résultat disponible";
    exit;
}

try {
    $results = new App\Results\Results($db, $competitionId);
    $results->setCupName($cupName);
    $resultData = $results->getResults();
    
    if (empty($resultData)) {
        echo '<p class="text-muted">Aucun résultat disponible pour cette coupe.</p>';
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
                  <i class="fas fa-file-pdf"></i> Voir les résultats
                  </a>';
        }
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    
} catch (Exception $e) {
    echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>