<?php

/* namespace App\Photos;

use PDO;
use PDOException;

class Photos {
    private PDO $db;
    private $id;
    private $title;
    private $date;
    private $image;
    private $sportId;  // Ajout de sport_id

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDate() {
        return $this->date;
    }

    public function getImage() {
        return $this->image;
    }

    public function getSportId() {
        return $this->sportId;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setSportId($sportId) {
        $this->sportId = $sportId;
    }

    // Save photo to database
    public function addPhoto($db) {
        $query = "INSERT INTO photos (title, date, image, sport_id) VALUES (:title, :date, :image, :sport_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':sport_id', $this->sportId);
        return $stmt->execute();
    }

    // Retrieve photos by sport_id
    public static function getBySportId($db, $sportId) {
        $query = "SELECT * FROM photos WHERE sport_id = :sport_id ORDER BY date DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':sport_id', $sportId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all photos (optional)
    public static function getAllPhotos($db) {
        $query = "SELECT * FROM photos ORDER BY date DESC";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete photo by ID
    public function deletePhoto()  {
        $sql = "DELETE FROM photos WHERE id = :id";
        $query = $db->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }

} 
 */


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
}