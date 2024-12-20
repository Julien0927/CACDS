<?php

namespace App\News;

class News {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($titre, $contenu, $sport_id, $users_id) {
        $stmt = $this->pdo->prepare("
            INSERT INTO news (titre, contenu, sport_id, users_id)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$titre, $contenu, $sport_id, $users_id]);
    }
    
    public function update($id, $titre, $contenu) {
        $stmt = $this->pdo->prepare("
            UPDATE news 
            SET titre = ?, contenu = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$titre, $contenu, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.name as auteur 
            FROM news a
            JOIN users u ON a.users_id = u.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getBySport($sport_id) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.name as auteur 
            FROM news a
            JOIN users u ON a.users_id = u.id
            WHERE a.sport_id = ?
            ORDER BY a.date_creation DESC
        ");
        $stmt->execute([$sport_id]);
        return $stmt->fetchAll();
    }
}

/* <?php

namespace App\Articles;

use PDO;
use PDOException;

class Articles {
    private PDO $db;
    private int $id;
    private string $title;
    private string $content;
    private string $image;
    private string $date;
    private int $articlesParPage = 6;

    public function __construct(PDO $db) {
        $this->db = $db;
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

    //Fonction permettant de recuperer un article par son id
    public function getArticleById(int $id) {
        $sql = "SELECT * FROM `articles` WHERE `id` = :id";
        $query = $this->db->prepare($sql);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    //Fonction d'ajouter un article
    public function addArticle(): void {

        $sql = "INSERT INTO `articles`(`title`, `content`, `image`, `date`) VALUES (:title, :content, :image, :date)";
        $query = $this->db->prepare($sql);

        $query->bindValue(":title", $this->title, PDO::PARAM_STR);
        $query->bindValue(":content", $this->content, PDO::PARAM_STR);
        $query->bindValue(":image", $this->image, PDO::PARAM_STR);
        $query->bindValue(":date", $this->date, PDO::PARAM_STR);

       $query->execute();
    }

     //Fonction permettant de recuperer tous les articles
    public function getAllArticles() {
        $sql = "SELECT * FROM `articles` ORDER BY `date` DESC";
        $query = $this->db->prepare($sql);
        $query->execute();
       
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);

        //Formatage de la date
        foreach ($articles as $key => $article) {
            $articles[$key]['date'] = $this->formatDate($article['date']);

            if (strlen($article['content']) > 100) {
                $articles[$key]['content'] = substr($article['content'], 0, 100) . '...';
            }
        }
    
        return $articles;
    }

 
    //Fonction permettant de formater la date
    private function formatDate(string $date): string {
        return date('d/m/Y', strtotime($date));
    }

    //Fonction permmettant de modifier un article
    public function updateArticle(): void {
        $sql = "UPDATE `articles` SET `title`=:title,`content`=:content,`image`=:image,`date`=:date WHERE id=:id";
        $query = $this->db->prepare($sql);

        $query->bindValue(":title", $this->title, PDO::PARAM_STR);
        $query->bindValue(":content", $this->content, PDO::PARAM_STR);
        $query->bindValue(":image", $this->image, PDO::PARAM_STR);
        $query->bindValue(":date", $this->date, PDO::PARAM_STR);
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);

        $query->execute();
    }

    //Fonction permettant de supprimer un article
    public function deleteArticle(): void {
        $sql = "DELETE FROM `articles` WHERE id=:id";
        $query = $this->db->prepare($sql);

        $query->bindValue(":id", $this->id, PDO::PARAM_INT);

        $query->execute();
    }

    //Fonction permettant de sauvegarder une image
    public function saveImage(): void {
        $sql = "INSERT INTO `articles`(`image`) VALUES (:image)";
        $query = $this->db->prepare($sql);

        $query->bindValue(":image", $this->image, PDO::PARAM_STR);

        $query->execute();
    }

    // Méthode pour obtenir le nombre total de pages
    public function getTotalPages(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM articles");
        $totalArticles = $stmt->fetchColumn();
        return ceil($totalArticles / $this->articlesParPage);
    }

    // Méthode pour récupérer les articles pour une page donnée
    public function getArticlesByPage(int $pageActuelle = 1): array {
        $pageActuelle = max(1, $pageActuelle);
        
        $offset = ($pageActuelle - 1) * $this->articlesParPage;
        $sql = "SELECT * FROM articles ORDER BY date DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $this->articlesParPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Formatage de la date
        foreach ($articles as $key => $article) {
            $articles[$key]['date'] = $this->formatDate($article['date']);

            if (strlen($article['content']) > 100) {
                $articles[$key]['content'] = substr($article['content'], 0, 100) . '...';
            }
        }
    
        return $articles;
    }

} */