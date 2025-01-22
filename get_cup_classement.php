
 <?php
/* require_once 'lib/pdo.php';
require_once 'App/Classements.php';

// Récupération des paramètres
$cupName = isset($_GET['cupName']) ? $_GET['cupName'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

if (!$cupName || !$competitionId) {
    echo '<p class="text-muted">Paramètres manquants.</p>';
    exit;
}

try {
    $classements = new App\Classements\Classements($db, $competitionId);
    $classementData = $classements->getClassements();
    
    // Filtrer les classements pour ne garder que ceux correspondant au nom de la coupe
    $cupClassements = array_filter($classementData, function($classement) use ($cupName) {
        return $classement['name'] === $cupName;
    });
    
    if (empty($cupClassements)) {
        echo '<p class="text-muted">Aucun classement disponible pour cette coupe.</p>';
        exit;
    }
    
    echo '<div class="classement-list">';
    foreach ($cupClassements as $classement) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        
        // Ajouter le nom de la phase si disponible
        if (!empty($classement['name'])) {
            echo '<h5 class="card-title">' . htmlspecialchars($classement['name']) . '</h5>';
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
} */