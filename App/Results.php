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
    
    public function getResults() {
        try {
            $query = "
                SELECT j.*, c.name as competition_name, c.type as competition_type 
                FROM journees j
                LEFT JOIN competitions c ON j.competitions_id = c.id
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
            }
            
            $query .= " ORDER BY j.day_number";
            
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
}