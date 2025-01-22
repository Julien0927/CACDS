<?php
/* 
namespace App\Photos;

use PDO;
use PDOException;

class Photos {
    private PDO $db;
    private ?int $id = null;
    private ?string $title = null;
    private ?string $date = null;
    private ?string $image = null;
    private ?int $sportId = null;
    private string $table = 'photos';

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Getters avec types de retour
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

    public function getSportId(): ?int {
        return $this->sportId;
    }

    // Setters avec types de paramètres
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        $this->title = htmlspecialchars($title);
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function setSportId(int $sportId): void {
        $this->sportId = $sportId;
    }

    // Méthodes CRUD
    public function addPhoto(): bool {
        try {
            $query = "INSERT INTO {$this->table} (title, date, image, sport_id) 
                     VALUES (:title, :date, :image, :sport_id)";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([
                ':title' => $this->title,
                ':date' => $this->date,
                ':image' => $this->image,
                ':sport_id' => $this->sportId
            ]);
        } catch (PDOException $e) {
            // On pourrait logger l'erreur ici
            return false;
        }
    }

    public function getBySportId(int $sportId): array {
        try {
            $query = "SELECT * FROM {$this->table} 
                     WHERE sport_id = :sport_id 
                     ORDER BY date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':sport_id' => $sportId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllPhotos(): array {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY date DESC";
            return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function deletePhoto(): bool {
        try {
            if ($this->id === null) {
                return false;
            }
            
            $sql = "DELETE FROM photos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthode utilitaire
    public function exists(int $id): bool {
        try {
            $query = "SELECT 1 FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
} */

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
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->setSportId($sportId);
    }

    /**
     * Définit le sport_id avec gestion des priorités
     * @param int|null $sportId
     */
    private function setSportId(?int $sportId = null): void {
        if ($sportId !== null) {
            // Priorité 1: Utilise le sport_id passé en paramètre
            $this->sportId = $sportId;
        } else if (isset($_SESSION['sport_id'])) {
            // Priorité 2: Utilise le sport_id de la session
            $this->sportId = (int)$_SESSION['sport_id'];
        } else {
            // Priorité 3: Utilise la valeur par défaut pour le badminton
            $this->sportId = 2;
        }

        if ($this->sportId <= 0) {
            throw new InvalidArgumentException("Sport ID invalide");
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

    // Méthodes CRUD modifiées...
    public function addPhoto(): bool {
        try {
            if (!$this->title || !$this->date || !$this->image) {
                throw new InvalidArgumentException("Titre, date et image sont requis");
            }

            $query = "INSERT INTO photos (title, date, image, sport_id)
                      VALUES (:title, :date, :image, :sport_id)";
            
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([
                ':title' => $this->title,
                ':date' => $this->date,
                ':image' => $this->image,
                ':sport_id' => $this->sportId
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout de la photo : " . $e->getMessage());
        }
    }

    public function getBySportId(?int $sportId = null): array {
        try {
            // Si aucun sport_id n'est fourni, utilise celui de la classe
            $sportIdToUse = $sportId ?? $this->sportId;

            $query = "SELECT * FROM {$this->table}
                      WHERE sport_id = :sport_id
                      ORDER BY date DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([':sport_id' => $sportIdToUse]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des photos : " . $e->getMessage());
        }
    }

    // Autres méthodes existantes...
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
            return $stmt->execute([':id' => $this->id]);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la suppression de la photo : " . $e->getMessage());
        }
    }

    public function exists(int $id): bool {
        try {
            $query = "SELECT 1 FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la vérification de l'existence de la photo : " . $e->getMessage());
        }
    }
}