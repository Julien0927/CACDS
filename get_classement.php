<?php
require_once 'lib/pdo.php';
require_once 'App/Classements.php';

// Vérification des paramètres
$pouleId = isset($_GET['poule_id']) ? (int)$_GET['poule_id'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

if (!$pouleId || !$competitionId) {
    echo "Aucun classement disponible";
    exit;
}

try {
    $classements = new App\Classements\Classements($db, $competitionId, $pouleId);
    $classementData = $classements->getClassements();
    
    if (empty($classementData)) {
        echo '<p class="text-muted">Aucun classement disponible pour cette poule.</p>';
        exit;
    }
    
    echo '<div class="classement-list">';
    foreach ($classementData as $classement) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        if ($classement['day_number']) {
            echo '<h5 class="card-title">Journée ' . htmlspecialchars($classement['day_number']) . '</h5>';
        } else {
            echo '<h5 class="card-title">Classement général</h5>';
        }
        
        if ($classement['classement_pdf_url']) {
            echo '<a href="' . htmlspecialchars($classement['classement_pdf_url']) . '" 
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
}
?>
