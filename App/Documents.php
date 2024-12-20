<?php

namespace App\Documents;

class Documents {
    private $pdo;
    private $upload_dir;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->upload_dir = [
            'photo' => 'uploads/photos/',
            'pdf' => 'uploads/pdf/'
        ];
    }
    
    public function upload($file, $sport_id, $utilisateur_id, $type) {
        $allowed_types = [
            'photo' => ['image/jpeg', 'image/png'],
            'pdf' => ['application/pdf']
        ];
        
        if (!in_array($file['type'], $allowed_types[$type])) {
            throw new Exception('Type de fichier non autorisé');
        }
        
        $filename = uniqid() . '_' . basename($file['name']);
        $filepath = $this->upload_dir[$type] . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Erreur lors de l\'upload');
        }
        
        $stmt = $this->pdo->prepare("
            INSERT INTO documents (nom, chemin, type, sport_id, users_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        if (!$stmt->execute([$filename, $filepath, $type, $sport_id, $users_id])) {
            unlink($filepath); // Supprime le fichier si l'insertion échoue
            throw new Exception('Erreur lors de l\'enregistrement');
        }
        
        return $filepath;
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT chemin FROM documents WHERE id = ?");
        $stmt->execute([$id]);
        $document = $stmt->fetch();
        
        if ($document && file_exists($document['chemin'])) {
            unlink($document['chemin']);
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM documents WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getBySport($sport_id, $type = null) {
        $sql = "
            SELECT d.*, u.name as auteur 
            FROM documents d
            JOIN users u ON d.users_id = u.id
            WHERE d.sport_id = ?
        ";
        $params = [$sport_id];
        
        if ($type) {
            $sql .= " AND d.type = ?";
            $params[] = $type;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function validateUploadDir() {
        foreach ($this->upload_dir as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }
}