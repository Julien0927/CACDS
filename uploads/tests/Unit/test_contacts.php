<?php

require_once 'App/Contacts.php'; // Assurez-vous que le chemin est correct

// Configuration de la base de données
try {
    $db = new PDO(
        'mysql:host=localhost;dbname=cacds1;charset=utf8',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Création d'une instance de la classe Contacts
$contacts = new App\Contacts\Contacts($db);

// Test 1: Ajout d'un contact
echo "Test d'ajout d'un contact:\n";
$result = $contacts->addContact(
    'Jean',
    'Dupont',
    'jean.dupont@email.com',
    'Message de test'
);
echo $result ? "Contact ajouté avec succès\n" : "Erreur lors de l'ajout du contact\n";

// Test 2: Récupération de tous les contacts
echo "\nTest de récupération de tous les contacts:\n";
$allContacts = $contacts->getContact();
foreach ($allContacts as $contact) {
    echo "ID: {$contact['id']}, Nom: {$contact['name']}, " .
         "Prénom: {$contact['firstname']}, Email: {$contact['email']}\n";
}

// Test 3: Récupération d'un contact par ID
echo "\nTest de récupération d'un contact par ID (1):\n";
$contact = $contacts->getContactById(1);
if ($contact) {
    echo "Contact trouvé: {$contact['firstname']} {$contact['name']}\n";
} else {
    echo "Contact non trouvé\n";
}

// Test 4: Suppression d'un contact
echo "\nTest de suppression d'un contact (ID: 1):\n";
$deleteResult = $contacts->deleteMessage(1);
echo $deleteResult ? "Contact supprimé avec succès\n" : "Erreur lors de la suppression\n";