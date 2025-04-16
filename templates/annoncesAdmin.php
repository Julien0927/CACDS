<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['texte']) && isset($_FILES['image_path'])) {
    if (verifyCSRFToken()) {
        $titre = $_POST['titre'];
        $texte = $_POST['texte'];
        $file = $_FILES['image_path'];

        $image_path = null;

        // Vérification basique du fichier
        $allowedTypes = ['image/jpeg' => '.jpg', 'image/png' => '.png', 'image/webp' => '.webp'];

        if ($file['error'] === UPLOAD_ERR_OK) {

            // Taille max : 2 Mo
            if ($file['size'] <= 5 * 1024 * 1024) {

                // Vérifie le vrai contenu du fichier
                if (getimagesize($file['tmp_name']) !== false) {

                    // Vérifie le type MIME
                    if (array_key_exists($file['type'], $allowedTypes)) {

                        $uploadDir = 'uploads/annonces/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $extension = $allowedTypes[$file['type']];
                        $fileName = uniqid() . $extension;
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($file['tmp_name'], $filePath)) {
                            $image_path = $filePath;
                        } else {
                            $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
                            header("Location: dashboardAdmin.php?tab=annonces");
                            exit;
                        }

                    } else {
                        $_SESSION['error'] = "Type de fichier non autorisé.";
                        header("Location: dashboardAdmin.php?tab=annonces");
                        exit;
                    }

                } else {
                    $_SESSION['error'] = "Le fichier n'est pas une image valide.";
                    header("Location: dashboardAdmin.php?tab=annonces");
                    exit;
                }

            } else {
                $_SESSION['error'] = "Image trop lourde (max 2 Mo).";
                header("Location: dashboardAdmin.php?tab=annonces");
                exit;
            }

        } else {
            $_SESSION['error'] = "Erreur lors de l'envoi du fichier.";
            header("Location: dashboardAdmin.php?tab=annonces");
            exit;
        }

        // Insertion de l'annonce
        if ($annonces->addAnnonce($titre, $texte, $image_path)) {
            $_SESSION['success_message'] = "Annonce ajoutée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout de l'annonce.";
        }

    } else {
        $_SESSION['error'] = "Erreur de sécurité (CSRF).";
    }

    header("Location: dashboardAdmin.php?tab=annonces");
    exit;
}

// Traitement pour suppression d’une annonce
if (isset($_POST['delete_annonce']) && isset($_POST['id'])) {
    if (verifyCSRFToken()) {
        $id = (int) $_POST['id'];
        $annonce = $annonces->getAnnonceById($id);

        if ($annonce && !empty($annonce['image_path']) && file_exists($annonce['image_path'])) {
            unlink($annonce['image_path']);
        }

        if ($annonces->deleteAnnonce($id)) {
            $_SESSION['success_message'] = "Annonce supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de l'annonce.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité.";
    }
    header("Location: dashboardAdmin.php?tab=annonces");
    exit;
}

// Liste des annonces
$listeAnnonces = $annonces->getAllAnnonces();
?>
<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Annonces</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Titre</th>
                    <th>Texte</th>
                    <th>Image</th>
                    <th>Date de publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($listeAnnonces as $annonce): ?>
                    <tr>
                        <td><?= htmlspecialchars($annonce['titre']) ?></td>
                        <td><?= nl2br(htmlspecialchars($annonce['texte'])) ?></td>
                        <td>
                            <?php if ($annonce['image_path']): ?>
                                <img src="<?= $annonce['image_path'] ?>" alt="Image" width="100">
                            <?php else: ?>
                                Aucune image
                            <?php endif; ?>
                        </td>
                        <td><?= $annonce['created_at'] ?></td>
                        <td>
                            <form action="" method="POST" onsubmit="return confirm('Supprimer cette annonce ?')">
                                <?php addCSRFTokenToForm(); ?>
                                <input type="hidden" name="id" value="<?= $annonce['id'] ?>">
                                <button type="submit" name="delete_annonce" class="btn btn-original btn-sm">Supprimer</button>
                            </form>
                            <!-- Option : bouton Modifier ici -->
                        </td>
                    </tr>
                <?php endforeach;
                if(empty($listeAnnonces)){
                    echo "<tr><td colspan='5' class='text-center'>Aucune annonce</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="container mt-4 mb-3">
    <h2 class="h2Sports text-center mb-4">Ajouter une annonce</h2>
    <form action="?tab=annonces" method="POST" enctype="multipart/form-data">
        <div class="d-flex justify-content-center gap-3">
            <label for="titre" class="form-label">Titre de l’annonce :</label>
            <input type="text" name="titre" class="form-control w-50" required>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-2">
            <label for="texte" class="form-label">Texte de l’annonce :</label>
            <textarea name="texte" rows="6" class="form-control w-50" required></textarea>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
            <label for="image_path" class="form-label">Image (optionnelle) :</label>
            <div class="w-50">
                <input type="file" name="image_path" class="form-control" placeholder="Format jpeg, png ou webp uniquement">
                <small class="form-text text-muted">
                    Formats acceptés : JPEG, PNG, WebP — Taille max : 5 Mo.
                </small>
            </div>
        </div>
        <?php addCSRFTokenToForm(); ?>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-original bold mt-3">Publier l’annonce</button>
        </div>
    </form>
</div>
