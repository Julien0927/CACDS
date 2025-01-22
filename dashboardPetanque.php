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
    // Supprimer le message après affichage
    unset($_SESSION['success_message']);
}

$competitionMapping = [
    'Championnat' => 1,
    'Coupe' => 2,
    'Tournoi' => 3
];

$competitionId = isset($_POST['results']) ? $competitionMapping[$_POST['results']] ?? null : null;
$pouleId = $_POST['poulesResults'] ?? null; // Récupérer l'ID de la poule depuis POST

$news = new App\News\News($db);
$results = new App\Results\Results($db, $competitionId, $pouleId);
$classement = new App\Classements\Classements($db);
$photo = new App\Photos\Photos($db);

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
handleDeletion(
    'deletePhoto',
    'photoBox',
    $photo,
    "La photo a bien été supprimée",
    "Les photos ont bien été supprimées"
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

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['deletePhoto']) && isset($_POST['photoBox'])) {
        foreach ($_POST['photoBox'] as $photoId) {
            $photo = new App\Photos\Photos($db);
            $photo->setId($photoId);
            $photo->deletePhoto();
        }
        $_SESSION['messages'] = ["Les photos ont bien été supprimées"];
    }
}

if (isset($_POST['deletePhoto']) && isset($_POST['photoBox'])) {
    $photo = new App\Photos\Photos($db);
    
    foreach ($_POST['photoBox'] as $photoId) {
        $photo->setId($photoId);
        $photo->deletePhoto();
    }
    
    $_SESSION['success_message'] = "Les photos sélectionnées ont été supprimées";
    header('Location: dashboardPetanque.php');
    exit;
}

$allNews = $news->getAllNews();
$allResults = $results->getResults();
$allClassements = $classement->getClassements();
$allPhotos = $photo->getBySportId();

?>


<h2 class="h2Sports center mt-3">Tableau de bord badminton</h2>
<?php
require_once 'templates/dashArticles.php'; 
require_once 'templates/dashResults.php';
require_once 'templates/dashRanking.php';
require_once 'templates/dashPhotos.php';

require_once 'templates/footer.php';