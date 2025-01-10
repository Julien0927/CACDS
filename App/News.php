<?php

namespace App\News;

use PDO;
use PDOException;

class News {
    private PDO $db;
    private int $id;
    private string $title;
    private string $content;
    private string $image;
    private string $date;
    private int $sport_id;
    private ?int $poule_id;
    private int $user_id;
    private int $newsParPage = 3;


    public function __construct(PDO $db) {
        $this->db = $db;
        $this->newsParPage = 3;
    }

    public function setSportId(int $sport_id) {
        $this->sport_id = $sport_id;
    }

    public function setPouleId(?int $poule_id) {
        $this->poule_id = $poule_id;
    }

    public function setUserId(int $user_id) {
        $this->user_id = $user_id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function setContent(string $content) {
        $this->content = $content;
    }

    public function setImage(string $image) {
        $this->image = $image;
    }

    //Fonction permettant de recuperer la date et de la mettre au format date
    public function setDate(string $date) {
        $this->date = $date;
    }

    public function setNewsParPage(int $number): void {
        $this->newsParPage = $number;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getContent(): string{
        return $this->content;
    }

    public function getImage(): ? string{
        return $this->image;
    }

    public function getDate(): string {
        return $this->date;
    }
    public function getNewsParPage(): int {
        return $this->newsParPage;
    }

     //Fonction permettant de recuperer les news par sport
    public function getNewsBySport($sport_id, $page)
    {
    $sql = "SELECT * FROM news 
            WHERE sport_id = :sport_id 
            ORDER BY date DESC, id DESC"; 
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':sport_id', $sport_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 

    //Fonction permettant de recuperer un article par son id
    public function getNewById(int $id) {
        $sql = "SELECT * FROM `news` WHERE `id` = :id";
        $query = $this->db->prepare($sql);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    //Fonction d'ajouter un article
    public function addNew(): void {
        
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            throw new \Exception("Vous devez être connecté pour ajouter une news");
        }

        $sql = "INSERT INTO `news`(
            `title`, `content`, `image`, `date`, `sport_id`, `poule_id`, `user_id`
        ) VALUES (
            :title, :content, :image, :date, :sport_id, :poule_id, :user_id
        )";
        
        $query = $this->db->prepare($sql);

        $query->bindValue(":title", $this->title, PDO::PARAM_STR);
        $query->bindValue(":content", $this->content, PDO::PARAM_STR);
        $query->bindValue(":image", $this->image, PDO::PARAM_STR);
        $query->bindValue(":date", $this->date, PDO::PARAM_STR);
        $query->bindValue(":sport_id", $_SESSION['sport_id'], PDO::PARAM_INT);
        $query->bindValue(":poule_id", $_SESSION['poule_id'], PDO::PARAM_INT);
        $query->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);

        $query->execute();
    }

    //Fonction permettant de récupérer les news par sport et poule
    public function getAllNews() {
        $sql = "SELECT n.*, u.name as author_name 
                FROM `news` n
                LEFT JOIN users u ON n.user_id = u.id
                WHERE 1=1";
        $params = [];

        // Si l'utilisateur n'est pas super admin, filtrer par sport et poule
        if ($_SESSION['role'] !== 'super_admin') {
            $sql .= " AND n.sport_id = :sport_id";
            $params[':sport_id'] = $_SESSION['sport_id'];

            if ($_SESSION['poule_id']) {
                $sql .= " AND n.poule_id = :poule_id";
                $params[':poule_id'] = $_SESSION['poule_id'];
            }
        }

        $sql .= " ORDER BY n.date DESC";
        $query = $this->db->prepare($sql);
        
        foreach($params as $key => $value) {
            $query->bindValue($key, $value, PDO::PARAM_INT);
        }
        
        $query->execute();
        $news = $query->fetchAll(PDO::FETCH_ASSOC);

        //Formatage de la date et du contenu
        foreach ($news as $key => $new) {
            $news[$key]['date'] = $this->formatDate($new['date']);
        }
    
        return $news;
    } 
    //Fonction permettant de formater la date
    private function formatDate(string $date): string {
        return date('d/m/Y', strtotime($date));
    }

    // Méthode pour vérifier si l'utilisateur a le droit de modifier/supprimer une news
    private function canModifyNews(int $newsId): bool {
        if ($_SESSION['role'] === 'super_admin') {
            return true;
        }

        $sql = "SELECT user_id, sport_id, poule_id FROM news WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->bindValue(':id', $newsId, PDO::PARAM_INT);
        $query->execute();
        $news = $query->fetch(PDO::FETCH_ASSOC);

        return $news &&
               $news['sport_id'] === $_SESSION['sport_id'] &&
               ($news['poule_id'] === $_SESSION['poule_id'] || $_SESSION['poule_id'] === null);
    }


    //Fonction permettant de modifier un article
    public function updateNew(): void {
        if (!$this->canModifyNews($this->id)) {
            throw new \Exception("Vous n'avez pas les droits pour modifier cette news");
        }

        $sql = "UPDATE `news` SET 
                `title` = :title,
                `content` = :content,
                `image` = :image,
                `date` = :date 
                WHERE id = :id";
                
        $query = $this->db->prepare($sql);

        $query->bindValue(":title", $this->title, PDO::PARAM_STR);
        $query->bindValue(":content", $this->content, PDO::PARAM_STR);
        $query->bindValue(":image", $this->image, PDO::PARAM_STR);
        $query->bindValue(":date", $this->date, PDO::PARAM_STR);
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);

        $query->execute();
    }

