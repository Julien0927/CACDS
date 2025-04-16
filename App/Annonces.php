<?php

namespace App\Annonces;

use PDO;
use PDOException;

class Annonces {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllAnnonces() {
        $stmt = $this->db->prepare("SELECT * FROM annonces ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAnnonceById($id) {
        $stmt = $this->db->prepare("SELECT * FROM annonces WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addAnnonce($titre, $texte, $image_path) {
        $stmt = $this->db->prepare("INSERT INTO annonces (titre, texte, image_path) VALUES (?, ?, ?)");
        return $stmt->execute([$titre, $texte, $image_path]);
    }

    public function updateAnnonce($id, $titre, $texte, $image_path = null) {
        if ($image_path) {
            $stmt = $this->db->prepare("UPDATE annonces SET titre = ?, texte = ?, image_path = ? WHERE id = ?");
            return $stmt->execute([$titre, $texte, $image_path, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE annonces SET titre = ?, texte = ? WHERE id = ?");
            return $stmt->execute([$titre, $texte, $id]);
        }
    }

    public function deleteAnnonce($id) {
        $stmt = $this->db->prepare("DELETE FROM annonces WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAnnoncesLimite(int $limite, int $offset): array {
        $stmt = $this->db->prepare("SELECT * FROM annonces ORDER BY created_at DESC LIMIT :limite OFFSET :offset");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countAnnonces(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM annonces");
        return (int) $stmt->fetchColumn();
    }
    
}
