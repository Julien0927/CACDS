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
require_once 'App/Photos.php';

$messages = [];
$errors = [];

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert success connexion bold mx-auto">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

$competitionMapping = [
    'Championnat' => 1,
    'Coupe' => 2,
    'Tournoi' => 3
];

$competitionId = isset($_POST['results']) ? $competitionMapping[$_POST['results']] ?? null : null;
$pouleId = $_POST['poulesResults'] ?? null;

$news = new App\News\News($db);
$results = new App\Results\Results($db, $competitionId, $pouleId);
$classement = new App\Classements\Classements($db);
$photo = new App\Photos\Photos($db);

function handleDeletion($postKey, $boxKey, $entity, $singleMessage, $multipleMessage) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$postKey])) {
        // Vérifier si boxKey existe dans $_POST et est un tableau
        if (!isset($_POST[$boxKey]) || !is_array($_POST[$boxKey])) {
            $_SESSION['errors'] = ["Aucun élément sélectionné pour la suppression"];
            return;
        }

        foreach ($_POST[$boxKey] as $idToDelete) {
            $entity->setId($idToDelete);
            $entity->{"delete" . ucfirst(str_replace('delete', '', $postKey))}();
        }
        
        $message = count($_POST[$boxKey]) > 1 ? $multipleMessage : $singleMessage;
        $_SESSION['messages'] = [$message];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Gestion des mises à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNew']) && isset($_POST['newBox'])) {
    if (count($_POST['newBox']) === 1) {
        $newsId = $_POST['newBox'][0];
        header("Location: updateNews.php?id=$newsId");
        exit();
    } else {
        $_SESSION['errors'] = ["Veuillez sélectionner un seul article à modifier"];
    }
}

// Appel unique pour chaque type de suppression
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

handleDeletion(
    'deletePhoto',
    'photoBox',
    $photo,
    "La photo a bien été supprimée",
    "Les photos ont bien été supprimées"
);

$allNews = $news->getAllNews();
$allResults = $results->getResults();
$allClassements = $classement->getClassements();
$allPhotos = $photo->getBySportId();
?>

<h2 class="h1Sports center mt-3">Tableau de bord pétanque</h2>
<?php
require_once 'templates/dashArticles.php'; 
require_once 'templates/dashResults.php';
require_once 'templates/dashRanking.php';
require_once 'templates/dashPhotos.php';
require_once 'templates/footer.php';