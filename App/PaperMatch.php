<?php 
namespace App\PaperMatch;

use PDO;
use PDOException;
use InvalidArgumentException;

class PaperMatch {
    private PDO $db;
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
            $this->sportId = (int)$sportId;
        } else if (isset($this->adminSportsHandler)) {
            $this->sportId = $this->adminSportsHandler->getCurrentSportId() ?? 2;
        } else if (isset($_SESSION['sport_id'])) {
            $this->sportId = (int)$_SESSION['sport_id'];
        } else {
            $this->sportId = 2;
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

public function getPaperMatch() {
    try {
        $query = "
            SELECT pm.*, c.name AS competition_name, c.type AS competition_type, pm.day_number
            FROM papermatch pm
            INNER JOIN competitions c ON pm.competitions_id = c.id
            WHERE pm.sport_id = :sport_id";
        
            $params = ['sport_id' => $this->sportId];
        
        if ($this->competitionId) {
            $query .= " AND pm.competitions_id = :competitions_id";
            $params['competitions_id'] = $this->competitionId;
        }
        if ($this->pouleId) {
            $query .= " AND pm.poule_id = :poule_id";
            $params['poule_id'] = $this->pouleId;
        }
        if (isset($this->cupName)) {
            $query .= " AND pm.name = :name";
        }
        
        $query .= " ORDER BY c.id, COALESCE(pm.day_number, 0), pm.name";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':sport_id', $this->sportId, PDO::PARAM_INT);
        
        if ($this->competitionId) {
            $stmt->bindValue(':competitions_id', $this->competitionId, PDO::PARAM_INT);
        }
        if ($this->pouleId) {
            $stmt->bindValue(':poule_id', $this->pouleId, PDO::PARAM_INT);
        }
        if (isset($this->cupName)) {
            $stmt->bindValue(':name', $this->cupName, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Erreur lors de la récupération du classement : " . $e->getMessage());
    }
}

/*  public function addPaperMatch($pdfUrl, $dayNumber = NULL, $name = NULL) {
    if ($dayNumber !== null && !is_numeric($dayNumber)) {
        throw new InvalidArgumentException("Le numéro de journée doit être un nombre ou nul.");
    }
    if ($this->competitionId === null) {
        throw new InvalidArgumentException("Competition ID est requis pour ajouter une feuille de match");
    }
    
    try {
        // Supprime d'abord l'ancien classement s'il existe, toujours filtré par poule
        $deleteQuery = "DELETE FROM papermatch 
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
        
        // Insère le nouveau classement
        $query = $this->db->prepare(
            "INSERT INTO papermatch (poule_id, competitions_id, day_number, fm_pdf_url, name, sport_id) 
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
        throw new \Exception("Erreur lors de l'ajout du classement : " . $e->getMessage());
    }
}
 */ 
public function addPaperMatch($pdfUrl, $dayNumber = NULL, $name = NULL) {
    if ($dayNumber !== null && !is_numeric($dayNumber)) {
        throw new InvalidArgumentException("Le numéro de journée doit être un nombre ou nul.");
    }
    if ($this->competitionId === null) {
        throw new InvalidArgumentException("Competition ID est requis pour ajouter une feuille de match");
    }

    try {
        // Suppression de la feuille de match de la journée précédente (si elle existe)
        if ($dayNumber !== null) {
            $deleteQuery = "DELETE FROM papermatch 
                            WHERE competitions_id = :competitions_id 
                            AND sport_id = :sport_id 
                            AND poule_id = :poule_id 
                            AND day_number = :day_number";
            $params = [
                ':competitions_id' => $this->competitionId,
                ':sport_id' => $this->sportId,
                ':poule_id' => $this->pouleId,
                ':day_number' => $dayNumber - 1 // Suppression de la journée précédente
            ];
            $stmt = $this->db->prepare($deleteQuery);
            $stmt->execute($params);
        }

        // Supprime d'abord l'ancien classement s'il existe, toujours filtré par poule
        $deleteQuery = "DELETE FROM papermatch 
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

        // Insère le nouveau classement
        $query = $this->db->prepare(
            "INSERT INTO papermatch (poule_id, competitions_id, day_number, fm_pdf_url, name, sport_id) 
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
        throw new \Exception("Erreur lors de l'ajout du classement : " . $e->getMessage());
    }
}

     public function displayPaperMatch() {
        try {
            $paperMatches = $this->getPaperMatch(); // Correction de la variable
            $output = '';
            
            if ($paperMatch) { // Correction de la variable
                foreach ($paperMatches as $paperMatch) {
                    $output .= "<div class='paperMatch-item'>"; // Correction de la chaîne
                    $output .= "<p>Journée " . htmlspecialchars($paperMatch['day_number']) . ":</p>";
                    $output .= "<a href='" . htmlspecialchars($paperMatch['fm_pdf_url']) . "' target='_blank'>Voir le classement</a>";
                    $output .= "</div>";
                }
            } else {
                $output = "<p>Aucune feuille de match disponible.</p>"; // Correction de l'orthographe
            }
            
            return $output;
        } catch (\Exception $e) {
            return "<p class='error'>Erreur lors de l'affichage des paperMatch : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    public function deletePaperMatch(): void {
        if (!isset($this->id)) {
            throw new InvalidArgumentException("Sélection non définie pour la suppression");
        }
        
        try {
            $query = $this->db->prepare("DELETE FROM papermatch WHERE id = :id");
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
