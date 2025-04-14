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
require_once 'App/Documents.php';
require_once 'App/Trombinoscope.php'; 

$messages = [];
$errors = [];

// Affichage des messages de succès
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Supprimer le message après affichage
}
// Initialisation de la classe AdminSportsHandler
$adminHandler = new App\AdminSportsHandler\AdminSportsHandler($db);
$documentsManager = new App\Documents\Documents($db);
$trombinosManager = new App\Trombinoscope\Trombinoscope($db);


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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document']) && isset($_POST['categorie'])) {
    if (verifyCSRFToken()) {
        $categorie = $_POST['categorie'];

        // Récupérer le fichier
        $file = $_FILES['document'];
        $uploadDir = 'uploads/documents/';
        $filePath = $uploadDir . basename($file['name']);

        // Vérifier si le fichier est un PDF
        if ($file['type'] === 'application/pdf') {
            // Déplacer le fichier vers le répertoire d'upload
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Ajouter le document et supprimer l'ancien si nécessaire
                if ($documentsManager->addDocument($categorie, $filePath)) {
                    $_SESSION['success_message'] = "Document ajouté avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout du document.";
                }
            } else {
                $_SESSION['error'] = "Erreur lors de l'upload du fichier.";
            }
        } else {
            $_SESSION['error'] = "Le fichier doit être un PDF.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité.";
    }
    header('Location: dashboardAdmin.php');
    exit;
}
// Traitement pour la suppression d’un document
if (isset($_POST['delete_document']) && isset($_POST['document_id'])) {
    if (verifyCSRFToken()) {
        if ($documentsManager->deleteDocument((int)$_POST['document_id'])) {
            $_SESSION['success_message'] = "Document supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du document.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité.";
    }
    header('Location: dashboardAdmin.php');
    exit;
}
//AJout d'un trombinoscope
// Traitement pour l'ajout d’un document Trombinoscope
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_path']) && isset($_POST['titre']) && isset($_POST['poule'])) {
    if (verifyCSRFToken()) {
        $poule = $_POST['poule'];
        $title = $_POST['titre'];
        $file = $_FILES['file_path'];

        if ($file['type'] === 'application/pdf') {
            $uploadDir = 'uploads/trombinoscopes/';
            $filePath = $uploadDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                if ($trombinosManager->addTrombinos($poule, $title, $filePath)) {
                    $_SESSION['success_message'] = "Trombinoscope ajouté avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout du document trombinoscope.";
                }
            } else {
                $_SESSION['error'] = "Erreur lors de l'upload du fichier.";
            }
        } else {
            $_SESSION['error'] = "Le fichier doit être un PDF.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité.";
    }
    header('Location: dashboardAdmin.php');
    exit;
}

