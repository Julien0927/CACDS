<?php
ob_start();
session_start();

require_once 'header.php';
require_once 'lib/auth.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'templates/nav.php';
require_once 'templates/messages.php';
require_once 'App/News.php';
require_once 'App/Results.php';
require_once 'App/Classements.php';
require_once 'App/Photos.php';
require_once 'App/Contacts.php';
require_once 'App/AdminSportsHandler.php'; 

$messages = [];
$errors = [];

// Affichage des messages de succès
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Supprimer le message après affichage
}
// Initialisation de la classe AdminSportsHandler
$adminHandler = new App\AdminSportsHandler\AdminSportsHandler($db);


// Vérification si l'utilisateur est super administrateur
if ($_SESSION['role'] === 'super_admin') {
    // Charger les sports disponibles
    $sports = $adminHandler->getAllSports();

    // Traitement de la sélection d'un sport
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_sport_id'])) {
        $selectedSportId = (int)$_POST['selected_sport_id'];
        $adminHandler->setTemporarySport($selectedSportId);
        $_SESSION['sport_id'] = $selectedSportId; // Stocker l'ID du sport sélectionné dans la session
        // Redirection en fonction de l'ID du sport
    switch ($selectedSportId) {
        case 1: // ID pour Tennis de table
            header("Location: dashboardTdT.php");
            exit();
        case 2: // ID pour Badminton
            header("Location: dashboardBad.php");
            exit();
        case 3: // ID pour Pétanque
            header("Location: dashboardPetanque.php");
            exit();
        case 4: // ID pour Volleyball
            header("Location: dashboardVolley.php");
            exit();
        default:
            // Sport non reconnu, retour à la page admin avec un message d'erreur
/*         $_SESSION['success_message'] = "Sport sélectionné avec succès !";
 */        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
}
?>
<h1 class="h1Sports text-center mt-3">Tableau de bord administrateur</h1>
<?php if ($_SESSION['role'] === 'super_admin'): ?>
    <h5 class=" h2Sports text-center mt-3">Choisissez votre tableau de bord</h5>
    <!-- Dropdown pour sélectionner un sport -->
    <form method="POST" action="">
        <div class="center mt-4">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <select id="sportSelector" name="selected_sport_id" onchange="this.form.submit()">
            <option value="" >-- Sélectionnez un sport --</option>
            <?php foreach ($sports as $sport): ?>
                <option value="<?= $sport['id'] ?>" <?= ($_SESSION['sport_id'] ?? '') == $sport['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sport['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    </form>
<?php endif; ?>
<?php
// Récupération des messages
$contactsManager = new App\Contacts\Contacts($db);
$messages = $contactsManager->getContact();

// Suppression d'un message
if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
    if (verifyCSRFToken()) {
        if ($contactsManager->deleteMessage((int)$_POST['message_id'])) {
            $_SESSION['success_message'] = "Message supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du message";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité";
    }
    header('Location: dashboardAdmin.php');
    exit;
}
?>

 <div class="container mt-4">
    <h2 class="h2Sports text-center mb-4">Messages reçus</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= htmlspecialchars($message['created_at']) ?></td>
                            <td><?= htmlspecialchars($message['name']) ?></td>
                            <td><?= htmlspecialchars($message['firstname']) ?></td>
                            <td><?= htmlspecialchars($message['email']) ?></td>
                            <td><?= htmlspecialchars(mb_strlen($message['content']) > 100 ? 
                                mb_substr($message['content'], 0, 100) . '...' : 
                                $message['content']) ?></td>
                            <td class="center">
                                <button type="button" class="btn btn-second bold btn-sm mb-2 me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#messageModal<?= $message['id'] ?>">
                                    Lire
                                </button>
                                <!-- Formulaire de suppression existant -->
                                <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                    <?php addCSRFTokenToForm(); ?>
                                    <input type="hidden" name="message_id" value="<?= (int)$message['id'] ?>">
                                    <button type="submit" name="delete_message" class="btn btn-original bold btn-sm mb-2">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun message</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Modales pour chaque message -->
        <?php foreach ($messages as $message): ?>
            <div class="modal fade" id="messageModal<?= $message['id'] ?>" tabindex="-1" aria-labelledby="messageModalLabel<?= $message['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="messageModalLabel<?= $message['id'] ?>">
                                Message de <?= htmlspecialchars($message['firstname']) ?> <?= htmlspecialchars($message['name']) ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Date :</strong> <?= htmlspecialchars($message['created_at']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($message['email']) ?></p>
                            <p><strong>Message :</strong></p>
                            <p><?= nl2br(htmlspecialchars($message['content'])) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-original bold" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
<?php endforeach; ?>
    </div>
</div>
<?php
require_once 'templates/footer.php';