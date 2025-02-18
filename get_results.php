<?php
require_once 'lib/pdo.php';
require_once 'App/Results.php';

// Récupération des paramètres
$pouleId = isset($_GET['poule_id']) ? (int)$_GET['poule_id'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

// Définition d'une map des URLs vers les sport_id
$sportUrlMap = [
    'tennisDT.php' => 1,
    'badminton.php' => 2,
    'petanque.php' => 3,
    'volley.php' => 4
];

// Détermination du sport_id basée sur l'URL appelante
$referer = isset($_SERVER['HTTP_REFERER']) ? basename(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH)) : null;
$sportId = isset($sportUrlMap[$referer]) ? $sportUrlMap[$referer] : null;

// Si pas de referer ou sport non trouvé, essayer la session
if (!$sportId && isset($_SESSION['sport_id'])) {
    $sportId = (int)$_SESSION['sport_id'];
}

// Si toujours pas de sport_id, renvoyer une erreur
if (!$sportId) {
    http_response_code(400);
    echo '<p class="text-danger">Sport non identifié</p>';
    exit;
}

try {
    // Création de l'instance Results avec le sport_id déterminé
    $results = new App\Results\Results($db, $competitionId, $pouleId, $sportId);

    if (isset($_GET['cupName']) && !empty($_GET['cupName'])) {
        $results->setCupName($_GET['cupName']);
    }

    if (isset($_GET['tournamentName']) && !empty($_GET['tournamentName'])) {
        $results->setTournamentName($_GET['tournamentName']);
    }

    $resultData = $results->getResults();
    
    if (empty($resultData)) {
        echo '<p style="color: #EC930F">Aucun résultat disponible.</p>';
        exit;
    }
    
    echo '<div class="results-list">';
    foreach ($resultData as $result) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        
        // Affichage du titre selon le type de résultat
        if ($result['day_number']) {
            echo '<h5 class="card-title">Journée ' . ($result['day_number']) . '</h5>';
        } elseif ($result['name']) {
            echo '<h5 class="card-title">' . ($result['name']) . '</h5>';
        }
        
        // Affichage du lien PDF s'il existe
        if ($result['result_pdf_url']) {
            // Adaptation des icônes et textes selon le type de compétition et le sport
            $icon = ($result['competition_type'] === 'Tournoi') ? 'fa-table' : 'fa-file-pdf';
            $text = ($result['competition_type'] === 'Tournoi') ? 'Voir le classement' : 'Voir les résultats';
            
            echo '<a href="' . ($result['result_pdf_url']) . '" 
                     class="btn btn-original bold" target="_blank">
                     <i class="fas ' . $icon . '"></i> ' . $text . '
                  </a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    
} catch (Exception $e) {
    http_response_code(500);
    echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
}