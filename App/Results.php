<?php 

namespace App\Results;

use PDO;
use PDOException;
use InvalidArgumentException;

class Results {
    private $db;
    private $competitionId;
    private $pouleId;
    private $sportId;
    private $id;
    private $cupName = null;
    private $tournamentName = null;
    
    public function __construct($db, $competitionId = null, $pouleId = null, $sportId = null, $cupName = null, $tournamentName = null) {
        $this->db = $db;
        $this->competitionId = $competitionId;
        $this->pouleId = $pouleId;
        $this->setSportId($sportId);
        if ($cupName !== null) {
            $this->setCupName($cupName);
        }
        if ($tournamentName !== null) {
            $this->setTournamentName($tournamentName);
        }
    }

    /**
     * Définit le sport_id avec gestion des priorités
     * @param int|null $sportId
     */
    private function setSportId($sportId = null): void {
        if ($sportId !== null) {
            // Priorité 1: Utilise le sport_id passé en paramètre
            $this->sportId = (int)$sportId;
        } else if (isset($_SESSION['sport_id'])) {
            // Priorité 2: Utilise le sport_id de la session
            $this->sportId = (int)$_SESSION['sport_id'];
        } else {
            // Priorité 3: Utilise la valeur par défaut pour le badminton
            $this->sportId = 2;
        }

        if ($this->sportId <= 0) {
            throw new InvalidArgumentException("Sport ID invalide");
        }
    }

    /**
     * Permet de changer le sport_id après l'instanciation
     * @param int $sportId
     */
    public function changeSportId(int $sportId): void {
        if ($sportId <= 0) {
            throw new InvalidArgumentException("Sport ID invalide");
        }
        $this->sportId = $sportId;
    }

    /**
     * Récupère le sport_id actuel
     * @return int
     */
    public function getCurrentSportId(): int {
        return $this->sportId;
    }

