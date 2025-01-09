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
            $checkImage = getimagesize($_FILES['image']['tmp_name']);
            if ($checkImage !== false) {
                $rawFileName = $_FILES['image']['name'];
                $cleanedFileName = strip_tags($rawFileName);
                $fileName = uniqid() . '-' . slugify($cleanedFileName);
                move_uploaded_file($_FILES['image']['tmp_name'], _NEWS_IMG_PATH_ . $fileName);
                $imagePath = _NEWS_IMG_PATH_ . $fileName;
            } else {
                $errors[] = 'Le fichier doit être une image';
            }
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

<h2>Ajouter un article</h2>

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
                <input type="file" class="form-control" name="image" id="image">
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