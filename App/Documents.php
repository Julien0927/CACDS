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
    public function addDocument($categorie, $titre, $filePath)
    {
        // Insertion du nouveau document
        $sql = "INSERT INTO documents (categorie, titre, fichier) VALUES (:categorie, :titre, :fichier)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':fichier', $filePath);
        return $stmt->execute();
    }

    // Méthode pour supprimer un document par ID
    public function deleteDocument($id)
    {
        $sql = "DELETE FROM documents WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Méthode pour récupérer les documents par catégorie
    public function getDocumentsByCategory($categorie)
    {
        $sql = "SELECT * FROM documents WHERE categorie = :categorie ORDER BY titre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
