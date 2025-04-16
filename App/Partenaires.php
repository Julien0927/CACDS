<?php

namespace App\Partenaires;

use PDO;
use PDOException;

class Partenaires {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function addPartenaire($logo_path, $url) {
        if (!$logo_path || !$url) {
            throw new \Exception("Le logo ou l'URL ne peuvent pas être vides.");
        }
        $stmt = $this->db->prepare("INSERT INTO partenaires (logo, url) VALUES (?, ?)");
        return $stmt->execute([$logo_path, $url]);
    }

    public function getAllPartenaires() {
        $stmt = $this->db->query("SELECT * FROM partenaires ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function deletePartenaire($id) {
        $stmt = $this->db->prepare("DELETE FROM partenaires WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
