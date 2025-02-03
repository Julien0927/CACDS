<?php
require_once 'App/News.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'lib/tools.php';
require_once 'lib/config_session.php';

// Vérifier si le formulaire est soumis
if (!empty($_POST)) {
    if (isset($_POST["title"], $_POST["content"], $_POST["date"]) 
    && !empty($_POST["title"]) && !empty($_POST["content"]) && !empty($_POST["date"])) {

try {
    $news = new App\News\News($db);
    $news->setId($_GET['id']); // Assure-toi que l'ID est passé dans l'URL
    $news->setTitle($_POST["title"]);
    $news->setContent($_POST["content"]);
    $news->setDate($_POST["date"]);
    
    
    // Gestion de l'image
    if (!empty($_FILES['image']['tmp_name'])) {
        $checkImage = getimagesize($_FILES['image']['tmp_name']);
        if ($checkImage !== false) {
            $rawFileName = $_FILES['image']['name'];
            $cleanedFileName = strip_tags($rawFileName);
            $fileName = uniqid() . '-' . slugify($cleanedFileName);
            $uploadPath = _NEWS_IMG_PATH_ . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $news->setImage($uploadPath);
            } else {
                throw new Exception("Erreur lors de l'upload de l'image");
            }
        } else {
            throw new Exception("Le fichier doit être une image valide");
        }
    }
    
    // Mise à jour de l'article
    $news->updateNew();
    $_SESSION['messages'] = ["Votre article a bien été modifié"];
    header("Location: addNews.php");
    exit();
} catch (Exception $e) {
    $_SESSION['errors'] = [$e->getMessage()];
}
} else {
    $_SESSION['errors'] = ["Le formulaire est incomplet"];
}
}

if (isset($_GET['id'])) {
    $news = new App\News\News($db);
    $newsData = $news->getNewById($_GET['id']);
    if (!$newsData) {
        $_SESSION['errors'] = ["Article introuvable"];
        header('Location: dashboardBad.php');
        exit();
    }
} else {
    $_SESSION['errors'] = ["Aucun article trouvé"];
    header('Location: dashboardBad.php');
    exit();
}

require_once 'header.php';
require_once 'templates/messages.php';
?>

<h2 class="h2Sports text-center mt-3">Modifier un article</h2>

<div class="container col-12 col-md-6">
    <form method="POST" action="updateNews.php?id=<?=htmlspecialchars($_GET['id'])?>" enctype="multipart/form-data" >
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= ($newsData["title"]) ?>">
        </div>
        <div class="mb-3">
            <label for="content">Texte</label>
            <textarea rows="6" class="form-control" placeholder="Contenu de l'article" name="content" id="content"><?=($newsData["content"]) ?></textarea>
        </div>    
        <div class="row mb-3">
            <div class="d-flex justify-content-center align-items-center gap-3">  
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="date" value="<?= htmlspecialchars($newsData["date"] ?? '') ?>">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" name="image" id="image">
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <?php addCSRFTokenToForm() ?>
            <button type="submit" class="btn btn-original bold my-3">Enregistrer</button>
        </div>
    </form>
</div>

<?php
require_once 'templates/footer.php';
?>
