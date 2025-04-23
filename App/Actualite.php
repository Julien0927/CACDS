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

    public function getLatestActualite($limit = 1) {
        $stmt = $this->db->prepare("SELECT * FROM actualite ORDER BY date_publication DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function displayActuGenerale($actualite) {
        if (!$actualite) return;
    
        echo '<div class="card actu-generale">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($actualite['titre']) . '</h5>';
        echo '<p class="card-text">' . htmlspecialchars(substr($actualite['contenu'], 0, 100)) . '...</p>';
        echo '<a href="actualites.php?id=' . $actualite['id'] . '" class="btn btn-primary">Lire</a>';
        echo '</div>';
        echo '</div>';
    }
    
    
}
