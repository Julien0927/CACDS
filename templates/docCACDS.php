<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document']) && isset($_POST['categorie']) && isset($_POST['titre'])) {
    if (verifyCSRFToken()) {
        $categorie = $_POST['categorie'];
        $titre = $_POST['titre'];

        // Récupérer le fichier
        $file = $_FILES['document'];
        $uploadDir = 'uploads/documents/';
        $filePath = $uploadDir . basename($file['name']);

        // Vérifier si le fichier est un PDF
        if ($file['type'] === 'application/pdf') {
            // Déplacer le fichier vers le répertoire d'upload
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Ajouter le document
                if ($documentsManager->addDocument($categorie, $titre, $filePath)) {
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
// Traitement pour la suppression d'un document
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
                    <th>Titre</th>
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
                    "Bureau",
                    "Comité de gestion",
                    "Assemblée Générale",
                    "Les Statuts",
                    "Les Réglements Généraux"
                ];
              
                foreach ($categories as $categorie) {
                    $documents = $documentsManager->getDocumentsByCategory(htmlspecialchars($categorie));
                    if (!empty($documents)) {
                        foreach ($documents as $document) { ?>
                            <tr>
                                <td><?= htmlspecialchars($categorie) ?></td>
                                <td><?= ($document['titre']) ?></td>
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
                        echo "<tr><td colspan='4' class='text-center'>Aucun document.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Ajouter un document</h2>
    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <?php addCSRFTokenToForm(); ?>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="categorie" name="categorie" required>
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie) ?>"><?= htmlspecialchars($categorie) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="titre" class="form-label">Titre du document</label>
                <input type="text" class="form-control" id="titre" name="titre" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="document" class="form-label">Document (PDF)</label>
                <input type="file" class="form-control" id="document" name="document" accept=".pdf" required>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-original">Ajouter le document</button>
        </div>
    </form>
</div>
