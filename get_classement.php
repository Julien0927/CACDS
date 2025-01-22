<?php
/* require_once 'lib/pdo.php';
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
 */

/* require_once 'lib/pdo.php';
require_once 'App/Classements.php';

session_start();
if (!isset($_SESSION['sport_id'])) {
    http_response_code(403);
    exit('Accès non autorisé');
}

$sportId = $_SESSION['sport_id'];

class ClassementHandler {
    private $classements;
    private $type;
    private $params;
    private $db;

    public function __construct($db, array $params) {
        $this->db = $db;
        $this->params = $params;
        $this->validateParams();
        $this->setupClassementsObject();
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
            throw new Exception('Type de classement non spécifié');
        }
    }

    private function setupClassementsObject() {
        switch ($this->type) {
            case 'poule':
                // Création directe avec poule_id
                $this->classements = new App\Classements\Classements(
                    $this->db, 
                    $this->params['competition_id'],
                    $this->params['poule_id']
                );
                break;
            case 'cup':
                $this->classements = new App\Classements\Classements($this->db, $this->params['competition_id']);
                $this->classements->setCupName($this->params['cupName']);
                break;
            case 'tournament':
                $this->classements = new App\Classements\Classements($this->db, $this->params['competition_id']);
                $this->classements->setTournamentName($this->params['tournamentName']);
                break;
        }
    }

    public function render() {
        try {
            $classementData = $this->classements->getClassements();

            if (empty($classementData)) {
                $message = match($this->type) {
                    'poule' => 'cette poule',
                    'cup' => 'cette coupe',
                    'tournament' => 'ce tournoi'
                };
                echo '<p class="text-muted">Aucun classement disponible pour ' . $message . '.</p>';
                return;
            }

            echo '<div class="classement-list">';
            foreach ($classementData as $classement) {
                $this->renderClassementCard($classement);
            }
            echo '</div>';

        } catch (Exception $e) {
            echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }

    private function renderClassementCard($classement) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        
        // Titre de la carte
        if ($this->type === 'poule' && isset($classement['day_number'])) {
            echo '<h5 class="card-title">Journée ' . htmlspecialchars($classement['day_number']) . '</h5>';
        } elseif (!empty($classement['name'])) {
            echo '<h5 class="card-title">' . htmlspecialchars($classement['name']) . '</h5>';
        } else {
            echo '<h5 class="card-title">Classement général</h5>';
        }

        // Lien PDF
        if (!empty($classement['classement_pdf_url'])) {
            $icon = $this->type === 'tournament' ? 'fa-table' : 'fa-file-pdf';
            echo '<a href="' . htmlspecialchars($classement['classement_pdf_url']) . '" 
                      class="btn btn-second" target="_blank">
                     <i class="fas ' . $icon . '"></i> Voir le classement
                  </a>';
        }

        echo '</div>';
        echo '</div>';
    }
}

// Utilisation
try {
    $handler = new ClassementHandler($db, $_GET);
    $handler->render();
} catch (Exception $e) {
    echo '<p class="text-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
}

 */
require_once 'lib/pdo.php';
require_once 'App/Classements.php';

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
    // Création de l'instance Classments avec le sport_id déterminé
    $classements = new App\Classements\Classements($db, $competitionId, $pouleId, $sportId);
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
        } elseif($classement['name']) {
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
}
    
