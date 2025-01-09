<?php
namespace App\Results;

use PDO;
use PDOException;


class Results {
    private $db;  // Instance de la connexion à la base de données
    private $competitionId;
    private $pouleId;
    
    // Constructeur
    public function __construct($db, $competitionId, $pouleId) {
        $this->db = $db;
        $this->competitionId = $competitionId;
        $this->pouleId = $pouleId;
    }

    // Ajouter un résultat pour une poule et une journée
    public function addResult($dayNumber, $pdfUrl) {
        $query = $this->db->prepare("
            INSERT INTO journees (poule_id, competitions_id, day_number, result_pdf_url)
            VALUES (:poule_id, :competitions_id, :day_number, :pdf_url)
        ");
        $query->execute([
            'poule_id' => $this->pouleId,
            'competitions_id' => $this->competitionId,
            'day_number' => $dayNumber,
            'pdf_url' => $pdfUrl
        ]);
    }

    // Récupérer tous les résultats d'une poule et d'une compétition
    public function getResults() {
        $query = $this->db->prepare("
            SELECT * FROM journees 
            WHERE poule_id = :poule_id
            AND competitions_id = :competitions_id
            ORDER BY day_number
        ");
        $query->execute([
            'poule_id' => $this->pouleId,
            'competitions_id' => $this->competitionId
        ]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
        var_dump (getResults());
    }

    // Afficher les résultats (liens vers les fichiers PDF)
    public function displayResults() {
        $results = $this->getResults();
        if ($results) {
            foreach ($results as $result) {
                echo "<div class='result-item'>";
                echo "<p>Journée " . $result['day_number'] . ":</p>";
                echo "<a href='" . $result['result_pdf_url'] . "' target='_blank'>Voir le résultat</a>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucun résultat disponible pour cette poule.</p>";
        }
    }
    }
?>
