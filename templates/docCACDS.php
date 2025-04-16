<?php
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
    header('Location: dashboardAdmin.php?tab=documents');
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
    header('Location: dashboardAdmin.php?tab=documents');
    exit;
}
?>

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
                    "Feuille de match Coupe",
                    "Palmarès championnat",
                    "Palmarès coupe",
                    "Palmarès titres et double",
                    "Calendrier de la saison",
                    "Chiffres et statistiques",
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

    <form action="?tab=documents" method="post" enctype="multipart/form-data">
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
                <option value="Feuille de match Coupe">Feuille de match Coupe</option>
                <option value="Palmarès Championnat">Palmarès Championnat</option>
                <option value="Palmarès Coupe">Palmarès Coupe</option>
                <option value="Palmarès titres et double">Palmarès titres et double</option>
                <option value="Chiffres et statistiques">Chiffres et statistiques</option>
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
