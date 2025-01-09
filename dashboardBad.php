<?php
ob_start();
session_start();

require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/auth.php';
require_once 'lib/pdo.php';
require_once 'templates/messages.php';
require_once 'App/News.php';
require_once 'App/Results.php';
$messages = [];
$errors = [];

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    // Supprimer le message après affichage
    unset($_SESSION['success_message']);
}

$competitionId = $_POST['results'] ?? null; // Récupérer l'ID de la compétition depuis POST
$pouleId = $_POST['poulesResults'] ?? null; // Récupérer l'ID de la poule depuis POST

$news = new App\News\News($db);
$Results = new App\Results\Results($db, $competitionId, $pouleId);

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

$allResults = $Results->getResults();


?>

<h2 class="h2Sports text-center mt-3">Tableau de bord Badminton</h2>
<?php
require_once 'templates/dashArticles.php'; 

?>
<h3 class="h2Sports ms-2">Gestion des résultats</h3>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead>
                        <tr>
                            <th>Poule</th>
                            <th>Journée</th>
                            <th>Fichier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allResults)) { ?>
                            <?php foreach ($allResults as $result) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($result["poule_id"]) ?></td>
                                    <td><?= htmlspecialchars($result["day_number"]) ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($result["result_pdf_url"]) ?>" target="_blank">
                                            Voir le fichier
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="3">Aucun résultat trouvé.</td>
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
                <a href="addScores.php" class="btn btn-card">Ajouter un résultat</button></a>
                <button type="submit" class="btn btn-second" name="deleteNew">Modifier</button>
                <button type="submit" class="btn btn-original" name="deleteNew">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>

<h3 class="h2Sports ms-2">Mise à jour du classement</h3>
<h3 class="h2Sports ms-2">Ajouter une photo</h3>
<?php
require_once 'templates/footer.php';
