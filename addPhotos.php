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
/* require_once 'App/Photos.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'lib/tools.php';
require_once 'lib/config_session.php';

$photo = new App\Photos\Photos($db);
 if(!empty($_POST)){
    if(isset($_POST["title"])
        && !empty($_POST["title"])){
    
        if (!empty($_FILES['image']['tmp_name'])) {
            $checkImage = getimagesize($_FILES['image']['tmp_name']);
            if ($checkImage !== false) {
                $rawFileName = $_FILES['image']['name'];
                $cleanedFileName = strip_tags($rawFileName);
                $fileName = uniqid() . '-' . slugify($cleanedFileName);
                move_uploaded_file($_FILES['image']['tmp_name'], _IMG_PATH_ . $fileName);
                $imagePath = _IMG_PATH_ . $fileName;
            } else {
                $errors[] = 'Le fichier doit être une image';
            }
        }
        
        $photos = new App\Photos\Photos($db);
        $photos->setTitle($_POST["title"]);
        $photos->setDate($_POST["date"]);
        $photos->setSportId($sportId);
        $photos->setImage(isset($imagePath) ? $imagePath : null);
        $photos->addPhoto($db);
        
        $_SESSION['messages'] = ["Votre photo a bien été enregistré"];
        header("Location: dashboardBad.php");
        exit();

        // Redirection ou affichage des messages ici
    } else {
        $_SESSION['errors']= ["Le formulaire est incomplet"];
    }
}
 */ 

/*  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && isset($_POST['title']) && isset($_POST['date'])) {
        $uploadedPath = $photo->uploadPhoto($_FILES['image']);
        
        if ($uploadedPath) {
            if ($photo->addPhoto($_POST['title'], $uploadedPath)) {
                $_SESSION['success_message'] = "La photo a été ajoutée avec succès";
                header('Location: dashboardBad.php');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout de la photo";
            }
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'upload du fichier";
        }
    }
    
    $photo = new App\Photos\Photos($db);
    $photo -> setTitle($_POST["title"]);
    $photo -> setDate($_POST["date"]);
    $photo -> setImage(isset($imagePath) ? $imagePath : null);
    $photo ->addPhoto($title, $image);

    $_SESSION['messages'] = ["Votre photo a bien été enregistrée"];
    header("Location: addPhotos.php");
    exit();

    // Redirection ou affichage des messages ici
} else {
    $_SESSION['errors']= ["Le formulaire est incomplet"];
}
 */
/* if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du CSRF token
    if (!verifyCSRFToken()) {
        $_SESSION['errors'][] = "Jeton de sécurité invalide";
        header("Location: addPhotos.php");
        exit;
    }

    // Vérification des champs requis
    if (empty($_POST['title']) || empty($_POST['date'])) {
        $_SESSION['errors'][] = "Le titre et la date sont obligatoires";
        header("Location: addPhotos.php");
        exit;
    }

    // Traitement de l'image
    if (!empty($_FILES['image']['tmp_name'])) {
        $checkImage = getimagesize($_FILES['image']['tmp_name']);
        if ($checkImage !== false) {
            $rawFileName = $_FILES['image']['name'];
            $cleanedFileName = strip_tags($rawFileName);
            $fileName = uniqid() . '-' . slugify($cleanedFileName);
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], _IMG_PATH_ . $fileName)) {
                // Configuration de l'objet photo avec les données du formulaire
                $photo->setTitle($_POST['title']);
                $photo->setDate($_POST['date']);
                $photo->setSportId($sportId);
                $photo->setImage($fileName);

                // Tentative d'ajout dans la base de données
                if ($photo->addPhoto()) {
                    $_SESSION['messages'][] = "La photo a été ajoutée avec succès";
                    header("Location: dashboardBad.php");
                    exit;
                } else {
                    // Suppression du fichier uploadé en cas d'échec de l'insertion en BDD
                    if (file_exists(_IMG_PATH_ . $fileName)) {
                        unlink(_IMG_PATH_ . $fileName);
                    }
                    $_SESSION['errors'][] = "Erreur lors de l'enregistrement dans la base de données";
                }
            } else {
                $_SESSION['errors'][] = "Erreur lors de l'upload du fichier";
            }
        } else {
            $_SESSION['errors'][] = "Le fichier doit être une image valide";
        }
    } else {
        $_SESSION['errors'][] = "Veuillez sélectionner une image";
    }

    // Redirection en cas d'erreur
    if (!empty($_SESSION['errors'])) {
        header("Location: addPhotos.php");
        exit;
    } */


require_once 'header.php';
require_once 'templates/messages.php';
?>

<h2 class="h2Sports ms-2 mt-3">Ajouter une photo</h2>

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
        <button type="submit" class="btn btn-original justify-content-center my-3" value="">Enregistrer</button>
        </div>
    </form>
</div>
<?php
require_once ('templates/footer.php');
?>