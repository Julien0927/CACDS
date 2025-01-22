<?php 
namespace App\Classements;

use PDO;
use PDOException;
use InvalidArgumentException;

class Classements {
    private $db;
    private $competitionId;
    private $pouleId;
    private $sportId;
    private $id;
    private $cupName;
    private $tournamentName;
    
    public function __construct($db, $competitionId = null, $pouleId = null, $sportId = null) {
        $this->db = $db;
        $this->competitionId = $competitionId;
        $this->pouleId = $pouleId;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->setSportId($sportId);
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

    public function getClassements() {
        try {
            $query = "
                SELECT 
                    cb.*,
                    c.name AS competition_name,
                    c.type AS competition_type,
                    cb.day_number
                FROM classementbad cb
                INNER JOIN competitions c ON cb.competitions_id = c.id
                WHERE cb.sport_id = :sport_id
            ";
            $params = ['sport_id' => $this->sportId];
            
            if ($this->competitionId) {
                $query .= " AND cb.competitions_id = :competitions_id";
                $params['competitions_id'] = $this->competitionId;
            }
            
            if ($this->pouleId) {
                $query .= " AND cb.poule_id = :poule_id";
                $params['poule_id'] = $this->pouleId;
            }

            if (isset($this->cupName)) {
                $query .= " AND cb.name = :name";
                $params['name'] = $this->cupName;
            }
            
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

    public function addClassement($dayNumber = NULL, $pdfUrl, $name = NULL) { 
           
        if ($dayNumber !== null && !is_numeric($dayNumber)) {
            throw new InvalidArgumentException("Le numéro de journée (day_number) doit être un nombre ou nul.");
        }
        if ($this->competitionId === null) {
            throw new InvalidArgumentException("Competition ID est requis pour ajouter un classement");
        }
    
        try {
            $stmt = $this->db->prepare("SELECT type FROM competitions WHERE id = :id");
            $stmt->execute(['id' => $this->competitionId]);
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
            
            $query = $this->db->prepare("
                INSERT INTO classementbad (poule_id, competitions_id, day_number, classement_pdf_url, name, sport_id)
                VALUES (:poule_id, :competitions_id, :day_number, :pdf_url, :name, :sport_id)
            ");
            
            return $query->execute([
                'poule_id' => $this->pouleId,
                'competitions_id' => $this->competitionId,
                'day_number' => $dayNumber,
                'pdf_url' => $pdfUrl,
                'name' => $name,
                'sport_id' => $this->sportId
            ]);
            
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout du classement : " . $e->getMessage());
        }
    }

    public function displayClassement() {
        try {
            $classements = $this->getClassements(); // Correction de la variable
            $output = '';
            
            if ($classements) { // Correction de la variable
                foreach ($classements as $classement) {
                    $output .= "<div class='classement-item'>"; // Correction de la chaîne
                    $output .= "<p>Journée " . htmlspecialchars($classement['day_number']) . ":</p>";
                    $output .= "<a href='" . htmlspecialchars($classement['classement_pdf_url']) . "' target='_blank'>Voir le classement</a>";
                    $output .= "</div>";
                }
            } else {
                $output = "<p>Aucun classement disponible.</p>"; // Correction de l'orthographe
            }
            
            return $output;
        } catch (\Exception $e) {
            return "<p class='error'>Erreur lors de l'affichage des classements : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    public function deleteClassement(): void {
        if (!isset($this->id)) {
            throw new InvalidArgumentException("ID non défini pour la suppression");
        }
        
        try {
            $sql = "DELETE FROM classementbad WHERE id = :id";
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $this->id, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la suppression du classement : " . $e->getMessage());
        }
    }

    public function getCupNames() {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT name 
                FROM classementbad 
                WHERE competitions_id = 2 
                AND sport_id = :sport_id 
                AND name IS NOT NULL
                ORDER BY name
            ");
            $stmt->execute(['sport_id' => $this->sportId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des noms de coupes : " . $e->getMessage());
        }
    }

    public function getTournamentNames() {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT name 
                FROM classementbad 
                WHERE competitions_id = 3 
                AND sport_id = :sport_id 
                AND name IS NOT NULL
                ORDER BY name
            ");
            $stmt->execute(['sport_id' => $this->sportId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des noms de tournois : " . $e->getMessage());
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
            throw new InvalidArgumentException("Le nom du tournoi est requis");
        }
        $this->tournamentName = $name;
    }
}
