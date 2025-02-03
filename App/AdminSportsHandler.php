<?php
namespace App\AdminSportsHandler;

use PDO;

class AdminSportsHandler {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les sports disponibles
     */
    public function getAllSports(): array {
        $stmt = $this->db->prepare("SELECT * FROM sports ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Configure temporairement un sport pour le super_admin
     */
    public function setTemporarySport(int $sportId): bool {
        if (!isset($_SESSION['is_super_admin']) || !$_SESSION['is_super_admin']) {
            return false;
        }

        // Vérifie si le sport existe
        $stmt = $this->db->prepare("SELECT id FROM sports WHERE id = ?");
        $stmt->execute([$sportId]);
        if (!$stmt->fetch()) {
            return false;
        }

        // Stocke le sport temporairement pour le super_admin
        $_SESSION['temp_sport_id'] = $sportId;
        return true;
    }

    /**
     * Récupère le sport_id actuel (temporaire ou permanent)
     */
    public function getCurrentSportId(): ?int {
        if (isset($_SESSION['is_super_admin']) && $_SESSION['is_super_admin']) {
            return $_SESSION['temp_sport_id'] ?? null;
        }
        return $_SESSION['sport_id'] ?? null;
    }

    /**
     * Efface le sport temporaire
     */
    public function clearTemporarySport(): void {
        if (isset($_SESSION['temp_sport_id'])) {
            unset($_SESSION['temp_sport_id']);
        }
    }
}