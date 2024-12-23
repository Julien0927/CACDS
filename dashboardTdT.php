<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/auth.php';


if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    // Supprimer le message après affichage
    unset($_SESSION['success_message']);
}

?>

<h2 class="center text-center mt-3">Tableau de bord<br>Tennis de table</h2>
<h3>Ajouter un article</h3>
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
                        <?php foreach ($allArticles as $article) { ?>
                        <tr class="allArticles text-start">
                            <td><?=($article["date"])?></td>
                            <td><?=($article["title"])?></td>
                            <td class="content d-none d-md-table-cell"><?=($article["content"])?></td>
                            <td class="text-center"><img src="<?=($article["image"])?>" class="imgArticle"></td>
                            <td><input type="checkbox" name="articleBox[]" value="<?= $article['id'] ?>"></td>
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
                <a href="addArticles.php" class="btn btn-secondary">Ajouter un article</button></a>
                <button type="submit" class="btn btn-original" name="deleteArticle">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>
<h3>Mise à jour du classement</h3>
<h3>Mise à jour des résultats</h3>
<h3>Ajouter une photo</h3>

<?php
require_once 'templates/footer.php';