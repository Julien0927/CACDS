<?php

namespace App\Photos;

use PDO;
use PDOException;
use InvalidArgumentException;

class Photos {
    private PDO $db;
    private ?int $id = null;
    private ?string $title = null;
    private ?string $date = null;
    private ?string $image = null;
    private ?int $sportId = null;
    private string $table = 'photos';

    public function __construct(PDO $db, ?int $sportId = null) {
        $this->db = $db;
        $this->setSportId($sportId);
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

    // Getters existants...
    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function getDate(): ?string {
        return $this->date;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    // Setters modifiés...
    public function setId(int $id): void {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID invalide");
        }
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        if (empty(trim($title))) {
            throw new InvalidArgumentException("Le titre ne peut pas être vide");
        }
        $this->title = htmlspecialchars($title);
    }

    public function setDate(string $date): void {
        if (!strtotime($date)) {
            throw new InvalidArgumentException("Format de date invalide");
        }
        $this->date = $date;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function addPhoto(): bool {
        try {
            if (!$this->title || !$this->date || !$this->image) {
                throw new InvalidArgumentException("Titre, date et image sont requis");
            }

            $query = "INSERT INTO photos (title, date, image, sport_id) VALUES (:title, :date, :image, :sport_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':image', $this->image, PDO::PARAM_STR);
            $stmt->bindValue(':sport_id', $this->sportId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout de la photo : " . $e->getMessage());
        }
    }

    public function getBySportId(?int $sportId = null): array {
        try {
            $sportIdToUse = $sportId ?? $this->sportId;
            $query = "SELECT * FROM {$this->table} WHERE sport_id = :sport_id ORDER BY date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':sport_id', $sportIdToUse, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des photos : " . $e->getMessage());
        }
    }

    public function getAllPhotos(): array {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY date DESC";
            return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération de toutes les photos : " . $e->getMessage());
        }
    }

    public function deletePhoto(): bool {
        try {
            if ($this->id === null) {
                throw new InvalidArgumentException("ID non défini pour la suppression");
            }
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la suppression de la photo : " . $e->getMessage());
        }
    }

    public function exists(int $id): bool {
        try {
            $query = "SELECT 1 FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la vérification de l'existence de la photo : " . $e->getMessage());
        }
    }
}