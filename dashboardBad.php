<?php
ob_start();
session_start();

require_once 'header.php';
require_once 'lib/auth.php';
require_once 'lib/pdo.php';
require_once 'lib/security.php';
require_once 'templates/nav.php';
require_once 'templates/messages.php';
require_once 'App/News.php';
require_once 'App/Results.php';
require_once 'App/Classements.php';

$messages = [];
$errors = [];

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    // Supprimer le message après affichage
    unset($_SESSION['success_message']);
}

$competitionMapping = [
    'Championnat' => 1,
    'Coupe' => 2,
    'Tournoi' => 3
];

/* $competitionId = $_POST['results'] ?? null; // Récupérer l'ID de la compétition depuis POST*/
$competitionId = isset($_POST['results']) ? $competitionMapping[$_POST['results']] ?? null : null;
$pouleId = $_POST['poulesResults'] ?? null; // Récupérer l'ID de la poule depuis POST

$news = new App\News\News($db);
$results = new App\Results\Results($db, $competitionId, $pouleId);
$classement = new App\Classements\Classements($db);

/* if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteNew'])) {
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
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteResult'])) {
    foreach ($_POST['resultBox'] as $idToDelete) {
        $results->setId($idToDelete);
        $results->deleteResult();
    }
    if (count($_POST['resultBox']) > 1) {

        $_SESSION['messages'] = ["Les résultats ont bien été supprimés"];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['messages'] = ["Le résultat a bien été supprimé"];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    }
}
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteClassement'])) {
        foreach ($_POST['rankingBox'] as $idToDelete) {
            $classement->setId($idToDelete);
            $classement->deleteClassement();
        }
        if (count($_POST['rankingBox']) > 1) {
    
            $_SESSION['messages'] = ["Les classements ont bien été supprimés"];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $_SESSION['messages'] = ["Le classement a bien été supprimé"];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
    
        }
    }
 */
function handleDeletion($postKey, $boxKey, $entity, $singleMessage, $multipleMessage) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$postKey])) {
        foreach ($_POST[$boxKey] as $idToDelete) {
            $entity->setId($idToDelete);
            $entity->{"" . ucfirst($postKey)}();
        }
        $message = count($_POST[$boxKey]) > 1 ? $multipleMessage : $singleMessage;
        $_SESSION['messages'] = [$message];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Appeler la fonction pour chaque type de suppression
handleDeletion(
    'deleteNew',
    'newBox',
    $news,
    "L'article a bien été supprimé",
    "Les articles ont bien été supprimés"
);

handleDeletion(
    'deleteResult',
    'resultBox',
    $results,
    "Le résultat a bien été supprimé",
    "Les résultats ont bien été supprimés"
);

handleDeletion(
    'deleteClassement',
    'rankingBox',
    $classement,
    "Le classement a bien été supprimé",
    "Les classements ont bien été supprimés"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification si l'on souhaite supprimer ou modifier
    if (isset($_POST['deleteNew']) && isset($_POST['newBox'])) {
        // Traitement de la suppression
        foreach ($_POST['newBox'] as $newsId) {
            // Suppression de l'article en appelant la méthode deleteNew() par exemple
            $news = new App\News\News($db);
            $news->setId($newsId);
            $news->deleteNew();
        }
        $_SESSION['messages'] = ["Les articles ont bien été supprimés"];
    } elseif (isset($_POST['updateNew']) && isset($_POST['newBox'])) {
        // Si l'article à modifier est sélectionné
        if (count($_POST['newBox']) === 1) {
            $newsId = $_POST['newBox'][0];
            // Rediriger vers la page de modification de l'article
            header("Location: updateNews.php?id=$newsId");
            exit();
        } else {
            $_SESSION['errors'] = ["Veuillez sélectionner un seul article à modifier"];
        }
    }
}


$allNews = $news->getAllNews();
$allResults = $results->getResults();
$allClassements = $classement->getClassements();



?>


<h2 class="h2Sports center mt-3">Tableau de bord badminton</h2>
<?php
require_once 'templates/dashArticles.php'; 
require_once 'templates/dashResults.php';
?>

<h3 class="h2Sports ms-2">Mise à jour des classements</h3>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead>
                        <tr>
                            <th>Compétition</th>
                            <th>Poule</th>
                            <th>Journée</th>
                            <th>Classement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allClassements)) { ?>
                            <?php foreach ($allClassements as $classement) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($classement["competition_type"]) ?></td>
                                    <td><?= $classement["competition_type"] === 'Championnat' ? htmlspecialchars($classement["poule_id"]) : '-' ?></td>
                                    <td><?= $classement["competition_type"] === 'Championnat' ? htmlspecialchars($classement["day_number"]) : '-' ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($classement["classement_pdf_url"]) ?>" title="<?= ($classement["classement_pdf_url"]) ?>" target="_blank">
                                            <img src="/assets/icones/pdf-250.png" alt="pdf" class="imgNew">
                                        </a>
                                    </td>
                                    <td><input type="checkbox" name="rankingBox[]" value="<?= $classement['id'] ?>"></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5">Aucun résultat trouvé.</td>
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
                <a href="addRanking.php" class="btn btn-card">Ajouter un classement</button></a>
                <?php addCSRFTokenToForm() ?>
                <button type="submit" class="btn btn-original" name="deleteClassement">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>


<h3 class="h2Sports ms-2">Ajouter une photo</h3>
<?php
require_once 'templates/footer.php';