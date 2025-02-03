<?php

/* namespace App\Contacts;

use PDO;
use Exception;


class Contacts
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    function getContactById(int $id){
        $query = $this->db -> prepare ('SELECT * FROM message WHERE id = :id');
        $query -> bindParam(':id', $id, PDO::PARAM_INT);
        $query -> execute();
        return $query -> fetch();
      }
    
      
      // Fonction qui permet de tout récupérer (page d'accueil)
      function getContact(){
        $sql = 'SELECT * FROM message ORDER BY id DESC';
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
      }
      //Fonction qui permet de récupérer le formulaire de contact dans la BDD
      function addContact(string $firstName, string $lastName, string $email, $message) {
        $sql = 'INSERT INTO `message` (`firstname`, `name`, `email`, `content`, `created_at`) 
                VALUES (:firstname, :name, :email, :message, NOW())';
        $query = $this->db->prepare($sql);
        $query->bindParam(':firstname', $firstName, PDO::PARAM_STR);
        $query->bindParam(':name', $lastName, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        return $query->execute();
    }

    function deleteMessage(int $id) {
      try {
          $sql = 'DELETE FROM message WHERE id = :id';
          $query = $this->db->prepare($sql);
          $query->bindParam(':id', $id, PDO::PARAM_INT);
          $result = $query->execute();
          
          // Ajoutez ces lignes pour déboguer
          if (!$result) {
              error_log('Erreur SQL: ' . implode(', ', $query->errorInfo()));
          }
          return $result;
      } catch (Exception $e) {
          error_log('Exception: ' . $e->getMessage());
          return false;
      }
  }
} */
namespace App\Contacts;

use PDO;
use Exception;

class Contacts 
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getContactById(int $id)
    {
        try {
            $query = $this->db->prepare('SELECT * FROM message WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getContact()
    {
        try {
            $sql = 'SELECT *, DATE_FORMAT(created_at, "%d/%m/%Y %H:%i:%s") as created_at FROM message ORDER BY id DESC';
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function addContact(string $firstName, string $lastName, string $email, string $message)
    {
        try {
            $sql = 'INSERT INTO message (firstname, name, email, content, created_at) 
                    VALUES (:firstname, :name, :email, :message, NOW())';
            $query = $this->db->prepare($sql);
            $query->bindParam(':firstname', $firstName, PDO::PARAM_STR);
            $query->bindParam(':name', $lastName, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':message', $message, PDO::PARAM_STR);
            return $query->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteMessage(int $id)
    {
        try {
            $sql = 'DELETE FROM message WHERE id = :id';
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}