<?php
session_start();

require_once 'App/News.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'lib/tools.php';
require_once 'lib/config_session.php';

if(!empty($_POST)){
    if(isset($_POST["title"], $_POST["content"], $_POST["date"])
        && !empty($_POST["title"]) && !empty($_POST["content"]) && !empty($_POST["date"])){
    
    if (!empty($_FILES['image']['tmp_name'])) {
    // Récupère l'extension du fichier téléchargé
    $fileInfo = pathinfo($_FILES['image']['name']);
    $extension = strtolower($fileInfo['extension']); // Extension en minuscule pour éviter les erreurs

    // Liste des types de fichiers autorisés
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    // Vérifie si l'extension est autorisée
    if (in_array($extension, $allowedTypes)) {
        // Création du nom de fichier unique pour éviter les collisions
        $cleanedFileName = uniqid() . '-' . basename($fileInfo['basename']); // Ajoute l'extension
        $targetPath = _NEWS_IMG_PATH_ . $cleanedFileName;

        // Déplace le fichier téléchargé vers le répertoire cible
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Si le fichier a été déplacé avec succès, enregistre le chemin
            $imagePath = $targetPath;
        } else {
            // Erreur si l'upload échoue
            $errors[] = "Erreur lors de l'upload du fichier.";
        }
    } else {
        // Si le fichier n'est pas dans les types autorisés
        $errors[] = 'Le fichier doit être une image ou un PDF.';
    }
} else {
    // Si aucun fichier n'est téléchargé
    $imagePath = null;
}

        $news = new App\News\News($db);
        $news->setTitle($_POST["title"]);
        $news->setContent($_POST["content"]);
        $news->setImage(isset($imagePath) ? $imagePath : null);
        $news->setDate($_POST["date"]);
        $news->addNew();
        
        $_SESSION['messages'] = ["Votre article a bien été enregistré"];
        header("Location: addNews.php");
        exit();

        // Redirection ou affichage des messages ici
    } else {
        $_SESSION['errors']= ["Le formulaire est incomplet"];
    }
}

require_once 'header.php';
require_once 'templates/messages.php';
?>

<h2 class="h2Sports text-center mt-3">Ajouter un article</h2>

<div class="container col-12 col-md-6">
    <form method="POST" action="addNews.php" enctype="multipart/form-data" >
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="mb-3">
            <label for="content">Texte</label>
            <textarea rows="6" class="form-control" placeholder="Contenu de l'article" name="content" id="content"></textarea>
        </div>    
        <div class="row mb-3">
                <div class="d-flex  justify-content-center  align-items-center gap-3">  
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="date">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*,.pdf">
            </div>
        </div>
        <div class="d-flex justify-content-center">
        <?php addCSRFTokenToForm() ?>
        <button type="submit" class="btn btn-original bold justify-content-center my-3" value="">Enregistrer</button>
        </div>
    </form>
</div>
<?php
require_once ('templates/footer.php');
?>