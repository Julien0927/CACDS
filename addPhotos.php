<?php
session_start();

require_once 'App/Photos.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'lib/tools.php';
require_once 'lib/config_session.php';

try {
    // Création de l'instance Photos (utilisera automatiquement le sport_id de la session)
    $photos = new App\Photos\Photos($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérification du CSRF token
        if (!verifyCSRFToken()) {
            throw new Exception("Jeton de sécurité invalide");
        }

        // Validation des champs requis
        if (empty($_POST['title']) || empty($_POST['date'])) {
            throw new Exception("Le titre et la date sont obligatoires");
        }

        // Traitement de l'image
        if (empty($_FILES['image']['tmp_name'])) {
            throw new Exception("Veuillez sélectionner une image");
        }

        $checkImage = getimagesize($_FILES['image']['tmp_name']);
        if ($checkImage === false) {
            throw new Exception("Le fichier doit être une image valide");
        }

        // Traitement du fichier image
        $rawFileName = $_FILES['image']['name'];
        $cleanedFileName = strip_tags($rawFileName);
        $fileName = uniqid() . '-' . slugify($cleanedFileName);
        $targetPath = _IMG_PATH_ . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            throw new Exception("Erreur lors de l'upload du fichier");
        }

        // Configuration de l'objet photos
        $photos->setTitle($_POST['title']);
        $photos->setDate($_POST['date']);
        $photos->setImage($targetPath);

        // Ajout de la photo
        if ($photos->addPhoto()) {
            $_SESSION['messages'][] = "Votre photo a bien été enregistrée";
            header("Location: dashboardBad.php");
            exit();
        } else {
            // Suppression du fichier en cas d'échec
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            throw new Exception("Erreur lors de l'enregistrement de la photo");
        }
    }
} catch (Exception $e) {
    $_SESSION['errors'][] = $e->getMessage();
    header("Location: addPhotos.php");
    exit();
}

require_once 'header.php';
require_once 'templates/messages.php';
?>

<h2 class="h2Sports mt-3 text-center">Ajouter une photo</h2>

<div class="container col-12 col-md-6">
    <form method="POST" action="addPhotos.php" enctype="multipart/form-data" >
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="row mb-3">
                <div class="d-flex  justify-content-center  align-items-center gap-3">  
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="date">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*">
            </div>
        </div>
        <div class="d-flex justify-content-center">
        <?php addCSRFTokenToForm() ?>
        <button type="submit" class="btn btn-original my-3" value="">Enregistrer</button>
        </div>
    </form>
</div>
<?php
require_once ('templates/footer.php');
?>