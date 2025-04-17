<?php

namespace App\Actualite;

use PDO;

class Actualite {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ajouter une actualité
    public function addActualite($titre, $contenu) {
        $stmt = $this->db->prepare("INSERT INTO actualite (titre, contenu) VALUES (?, ?)");
        return $stmt->execute([$titre, $contenu]);
    }

    // Récupérer toutes les actualités
    public function getAllActualites() {
        $stmt = $this->db->query("SELECT * FROM actualite ORDER BY date_publication DESC");
        return $stmt->fetchAll();
    }

    // Supprimer une actualité
    public function deleteActualite($id) {
        $stmt = $this->db->prepare("DELETE FROM actualite WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
