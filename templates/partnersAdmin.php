<?php
$partenaireManager = new App\Partenaires\Partenaires($db); // à adapter à ton nom de classe

// AJOUT PARTENAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'], $_FILES['logo']) && isset($_POST['add_partenaire'])) {
    if (verifyCSRFToken()) {
        $url = trim($_POST['url']);
        $file = $_FILES['logo'];
        $logo_path = null;

        $allowedTypes = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/webp' => '.webp'
        ];

        if ($file['error'] === UPLOAD_ERR_OK) {

            // Limite de taille : 5 Mo
            if ($file['size'] <= 5 * 1024 * 1024) {

                // Vérifie que le fichier est bien une image
                if (getimagesize($file['tmp_name']) !== false) {

                    // Vérifie le type MIME
                    if (array_key_exists($file['type'], $allowedTypes)) {

                        $uploadDir = 'uploads/partenaires/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $extension = $allowedTypes[$file['type']];
                        $fileName = uniqid('logo_') . $extension;
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($file['tmp_name'], $filePath)) {
                            $logo_path = $filePath;
                        } else {
                            $_SESSION['error'] = "Erreur lors de l'upload du logo.";
                            header("Location: dashboardAdmin.php?tab=partenaires");
                            exit;
                        }

                    } else {
                        $_SESSION['error'] = "Type de fichier non autorisé. (jpg, png, webp)";
                        header("Location: dashboardAdmin.php?tab=partenaires");
                        exit;
                    }

                } else {
                    $_SESSION['error'] = "Le fichier n'est pas une image valide.";
                    header("Location: dashboardAdmin.php?tab=partenaires");
                    exit;
                }

            } else {
                $_SESSION['error'] = "Logo trop volumineux (max 5 Mo).";
                header("Location: dashboardAdmin.php?tab=partenaires");
                exit;
            }

        } else {
            $_SESSION['error'] = "Erreur lors du transfert du fichier.";
            header("Location: dashboardAdmin.php?tab=partenaires");
            exit;
        }

        if ($partenaireManager->addPartenaire($logo_path, $url)) {
            $_SESSION['success_message'] = "Partenaire ajouté avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du partenaire.";
        }

    } else {
        $_SESSION['error'] = "Erreur de sécurité (CSRF).";
    }

    header("Location: dashboardAdmin.php?tab=partenaires");
    exit;
}

// SUPPRESSION PARTENAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_partenaire'], $_POST['id'])) {
    if (verifyCSRFToken()) {
        $id = (int) $_POST['id'];

        // On récupère le chemin du logo pour le supprimer physiquement
        $partenaire = $partenaireManager->deletePartenaire($id);
        if ($partenaire && !empty($partenaire['logo']) && file_exists($partenaire['logo'])) {
            unlink($partenaire['logo']);
        }

        if ($partenaireManager->deletePartenaire($id)) {
            $_SESSION['success_message'] = "Partenaire supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du partenaire.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité.";
    }
    header("Location: dashboardAdmin.php?tab=partenaires");
    exit;
}


$listePartners = $partenaireManager->getAllPartenaires();
?>
<!-- Gestion des partenaires -->
<section class="container mt-5" id="partenairesAdmin">
    <h2 class="h2Sports text-center mb-4">Gestion des partenaires</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Logo</th>
                    <th>Lien</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listePartners as $partner): ?>
                    <tr>
                        <td>
                            <?php if ($partner['logo']): ?>
                                <img src="<?= $partner['logo'] ?>" alt="Image" width="100">
                            <?php else: ?>
                                Aucune image
                            <?php endif; ?>
                        </td>
                        <td><?= $partner['url'] ?></td>
                        <td>
                            <form action="" method="POST" onsubmit="return confirm('Supprimer ce partenaire ?')">
                                <?php addCSRFTokenToForm(); ?>
                                <input type="hidden" name="id" value="<?= $partner['id'] ?>">
                                <button type="submit" name="delete_partenaire" class="btn btn-original btn-sm">Supprimer</button>
                            </form>
                            
                        </td>
                    </tr>
                <?php endforeach;
                if (empty($listePartners)){
                    echo '<tr><td colspan="3" class="text-center">Aucun partenaire.</td></tr>';
                } 
                ?>
            </tbody>
        </table>
    </div>

    <!-- Formulaire d'ajout -->
    <form action="?tab=partenaires" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="logo" class="form-label">Logo du partenaire :</label>
            <input type="file" name="logo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="url" class="form-label">URL du partenaire :</label>
            <input type="url" name="url" class="form-control" required>
            <small class="form-text text-muted">
                Formats acceptés : JPEG, PNG, WebP — Taille max : 5 Mo.
            </small>
        </div>
        <?php addCSRFTokenToForm(); ?>
        <div class="d-flex justify-content-center">
        <button type="submit" name="add_partenaire" class="btn btn-original">Ajouter le partenaire</button>
        </div>
    </form>

 </section>
