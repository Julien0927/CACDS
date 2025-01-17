<?php
/* require_once 'lib/pdo.php';
require_once 'App/Results.php';

// Vérification des paramètres
$pouleId = isset($_GET['poule_id']) ? (int)$_GET['poule_id'] : null;
$competitionId = isset($_GET['competition_id']) ? (int)$_GET['competition_id'] : null;

if (!$pouleId || !$competitionId) {
    echo "Aucun résultat disponible";
    exit;
}

try {
    $results = new App\Results\Results($db, $competitionId, $pouleId);
    $resultData = $results->getResults();
    
    if (empty($resultData)) {
        echo '<p class="text-muted">Aucun résultat disponible pour cette poule.</p>';
        exit;
    }
    
    echo '<div class="results-list">';
    foreach ($resultData as $result) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        if ($result['day_number']) {
            echo '<h5 class="card-title">Journée ' . htmlspecialchars($result['day_number']) . '</h5>';
        }
        
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
 */?>
<?php
require_once 'lib/pdo.php';
require_once 'App/Results.php';

class ResultsDisplay {
    private $results;
    private $type;
    private $params;
    private $db;

    public function __construct($db, array $params) {
        $this->db = $db;
        $this->params = $params;
        $this->validateParams();
        $this->setupResultsObject();
    }

    private function validateParams() {
        if (!isset($this->params['competition_id']) || !(int)$this->params['competition_id']) {
            throw new Exception('ID de compétition manquant ou invalide');
        }

        // Détermination du type basé sur les paramètres
        if (isset($this->params['poule_id'])) {
            $this->type = 'poule';
        } elseif (isset($this->params['cupName'])) {
            $this->type = 'cup';
        } elseif (isset($this->params['tournamentName'])) {
            $this->type = 'tournament';
        } else {
            throw new Exception('Type de résultat non spécifié');
        }
    }

    private function setupResultsObject() {
        switch ($this->type) {
            case 'poule':
                // Création directe avec poule_id
                $this->results = new App\Results\Results(
                    $this->db, 
                    $this->params['competition_id'],
                    $this->params['poule_id']
                );
                break;
            case 'cup':
                $this->results = new App\Results\Results($this->db, $this->params['competition_id']);
                $this->results->setCupName($this->params['cupName']);
                break;
            case 'tournament':
                $this->results = new App\Results\Results($this->db, $this->params['competition_id']);
                $this->results->setTournamentName($this->params['tournamentName']);
                break;
        }
    }

    public function render() {
        try {
            $resultData = $this->results->getResults();

            if (empty($resultData)) {
                $message = match($this->type) {
                    'poule' => 'cette poule',
                    'cup' => 'cette coupe',
                    'tournament' => 'ce tournoi'
                };
                echo '<p class="text-muted">Aucun résultat disponible pour ' . $message . '.</p>';
                return;
            }

            echo '<div class="results-list">';
            foreach ($resultData as $result) {
                $this->renderResultCard($result);
            }
            echo '</div>';

        } catch (Exception $e) {
            echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }

    private function renderResultCard($result) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        
        // Titre de la carte
        if ($this->type === 'poule' && isset($result['day_number'])) {
            echo '<h5 class="card-title">Journée ' . htmlspecialchars($result['day_number']) . '</h5>';
        } else {
            echo '<h5 class="card-title">' . htmlspecialchars($result['name']) . '</h5>';
        }

        // Lien PDF
        if (!empty($result['result_pdf_url'])) {
            $icon = $this->type === 'tournament' ? 'fa-table' : 'fa-file-pdf';
            $text = $this->type === 'tournament' ? 'Voir le classement' : 'Voir les résultats';
            
            echo '<a href="' . htmlspecialchars($result['result_pdf_url']) . '" 
                      class="btn btn-original" target="_blank">
                     <i class="fas ' . $icon . '"></i> ' . $text . '
                  </a>';
        }

        echo '</div>';
        echo '</div>';
    }
}

// Utilisation
try {
    $display = new ResultsDisplay($db, $_GET);
    $display->render();
} catch (Exception $e) {
    echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>