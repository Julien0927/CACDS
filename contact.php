<?php

ob_start();
require_once 'header.php';
require_once 'lib/config_session.php';
require_once 'templates/nav.php';
require_once 'App/Contacts.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';

$messages = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken()) {
        $errors[] = "Erreur de sécurité lors de l'envoi du message";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    } else {
        $contactManager = new App\Contacts\Contacts($db);
        $result = $contactManager->addContact(
            htmlspecialchars($_POST['firstname']),
            htmlspecialchars($_POST['name']),
            htmlspecialchars($_POST['email']),
            htmlspecialchars($_POST['content'])
        );

        if ($result) {
            $_SESSION['success_message'] = "Votre message a bien été envoyé";
            header('Location: contact.php');
            exit;
        } else {
            $errors[] = "Erreur lors de l'envoi du message";
        }
    }
}

// Affichage des messages de succès
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

// Affichage des erreurs
foreach ($errors as $error) {
    echo '<div class="alert danger connexion bold mx-auto">' . $error . '</div>';
}
?>

<h1 class="text-center mt-3">Message</h1>
<form class="ms-2" method="POST" action="">
    <div class="d-flex mx-auto flex-column col-12 col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" name="name" class="form-control inputRegister" placeholder="Votre nom" id="name" required>
        </div>
        <div class="mb-3">
            <label for="firstname" class="form-label">Prénom</label>
            <input type="text" name="firstname" class="form-control inputRegister" placeholder="Votre prénom" id="firstname" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control inputRegister" placeholder="Votre email" id="email" required>
        </div>
        <div class="mb-3">
            <label for="content">Message</label>
            <textarea rows="6" class="form-control" name="content" placeholder="Contenu de votre message" id="content" required></textarea>
        </div>
        <div class="center mt-2">
            <?php addCSRFTokenToForm() ?>
            <button class="btn btn-original bold" type="submit">Envoyer</button>
        </div>
    </div>
</form>

<?php require_once 'templates/footer.php'; ?>