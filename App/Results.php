<?php 
namespace App\Results;

use PDO;
use PDOException;
use InvalidArgumentException;

class Results {
    private $db;
    private $competitionId;
    private $pouleId;
    
    public function __construct($db, $competitionId = null, $pouleId = null) {
        $this->db = $db;
        $this->competitionId = $competitionId;
        $this->pouleId = $pouleId;
    }

    public function setId($id): void {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("ID invalide");
        }
        $this->id = (int)$id;
    }
    
public function addResult($dayNumber = NULL, $pdfUrl, $name = NULL) {
    if ($this->competitionId === null) {
        throw new InvalidArgumentException("Competition ID est requis pour ajouter un résultat");
    }
    
    try {
        // Vérifie le type de compétition
        $stmt = $this->db->prepare("SELECT type FROM competitions WHERE id = :id");
        $stmt->execute(['id' => $this->competitionId]);
        $competition = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Pour les coupes et tournois, force poule_id et day_number à NULL
        if ($competition && in_array($competition['type'], ['Coupe', 'Tournoi'])) {
            $this->pouleId = null;
            $dayNumber = null;
            
            // Vérifie que le nom est fourni pour les coupes et tournois
            if (empty($name)) {
                throw new InvalidArgumentException("Le nom est requis pour les coupes et tournois");
            }
        } else {
            // Pour le championnat, le nom est optionnel
            $name = null;
        }
        
        $query = $this->db->prepare("
            INSERT INTO journees (poule_id, competitions_id, day_number, result_pdf_url, name)
            VALUES (:poule_id, :competitions_id, :day_number, :pdf_url, :name)
        ");
        
        return $query->execute([
            'poule_id' => $this->pouleId,
            'competitions_id' => $this->competitionId,
            'day_number' => $dayNumber,
            'pdf_url' => $pdfUrl,
            'name' => $name
        ]);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de l'ajout du résultat : " . $e->getMessage());
    }
}

public function setCupName($name): void {
    if (empty($name)) {
        throw new InvalidArgumentException("Le nom de la coupe est requis");
    }
    $this->cupName = $name;
}
public function setTournamentName($name): void {
    if (empty($name)) {
        throw new InvalidArgumentException("Le nom de la coupe est requis");
    }
    $this->cupName = $name;
}


public function getResults() {
    try {
        $query = "
            SELECT 
                j.*,
                c.name as competition_name,
                c.type as competition_type
            FROM journees j
            INNER JOIN competitions c ON j.competitions_id = c.id
        ";
        $params = [];
        
        if ($this->competitionId || $this->pouleId) {
            $query .= " WHERE 1=1";
            
            if ($this->competitionId) {
                $query .= " AND j.competitions_id = :competitions_id";
                $params['competitions_id'] = $this->competitionId;
            }
            
            if ($this->pouleId) {
                $query .= " AND j.poule_id = :poule_id";
                $params['poule_id'] = $this->pouleId;
            }

            if (isset($this->cupName)) {
                $query .= " AND j.name = :cup_name"; // Filtrage par le nom de la coupe
                $params['cup_name'] = $this->cupName;
            }
        }
        $query .= " ORDER BY c.id, COALESCE(j.day_number, 0), j.name";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération des résultats : " . $e->getMessage());
    }
}
  

public function getCompetitions() {
    try {
        $stmt = $this->db->query("SELECT id, name, type FROM competitions ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération des compétitions : " . $e->getMessage());
    }
}

    public function displayResults() {
        try {
            $results = $this->getResults();
            $output = '';
            
            if ($results) {
                foreach ($results as $result) {
                    $output .= "<div class='result-item'>";
                    $output .= "<p>Journée " . htmlspecialchars($result['day_number']) . ":</p>";
                    $output .= "<a href='" . htmlspecialchars($result['result_pdf_url']) . "' target='_blank'>Voir le résultat</a>";
                    $output .= "</div>";
                }
            } else {
                $output = "<p>Aucun résultat disponible.</p>";
            }
            
            return $output;
        } catch (\Exception $e) {
            return "<p class='error'>Erreur lors de l'affichage des résultats : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    public function deleteResult() :void {
        $sql = "DELETE FROM journees WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }

public function getCupNames() {
    try {
        $stmt = $this->db->prepare("
            SELECT DISTINCT j.name 
            FROM journees j
            INNER JOIN competitions c ON j.competitions_id = c.id
            WHERE c.type = 'Coupe'
            ORDER BY j.name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération des noms de coupes : " . $e->getMessage());
    }
}

public function getTournamentNames() {
    try {
        $stmt = $this->db->prepare("
            SELECT DISTINCT j.name 
            FROM journees j
            INNER JOIN competitions c ON j.competitions_id = c.id
            WHERE c.type = 'Tournoi'
            ORDER BY j.name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération des noms de coupes : " . $e->getMessage());
    }
}
}