    //Fonction permettant de supprimer un article
    public function deleteNew(): void {
        if (!$this->canModifyNews($this->id)) {
            throw new \Exception("Vous n'avez pas les droits pour supprimer cette news");
        }

        $sql = "DELETE FROM `news` WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    //Fonction permettant de sauvegarder une image
    public function saveImage(): void {
        $sql = "INSERT INTO `news`(`image`) VALUES (:image)";
        $query = $this->db->prepare($sql);

        $query->bindValue(":image", $this->image, PDO::PARAM_STR);

        $query->execute();
    }

     // Méthode pour obtenir le nombre total de pages avec filtres
    public function getTotalPages(): int {
    $sql = "SELECT COUNT(*) FROM news WHERE 1=1";
    $params = [];

    // Vérification si l'utilisateur est connecté
    if (isset($_SESSION['role']) && $_SESSION['role'] !== 'super_admin') {
        $sql .= " AND sport_id = :sport_id";
        $params[':sport_id'] = $_SESSION['sport_id'];

        if (!empty($_SESSION['poule_id'])) {
            $sql .= " AND poule_id = :poule_id";
            $params[':poule_id'] = $_SESSION['poule_id'];
        }
    }

    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_INT);
    }
    $stmt->execute();
    
    $totalNews = $stmt->fetchColumn();
    return ceil($totalNews / $this->newsParPage);
    }
 

    // Méthode pour récupérer les articles pour une page donnée avec filtres
    public function getNewsByPage(int $pageActuelle = 1): array {
        $pageActuelle = max(1, $pageActuelle);
        $offset = ($pageActuelle - 1) * $this->newsParPage;

        $sql = "SELECT n.*, u.name as author_name 
                FROM `news` n
                LEFT JOIN users u ON n.user_id = u.id
                WHERE 1=1";
        $params = [];

        if ($_SESSION['role'] !== 'super_admin') {
            $sql .= " AND n.sport_id = :sport_id";
            $params[':sport_id'] = $_SESSION['sport_id'];

            if ($_SESSION['poule_id']) {
                $sql .= " AND n.poule_id = :poule_id";
                $params[':poule_id'] = $_SESSION['poule_id'];
            }
        }

        $sql .= " ORDER BY n.date DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $this->newsParPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($news as $key => $new) {
            $news[$key]['date'] = $this->formatDate($new['date']);
        }
    
        return $news;
    }
}