// Traitement pour suppression d’un document Trombinoscope
if (isset($_POST['delete_trombinoscope']) && isset($_POST['document_id'])) {
    if (verifyCSRFToken()) {
        if ($trombinosManager->deleteTrombinos((int)$_POST['document_id'])) {
            $_SESSION['success_message'] = "Trombinoscope supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du document.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité.";
    }
    header('Location: dashboardAdmin.php');
    exit;
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
                    <th>Action</th>
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
<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Documents</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Catégorie</th>
                    <th>Fichier</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Affichage des documents par catégorie
                $categories = [
                    "Règlement Badminton CACDS", 
                    "Compte rendu réunion des capitaines",
                    "Demande d'engagement",
                    "Demande d'adhésions", 
                    "Attestation certificats médicaux",
                    "Autorisation droit à l'image",
                    "Fournir un certificat médical",
                    "Coordonnées des capitaines",
                    "Créneaux des équipes",
                    "Relais de l'information",
                    "Feuille de match",
                    "Palmarès championnat",
                    "Palmarès coupe",
                    "Palmarès titres et double",
                    "Calendrier de la saison",
                ];
              
                foreach ($categories as $categorie) {
                    $documents = $documentsManager->getDocumentsByCategory(htmlspecialchars($categorie));
                    if (!empty($documents)) {
                        foreach ($documents as $document) { ?>
                            <tr>
                                <td><?=(htmlspecialchars($categorie)) ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($document['fichier']) ?>" target="_blank">
                                        <i class="fas fa-file-pdf d-flex justify-content-center mt-3" style="color: purple;"></i>
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                        <?php addCSRFTokenToForm(); ?>
                                        <input type="hidden" name="document_id" value="<?= (int)$document['id'] ?>">
                                        <button type="submit" name="delete_document" class="btn btn-original btn-sm">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>                     
                        <?php }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>Aucun document dans cette catégorie.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Ajouter un document</h2>

    <form action="dashboardAdmin.php" method="post" enctype="multipart/form-data">
        <div class="d-flex justify-content center gap-3 flex-wrap">

            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie" required>
                <option value="Calendrier de la saison">Calendrier de la saison</option>
                <option value="Règlement Badminton CACDS">Règlement Badminton CACDS</option>
                <option value="Compte rendu réunion des capitaines">Compte rendu réunion des capitaines</option>
                <option value="Demande d'engagement">Demande d'engagement</option>
                <option value="Demande d'adhésions">Demande d'adhésions</option>
                <option value="Attestation certificats médicaux">Attestation certificats médicaux</option>
                <option value="Autorisation droit à l'image">Autorisation droit à l'image</option>
                <option value="Fournir un certificat médical">Fournir un certificat médical</option>
                <option value="Coordonnées des capitaines">Coordonnées des capitaines</option>
                <option value="Créneaux des équipes">Créneaux des équipes</option>
                <option value="Relais de l'information">Relais de l'information</option>
                <option value="Feuille de match">Feuille de match</option>
                <option value="Palmarès Championnat">Palmarès Championnat</option>
                <option value="Palmarès Coupe">Palmarès Coupe</option>
                <option value="Palmarès titres et double">Palmarès titres et double</option>
            </select>

            <label for="document">Fichier PDF :</label>
            <input type="file" name="document" id="document" accept="application/pdf" required>
        </div>
        <?php addCSRFTokenToForm(); ?>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-original bold mt-3">Ajouter le document</button>
        </div>
    </form>
</div>

<!--Affichage trombinoscope-->

<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Trombinoscopes</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Poule</th>
                    <th>Équipe</th>
                    <th>Fichier</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $trombiDocs = $trombinosManager->getAllTrombinos();
                if (!empty($trombiDocs)) {
                    foreach ($trombiDocs as $doc) {?>
                        <tr>
                            <td ><?= htmlspecialchars($doc['poule']) ?></td>
                            <td><?= htmlspecialchars($doc['titre']) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($document['fichier']) ?>" target="_blank">
                                    <i class="fas fa-file-pdf d-flex justify-content-center mt-3" style="color: purple;"></i>
                                </a>
                            </td>
                            <td>
                                <form method="POST" action="" onsubmit="return confirm('Supprimer ce document ?');">
                                    <?php addCSRFTokenToForm(); ?>
                                    <input type="hidden" name="document_id" value="<?= (int)$doc['id'] ?>">
                                    <button type="submit" name="delete_trombinoscope" class="btn btn-original btn-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Aucun document</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Ajouter un trombinoscope</h2>

    <form action="dashboardAdmin.php" method="post" enctype="multipart/form-data">
        <div class="d-flex justify-content center gap-3 flex-wrap">

            <label for="poule">Poule :</label>
            <select name="poule" id="categorie" required>
                <option value="1">Poule 1</option>
                <option value="2">Poule 2</option>
                <option value="3">Poule 3</option>
                <option value="4">Poule 4</option>
                <option value="5">Poule 5</option>
                <option value="6">Poule 6</option>
                <option value="7">Poule 7</option>
                <option value="8">Poule 8</option>
            </select>

            <label for="titre">Équipe :</label>
            <input type="text" name="titre" id="titre" accept="application/pdf" required>

            <label for="file_path">Fichier PDF :</label>
            <input type="file" name="file_path" id="file_path" accept="application/pdf" required>
        </div>
        <?php addCSRFTokenToForm(); ?>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-original bold mt-3">Ajouter le document</button>
        </div>
    </form>
</div>

<?php
require_once 'templates/footer.php';