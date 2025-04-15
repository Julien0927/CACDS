<?php

namespace App\Documents;

use PDO;
use Exception;

class Documents
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour ajouter un document
    public function addDocument($categorie, $filePath)
    {
        // Vérifier si un document de la même catégorie existe déjà
        $sql = "SELECT COUNT(*) FROM documents WHERE categorie = :categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Si un document existe déjà pour cette catégorie, le supprimer
        if ($count > 0) {
            $this->deleteDocument($categorie);
        }

        // Insertion du nouveau document
        $sql = "INSERT INTO documents (categorie, fichier) VALUES (:categorie, :fichier)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':fichier', $filePath);
        return $stmt->execute();
    }

    // Méthode pour supprimer un document par catégorie
    public function deleteDocument($categorie)
    {
        $sql = "DELETE FROM documents WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $categorie);
        return $stmt->execute();
    }

    // Méthode pour récupérer les documents par catégorie
    public function getDocumentsByCategory($categorie)
    {
        $sql = "SELECT * FROM documents WHERE categorie = :categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