    public function setId($id): void {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("ID invalide");
        }
        $this->id = (int)$id;
    }
    
    /* public function addResult($pdfUrl, $dayNumber = NULL, $name = NULL) {
        if ($this->competitionId === null) {
            throw new InvalidArgumentException("Competition ID est requis pour ajouter un résultat");
        }
        
        try {
            $stmt = $this->db->prepare("SELECT type FROM competitions WHERE id = :id");
            $stmt->bindValue(':id', $this->competitionId, PDO::PARAM_INT);
            $stmt->execute();
            $competition = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($competition && in_array($competition['type'], ['Coupe', 'Tournoi'])) {
                $this->pouleId = null;
                $dayNumber = null;
                
                if (empty($name)) {
                    throw new InvalidArgumentException("Le nom est requis pour les coupes et tournois");
                }
            } else {
                $name = null;
            }
            
            $query = $this->db->prepare(
                "INSERT INTO journees (poule_id, competitions_id, day_number, result_pdf_url, name, sport_id)
                VALUES (:poule_id, :competitions_id, :day_number, :pdf_url, :name, :sport_id)"
            );
            
            $query->bindValue(':poule_id', $this->pouleId, PDO::PARAM_INT);
            $query->bindValue(':competitions_id', $this->competitionId, PDO::PARAM_INT);
            $query->bindValue(':day_number', $dayNumber, PDO::PARAM_INT);
            $query->bindValue(':pdf_url', $pdfUrl, PDO::PARAM_STR);
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->bindValue(':sport_id', $this->sportId, PDO::PARAM_INT);
            
            return $query->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout du résultat : " . $e->getMessage());
        }
    }  */

    public function addResult($pdfUrl, $dayNumber = NULL, $name = NULL) {
        if ($this->competitionId === null) {
            throw new InvalidArgumentException("Competition ID est requis pour ajouter un résultat");
        }
        
        try {
            // Supprime d'abord l'ancien résultat s'il existe, toujours filtré par poule
            $deleteQuery = "DELETE FROM journees 
                           WHERE competitions_id = :competitions_id 
                           AND sport_id = :sport_id 
                           AND poule_id = :poule_id";
            $params = [
                ':competitions_id' => $this->competitionId,
                ':sport_id' => $this->sportId,
                ':poule_id' => $this->pouleId
            ];
            
            // Ajoute la condition supplémentaire selon le type (name ou day_number)
            if ($name !== null) {
                $deleteQuery .= " AND name = :name";
                $params[':name'] = $name;
            } else {
                $deleteQuery .= " AND day_number = :day_number";
                $params[':day_number'] = $dayNumber;
            }
            
            $stmt = $this->db->prepare($deleteQuery);
            $stmt->execute($params);
            
            // Insère le nouveau résultat
            $query = $this->db->prepare(
                "INSERT INTO journees (poule_id, competitions_id, day_number, result_pdf_url, name, sport_id)
                 VALUES (:poule_id, :competitions_id, :day_number, :pdf_url, :name, :sport_id)"
            );
            
            $query->bindValue(':poule_id', $this->pouleId, PDO::PARAM_INT);
            $query->bindValue(':competitions_id', $this->competitionId, PDO::PARAM_INT);
            $query->bindValue(':day_number', $dayNumber, PDO::PARAM_INT);
            $query->bindValue(':pdf_url', $pdfUrl, PDO::PARAM_STR);
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->bindValue(':sport_id', $this->sportId, PDO::PARAM_INT);
            
            return $query->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout du résultat : " . $e->getMessage());
        }
    }     
    /* public function getResults() {
        try {
            $query = "
                SELECT 
                    j.*,
                    c.name as competition_name,
                    c.type as competition_type
                FROM journees j
                INNER JOIN competitions c ON j.competitions_id = c.id
                WHERE j.sport_id = :sport_id
            ";
            $params = ['sport_id' => $this->sportId];
            
            if ($this->competitionId) {
                $query .= " AND j.competitions_id = :competitions_id";
                $params['competitions_id'] = $this->competitionId;
            }
            
            if ($this->pouleId) {
                $query .= " AND j.poule_id = :poule_id";
                $params['poule_id'] = $this->pouleId;
            }

            if (isset($this->cupName)) {
                $query .= " AND j.name = :name";
                $params['name'] = $this->cupName;
            }
            
            $query .= " 
            ORDER BY 
                c.id, 
                COALESCE(j.day_number, 0), 
                j.name";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des résultats : " . $e->getMessage());
        }
    }
 */

 public function getResults() {
    try {
        $query = "
            SELECT 
                j.*,
                c.name as competition_name,
                c.type as competition_type
            FROM journees j
            INNER JOIN competitions c ON j.competitions_id = c.id
            WHERE j.sport_id = :sport_id
        ";
        $params = ['sport_id' => $this->sportId];
        
        if ($this->competitionId) {
            $query .= " AND j.competitions_id = :competitions_id";
            $params['competitions_id'] = $this->competitionId;
        }
        
        if ($this->pouleId) {
            $query .= " AND j.poule_id = :poule_id";
            $params['poule_id'] = $this->pouleId;
        }

        if ($this->cupName !== null) { // Changement de la condition
            $query .= " AND j.name = :name";
            $params['name'] = $this->cupName;
        }
        
        $query .= " 
        ORDER BY 
            c.id, 
            COALESCE(j.day_number, 0), 
            j.name";
        
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
                        $output .= "<p>Journée " . ($result['day_number']) . ":</p>";
                        $output .= "<a href='" . ($result['result_pdf_url']) . "' target='_blank'>Voir le résultat</a>";
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

        public function setCupName($name): void {
            if (empty($name)) {
                throw new InvalidArgumentException("Le nom de la coupe est requis");
            }
            $this->cupName = trim($name);
        }
        public function setTournamentName($name): void {
            if (empty($name)) {
                throw new InvalidArgumentException("Le nom de la coupe est requis");
            }
            $this->cupName = $name;
        }
        
    
 public function getCupNames() {
    try {
        $stmt = $this->db->prepare("
            SELECT DISTINCT j.name 
            FROM journees j
            INNER JOIN competitions c ON j.competitions_id = c.id
            WHERE c.type = 'Coupe' 
            AND j.sport_id = :sport_id     -- Ajout du paramètre nommé
            AND j.name IS NOT NULL         -- Déplacé après le sport_id
            ORDER BY j.name
        ");
        
        $stmt->execute([
            'sport_id' => $this->sportId
        ]);
        
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
            AND j.sport_id = :sport_id     -- Ajout du paramètre nommé
            AND j.name IS NOT NULL         -- Déplacé après le sport_id
            ORDER BY j.name
        ");
        
        $stmt->execute([
            'sport_id' => $this->sportId
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération des noms de coupes : " . $e->getMessage());
    }
}
    
}