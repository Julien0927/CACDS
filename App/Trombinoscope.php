<?php
namespace App\Trombinoscope;

use PDO;

class Trombinoscope
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Ajouter un document
    public function addTrombinos($poule, $titre, $filePath)
    {
    
        // Ajouter le nouveau fichier
        $sql = "INSERT INTO trombinoscope (poule, titre, file_path) 
                VALUES ( :poule, :titre, :file_path)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':poule' => $poule,
            ':titre' => $titre,
            ':file_path' => $filePath
        ]);
    
        return $this->db->lastInsertId();
    }

    // Supprimer un document
    public function deleteTrombinos($id)
    {
        $sql = "DELETE FROM trombinoscope WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Récupérer tous les documents 
    public function getAllTrombinos($poule = null)
    {
        $sql = "SELECT * FROM trombinoscope WHERE 1=1";
        $params = [];

        if ($poule !== null) {
            $sql .= " AND poule = :poule";
            $params[':poule'] = $poule;
        }

        $sql .= " ORDER BY uploaded_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un document par ID
    public function getTrombinosById($id)
    {
        $sql = "SELECT * FROM trombinoscope WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un document
    public function updateTrombinos($id, $poule, $titre, $filePath)
    {
        $sql = "UPDATE trombinoscope 
                SET  poule = :poule, titre = :titre, file_path = :file_path 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':poule' => $poule,
            ':titre' => $titre,
            ':file_path' => $filePath
        ]);
    }
}
