<?php 
namespace App\Classements;

use PDO;
use PDOException;
use InvalidArgumentException;

class Classements {
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
    
/* public function getClassements() {
    try {
        $query = "
            SELECT 
                cb.*,
                c.name AS competition_name,
                c.type AS competition_type
            FROM classementbad cb
            INNER JOIN competitions c ON cb.competitions_id = c.id;

        ";
        $params = [];
        
        if ($this->competitionId || $this->pouleId) {
            $query .= " WHERE 1=1";
            
            if ($this->competitionId) {
                $query .= " AND cb.competitions_id = :competitions_id";
                $params['competitions_id'] = $this->competitionId;
            }
            
            if ($this->pouleId) {
                $query .= " AND cb.poule_id = :poule_id";
                $params['poule_id'] = $this->pouleId;
            }
        }
        
        $query .= " ORDER BY cb.id, cb.day_number";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération des classements : " . $e->getMessage());
    }
}
 */
public function getClassements() {
    try {
        $query = "
            SELECT 
                cb.*,
                c.name AS competition_name,
                c.type AS competition_type
            FROM classementbad cb
            INNER JOIN competitions c ON cb.competitions_id = c.id
            WHERE 1=1
        ";
        $params = [];
        
        // Filtrer par competition_id si spécifié
        if ($this->competitionId) {
            $query .= " AND cb.competitions_id = :competitions_id";
            $params[':competitions_id'] = $this->competitionId;
        }
        
        // Filtrer par poule_id si spécifié
        if ($this->pouleId) {
            $query .= " AND cb.poule_id = :poule_id";
            $params[':poule_id'] = $this->pouleId;
        }
        
        // Ordonner différemment selon le type de compétition
        $query .= "
        ORDER BY 
            c.id, 
            COALESCE(cb.day_number, 0), 
            cb.name";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération du classement : " . $e->getMessage());
    }
}
// Dans la méthode addResult(), modifiez la vérification du type :
public function addClassement($dayNumber = NULL, $pdfUrl, $name = NULL) {
    if ($this->competitionId === null) {
        throw new InvalidArgumentException("Competition ID est requis pour ajouter un classement");
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
            INSERT INTO classementbad (poule_id, competitions_id, day_number, classement_pdf_url, name)
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
        throw new \Exception("Erreur lors de l'ajout du classement : " . $e->getMessage());
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

    public function displayClassement() {
        try {
            $classement = $this->getClassement();
            $output = '';
            
            if ($classement) {
                foreach ($classements as $classement) {
                    $output .= "<div class='clas$classement-item'>";
                    $output .= "<p>Journée " . htmlspecialchars($classement['day_number']) . ":</p>";
                    $output .= "<a href='" . htmlspecialchars($classement['classement_pdf_url']) . "' target='_blank'>Voir le classement</a>";
                    $output .= "</div>";
                }
            } else {
                $output = "<p>Aucun classsement disponible.</p>";
            }
            
            return $output;
        } catch (\Exception $e) {
            return "<p class='error'>Erreur lors de l'affichage des classements : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    public function deleteClassement() :void {
        $sql = "DELETE FROM classementbad WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }
}