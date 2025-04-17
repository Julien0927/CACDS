<?php
ob_start();
session_start();

require_once 'header.php';
require_once 'lib/auth.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'templates/nav.php';
require_once 'templates/messages.php';
include_once 'templates/toast.php'; 
require_once 'App/News.php';
require_once 'App/Results.php';
require_once 'App/Classements.php';
require_once 'App/Photos.php';
require_once 'App/Contacts.php';
require_once 'App/AdminSportsHandler.php';
require_once 'App/Documents.php';
require_once 'App/Trombinoscope.php'; 
require_once 'App/Annonces.php'; 
require_once 'App/Partenaires.php';
require_once 'App/Actualite.php'; 

$messages = [];
$errors = [];

if (isset($_SESSION['success_message'])) {
    showToast('success', $_SESSION['success_message']);
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error'])) {
    showToast('danger', $_SESSION['error']);
    unset($_SESSION['error']);
}


// Affichage des messages de succès
/* if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Supprimer le message après affichage
}
 */// Initialisation de la classe AdminSportsHandler
$adminHandler = new App\AdminSportsHandler\AdminSportsHandler($db);
$documentsManager = new App\Documents\Documents($db);
$trombinosManager = new App\Trombinoscope\Trombinoscope($db);
$annonces = new App\Annonces\Annonces($db);
$actualiteManager = new App\Actualite\Actualite($db);


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
<ul class="nav nav-tabs mt-3">
  <li class="nav-item">
    <a class="nav-link <?= (!isset($_GET['tab']) || $_GET['tab'] === 'actualite') ? 'active bg-messages' : '' ?>" 
       style="background-color: #6A0572;"
       href="?tab=actualite">Actualité</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] === 'messages') ? 'active bg-messages' : '' ?>" 
       style="background-color: #6A0572;"
       href="?tab=messages">Messages reçus</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] === 'documents') ? 'active bg-messages' : '' ?>" 
       style="background-color: #6A0572" 
       href="?tab=documents">Documents</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] === 'trombinoscope') ? 'active bg-messages' : '' ?>" 
       style="background-color: #6A0572" 
       href="?tab=trombinoscope">Trombinoscope</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] === 'annonces') ? 'active bg-messages' : '' ?>" 
       style="background-color: #6A0572" 
       href="?tab=annonces">Annonces</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] === 'partenaires') ? 'active bg-messages' : '' ?>" 
       style="background-color: #6A0572" 
       href="?tab=partenaires">Partenaires</a>
  </li>
</ul>
<?php
$tab = $_GET['tab'] ?? 'actualite'; // Onglet par défaut

switch ($tab) {
    case 'actualite':
        require_once 'templates/actualiteAdmin.php';
        break;
    case 'messages':
        require_once 'templates/messagesAdmin.php';
        break;
    case 'documents':
        require_once 'templates/docCACDS.php';
        break;
    case 'trombinoscope':
        require_once 'templates/trombinos.php';
        break;
    case 'annonces':
        require_once 'templates/annoncesAdmin.php';
        break;
    case 'partenaires':
        require_once 'templates/partnersAdmin.php';
        break;
    default:
        echo "<p>Onglet inconnu.</p>";
}
?>


<script>
  const toastEl = document.querySelector('.toast');
  if (toastEl) {
    const toast = new bootstrap.Toast(toastEl, {
      delay: 4000,
      autohide: true
    });
    toast.show();
  }
</script>
