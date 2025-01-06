<?php
ob_start();
session_start();

require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/auth.php';
require_once 'App/News.php';
require_once 'lib/pdo.php';
require_once 'templates/messages.php';

$messages = [];
$errors = [];

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    // Supprimer le message après affichage
    unset($_SESSION['success_message']);
}

$news = new App\News\News($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteNew'])) {
    foreach ($_POST['newBox'] as $idToDelete) {
        $news->setId($idToDelete);
        $news->deleteNew();
    }
    if (count($_POST['newBox']) > 1) {

        $_SESSION['messages'] = ["Les articles ont bien été supprimés"];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['messages'] = ["L'article a bien été supprimé"];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    }
}

$allNews = $news->getAllNews();
?>

<h2>Volley</h2>
<form method="POST">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Titre</th>
                            <th class="d-none d-md-table-cell">Contenu</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allNews as $new) { ?>
                        <tr class="allNews text-start">
                            <td><?=($new["date"])?></td>
                            <td><?=($new["title"])?></td>
                            <td class="content d-none d-md-table-cell"><?=($new["content"])?></td>
                            <td class="text-center"><img src="<?=($new["image"])?>" class="imgNew"></td>
                            <td><input type="checkbox" name="newBox[]" value="<?= $new['id'] ?>"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-end">
            <div class=" my-3">
                <a href="addNews.php" class="btn btn-secondary">Ajouter un article</button></a>
                <button type="submit" class="btn btn-original" name="deleteNew">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>

<?php
require_once 'templates/footer.php';
