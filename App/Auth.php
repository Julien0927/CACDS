<?php

namespace App\Auth;

class Auth {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function register($email, $password, $role, $sport_id, $poule_id = null) {
        // Vérification email unique
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('Email déjà utilisé');
        }

        // Hashage sécurisé du mot de passe
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (email, password, role, sport_id, poule_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$email, $hash, $role, $sport_id, $poule_id]);
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("
            SELECT u.*, s.name as sport_nom 
            FROM users u 
            LEFT JOIN sports s ON u.sport_id = s.id 
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Démarrage session sécurisée
            session_start(['cookie_httponly' => true]);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['sport_id'] = $user['sport_id'];
            $_SESSION['poule_id'] = $user['poule_id'];
            return $user;
        }
        return false;
    }

    public function checkAccess($sport_id, $poule_id = null) {
        if ($_SESSION['role'] === 'super_admin') {
            return true;
        }
        
        if ($_SESSION['sport_id'] !== $sport_id) {
            return false;
        }
        
        if ($poule_id && $_SESSION['poule_id'] !== $poule_id) {
            return false;
        }
        
        return true;
    }
}