<?php
session_start();
require_once 'App/Photos.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'lib/tools.php';
require_once 'lib/config_session.php';

// Configuration pour les uploads
const ALLOWED_IMAGE_MIME_TYPES = [
    'image/jpeg' => 'jpg',
    'image/jpg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif'
];

const ALLOWED_VIDEO_MIME_TYPES = [
    'video/mp4' => 'mp4',
    'video/webm' => 'webm',
    'video/ogg' => 'ogg'
];

const MAX_FILE_SIZE = 100 * 1024 * 1024; // 100MB pour les vidéos

function validateImage(array $file): array {
    if (empty($file['tmp_name'])) {
        throw new Exception("Veuillez sélectionner une image");
    }

    // Vérification de la taille
    if ($file['size'] > 100 * 1024 * 1024) { // 100MB pour les images
        throw new Exception("L'image est trop volumineuse (max 10MB)");
    }

    // Vérification simple du type MIME
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        throw new Exception("Format d'image invalide");
    }

    $mimeType = $imageInfo['mime'];
    if (!array_key_exists($mimeType, ALLOWED_IMAGE_MIME_TYPES)) {
        throw new Exception("Type de fichier non autorisé (JPG, PNG ou GIF uniquement)");
    }

    return [
        'mime_type' => $mimeType,
        'extension' => ALLOWED_IMAGE_MIME_TYPES[$mimeType]
    ];
}

function validateVideo(array $file): array {
    if (empty($file['tmp_name'])) {
        throw new Exception("Veuillez sélectionner une vidéo");
    }

    // Vérification de la taille
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception("La vidéo est trop volumineuse (max 100MB)");
    }

    // Vérification simple du type MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!array_key_exists($mimeType, ALLOWED_VIDEO_MIME_TYPES)) {
        throw new Exception("Type de vidéo non autorisé (MP4, WebM ou OGG uniquement)");
    }

    return [
        'mime_type' => $mimeType,
        'extension' => ALLOWED_VIDEO_MIME_TYPES[$mimeType]
    ];
}

try {
    $photos = new App\Photos\Photos($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCSRFToken()) {
            throw new Exception("Erreur de connexion");
        }
        if ($_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
            error_log("Erreur d'upload: " . $_FILES['media_file']['error']);
            throw new Exception("Erreur d'upload: " . $_FILES['media_file']['error']);
        }
        // Validation des champs
        if (empty($_POST['title']) || empty($_POST['date'])) {
            throw new Exception("Le titre et la date sont obligatoires");
        }

        $mediaType = $_POST['media_type'] ?? 'photo'; // Changé 'image' en 'photo'
        $photos->setType($mediaType);

        if ($mediaType === 'photo') { // Changé 'image' en 'photo'
            // Validation et traitement de l'image
            $imageInfo = validateImage($_FILES['media_file']);
            
            // Création du nom de fichier
            $fileName = uniqid() . '.' . $imageInfo['extension'];
            $targetPath = _IMG_PATH_ . $fileName;

            // Upload du fichier
            if (!move_uploaded_file($_FILES['media_file']['tmp_name'], $targetPath)) {
                throw new Exception("Erreur lors de l'upload de l'image");
            }

            // Enregistrement dans la base de données
            $photos->setTitle($_POST['title']);
            $photos->setDate($_POST['date']);
            $photos->setImage($targetPath);
            $photos->setVideo(null);
        } else {
            // Validation et traitement de la vidéo
            $videoInfo = validateVideo($_FILES['media_file']);
            
            // Création du nom de fichier
            $fileName = uniqid() . '.' . $videoInfo['extension'];
            $targetPath = _MEDIA_PATH_ . $fileName; // Utilisez un dossier dédié pour les vidéos

            // Upload du fichier
            if (!move_uploaded_file($_FILES['media_file']['tmp_name'], $targetPath)) {
                throw new Exception("Erreur lors de l'upload de la vidéo");
            }

            // Enregistrement dans la base de données
            $photos->setTitle($_POST['title']);
            $photos->setDate($_POST['date']);
            $photos->setVideo($targetPath);
            $photos->setImage(null);
        }

        if (!$photos->addMedia()) {
            // Suppression du fichier en cas d'échec
            $pathToCheck = $mediaType === 'photo' ? $photos->getImage() : $photos->getVideo(); // Changé 'image' en 'photo'
            if ($pathToCheck && file_exists($pathToCheck)) {
                unlink($pathToCheck);
            }
            throw new Exception("Erreur lors de l'enregistrement du média");
        }

        $_SESSION['messages'][] = "Votre " . ($mediaType === 'photo' ? "photo" : "vidéo") . " a bien été enregistrée"; // Changé 'image' en 'photo'
        header("Location: dashboardBad.php");
        exit();
    }
} catch (Exception $e) {
    $_SESSION['errors'][] = $e->getMessage();
    header("Location: addMedia.php");
    exit();

}

require_once 'header.php';
require_once 'templates/messages.php';
?>

<h2 class="h2Sports mt-3 text-center">Ajouter une photo ou une vidéo</h2>

<div class="container col-12 col-md-6">
    <form method="POST" action="addMedia.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" id="date">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Type de média</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="media_type" id="type_photo" value="photo" checked> <!-- Changé 'image' en 'photo' -->
                <label class="form-check-label" for="type_photo">
                    Photo
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="media_type" id="type_video" value="video">
                <label class="form-check-label" for="type_video">
                    Vidéo
                </label>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="media_file" class="form-label">Fichier</label>
            <input type="file" class="form-control" name="media_file" id="media_file">
            <small id="file_help" class="form-text text-muted">
                Formats acceptés: JPG, PNG, GIF pour les photos. MP4, WebM, OGG pour les vidéos.
            </small>
        </div>
        <div class="d-flex justify-content-center">
            <?php addCSRFTokenToForm() ?>
            <button type="submit" class="btn btn-original bold my-3">Enregistrer</button>
        </div>
    </form>
</div>

<?php require_once('templates/footer.php'); ?>