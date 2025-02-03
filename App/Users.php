<?php

namespace App\Users;

use PDO;
use Exception;

class Users
{
    private $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour créer un utilisateur
    public function createUser($data)
    {
        // Vérifier si l'email existe déjà
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();

        // Si l'email existe déjà, retourner false pour indiquer que l'insertion échoue
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            // L'email existe déjà
            return false;
        }

        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        // Si l'email n'existe pas, procéder à l'insertion de l'utilisateur
        $sql = "INSERT INTO users (name, firstname, email, password, role, sport_id, poule_id) 
                VALUES (:name, :firstname, :email, :password, :role, :sport_id, :poule_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $passwordHash ); // Hash le mot de passe
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':sport_id', $data['sport_id']);
        $stmt->bindParam(':poule_id', $data['poule_id']);

        // Exécuter la requête d'insertion et retourner vrai si l'insertion a réussi
        return $stmt->execute();
    }

        // Nouvelle méthode pour vérifier si l'utilisateur est super admin
    public function isSuperAdmin($userId) {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        return $user && $user['role'] === 'super_admin';
    }

        // Méthode pour connecter un utilisateur
    public function login($email, $password) {
        $stmt = $this->db->prepare("
            SELECT u.*, s.name as sport_name 
            FROM users u 
            LEFT JOIN sports s ON u.sport_id = s.id 
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start(['cookie_httponly' => true]);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            // Pour un super_admin, on ne définit pas de sport_id ou poule_id spécifique
            if ($user['role'] !== 'super_admin') {
                $_SESSION['sport_id'] = $user['sport_id'];
                $_SESSION['poule_id'] = $user['poule_id'];
            }
            
            $_SESSION['is_super_admin'] = ($user['role'] === 'super_admin');
            return $user;
        }
        return false;
    }
    
        // Méthode pour déconnecter un utilisateur
    public function logout() {
        session_start(['cookie_httponly' => true]);
        session_destroy();
    }

    //Méthode pour récupérer tous les sports
public function getAllSports() {
    $stmt = $this->db->prepare("SELECT * FROM sports ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Modification de la méthode canAccessSport pour le super_admin
public function canAccessSport($userId, $sportId) {
    $stmt = $this->db->prepare("
        SELECT role, sport_id 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    // Le super_admin peut accéder à tous les sports
    if ($user && $user['role'] === 'super_admin') {
        return true;
    }
    
    // Pour les autres utilisateurs, vérifier le sport_id
    return $user && $user['sport_id'] == $sportId;
}

        // Méthode pour récupérer un utilisateur par son ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, s.name as sport_name 
            FROM users u 
            LEFT JOIN sports s ON u.sport_id = s.id 
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

}
