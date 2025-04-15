<?php
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
    header('Location: dashboardAdmin.php?tab=trombinoscope');
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
    header('Location: dashboardAdmin.php?tab=trombinoscope');
    exit;
}
?>

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

    <form action="?tab=trombinoscope" method="post" enctype="multipart/form-data">
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

