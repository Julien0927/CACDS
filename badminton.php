<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';
require_once 'App/Classements.php';
require_once 'App/Results.php';
require_once 'App/Photos.php';

// Définition constante pour l'ID du badminton
const BADMINTON_SPORT_ID = 2;

// Initialisation des classes avec l'ID du badminton
try {
    // Initialisation de News
    $news = new App\News\News($db);
    
    // Initialisation de Classements avec l'ID du badminton
    $classement = new App\Classements\Classements($db);
    
    // Initialisation de Results avec l'ID du badminton explicite
    $results = new App\Results\Results($db, null, null, BADMINTON_SPORT_ID);
    
    // Initialisation de Photos
    $photos = new App\Photos\Photos($db);

    // Récupération de la page courante pour la pagination
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($pageActuelle < 1) $pageActuelle = 1;

    // Calcul du nombre total de pages
    $totalNews = $news->getTotalNewsBySport(BADMINTON_SPORT_ID);
    $totalPages = ceil($totalNews / $news->getNewsParPage());

    // Récupération des news pour la page actuelle
    $newsPageActuelle = $news->getNewsBySport(BADMINTON_SPORT_ID, $pageActuelle);

    // Récupération des noms des compétitions
    $cupNames = $results->getCupNames();
    $tournamentNames = $results->getTournamentNames();

    // Récupération des photos
    $photoData = $photos->getBySportId(BADMINTON_SPORT_ID);

    // Récupération des poules
    $stmt = $db->prepare("SELECT id FROM poules WHERE sport_id = :sport_id");
    $stmt->bindValue(':sport_id', BADMINTON_SPORT_ID, PDO::PARAM_INT);
    $stmt->execute();
    $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\Exception $e) {
    // Log l'erreur et affiche un message utilisateur
    error_log($e->getMessage());
    $error = "Une erreur est survenue lors du chargement de la page. Veuillez réessayer plus tard.";
}
?>

<div class="center">
    <h1 class="mt-3"><img src="/assets/icones/Square item bad.svg" class="me-3">BADMINTON</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>



<?php require_once 'templates/insideNav.php'; ?>

<div class="container-fluid ms-3">

    <!--Section News-->
<?php require_once 'templates/viewNews.php'; ?>

<section class="container-fluid">
        <h2 class="h2Sports line ">Compétitions</h2>
        <hr>
        <h3 id="calendrier" class="h3Sports text-center mt-3">Calendrier de la saison</h3>
        <a href="/assets/documents/Calendrier 2024 2025.pdf" class="center"><img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison"></a>

    <!--Section Résultats-->
<?php require_once 'templates/viewCompetitions.php';?>

    <!-- Section Documents -->
    <section class="container-fluid" id="documents">
        <h2 class="h2Sports">Documents</h2>
        <hr>
        <p>Accédez aux documents officiels et informations utiles.</p>
        <div class="row">
            <div class="d-flex flex-column justify-content-center col-12 col-md-4">
                <a href="/assets/documents/Demande_d_adhesions_2025.pdf" class="center"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
                <h3 class="h3Sports text-center">Demande d'adhésion</h3>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-4">
                <a href="/assets/documents/Demande_engagement_2025.pdf" class="center"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
                <h3 class="h3Sports text-center">Demande d'engagement</h3>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-4">
                <a href="/assets/documents/" class="center"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
                <h3 class="h3Sports text-center">Fiche d'inscription</h3>
            </div>
        </div>
        <div class="row mt-3">
            <div class="d-flex flex-column justify-content-center col-12 col-md-4">
                <a href="/assets/documents/Attestation_certificats_medicaux_2025.pdf" class="center"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
                <h3 class="h3Sports text-center">Attestation certificats médicaux</h3>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-4">
                <a href="/assets/documents/Autorisation_droit_image_2025.pdf" class="center"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
                <h3 class="h3Sports text-center">Autorisation de droit à l'image</h3>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-4">
                <a href="/assets/documents/Reglement_badminton_CACDS_Saison_2024_2025.pdf" class="center"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
                <h3 class="h3Sports text-center">Règlement badminton</h3>
            </div>
        </div>
    </section>

    <!-- Section Informations -->
<!--     <section class="container-fluid" id="informations">
        <h2 class="h2Sports">Informations</h2>
        <p>Toutes les informations à propos de nos événements et activités.</p>
    </section>
 -->
    <!-- Section Les Chiffres -->
    <section class="container-fluid" id="chiffres">
        <h2 class="h2Sports mt-3">Les Chiffres</h2>
        <hr>
        <p>Quelques statistiques clés pour mieux comprendre nos performances.</p>
    </section>

    <!-- Section Galerie Photos -->
<?php require_once 'templates/viewPhotos.php'; ?>
    <!-- Section Liens Utiles -->
    <section class="container-fluid" id="link">
        <h2 class="h2Sports">Liens Utiles</h2>
        <hr>
        <div class="d-flex justify-content-around mt-5">
            <a href="https://www.ffbad.org/" target="_blank">
                <img src="/assets/logos/FFBAD.png" alt="Fédération Française de Badminton" class="img-fluid">
            </a>
            <a href="https://www.badmintoneurope.com/Cms/" target="_blank">
                <img src="/assets/logos/BadEurope.jpg" alt="Badminton Europe" class="img-fluid">
            </a>
            <a href="https://bwfbadminton.com/" target="_blank">
                <img src="/assets/logos/WorldBad.jpg" alt="World Badminton Federation" class="img-fluid">
            </a>
            <a href="https://www.facebook.com/badcrechois/" target="_blank">
                <img src="/assets/logos/BadCrechois.jpg" alt="Page Facebook du Badminton Créchois" class="img-fluid">
            </a>
        </div>
    </section>
</div>
<?php
require_once 'templates/footer.php';
