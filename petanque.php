<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';
require_once 'App/Classements.php';
require_once 'App/Results.php';
require_once 'App/Photos.php';

// Définition constante pour l'ID du badminton
const PETANQUE_SPORT_ID = 3;

// Initialisation des classes avec l'ID du badminton
try {
    // Initialisation de News
    $news = new App\News\News($db);
    
    // Initialisation de Classements avec l'ID du badminton
    $classement = new App\Classements\Classements($db);
    
    // Initialisation de Results avec l'ID du badminton explicite
    $results = new App\Results\Results($db, null, null, PETANQUE_SPORT_ID);
    
    // Initialisation de Photos
    $photos = new App\Photos\Photos($db);

    // Récupération de la page courante pour la pagination
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($pageActuelle < 1) $pageActuelle = 1;

    // Calcul du nombre total de pages
    $totalNews = $news->getTotalNewsBySport(PETANQUE_SPORT_ID);
    $totalPages = ceil($totalNews / $news->getNewsParPage());

    // Récupération des news pour la page actuelle
    $newsPageActuelle = $news->getNewsBySport(PETANQUE_SPORT_ID, $pageActuelle);

    // Récupération des noms des compétitions
    $cupNames = $results->getCupNames();
    $tournamentNames = $results->getTournamentNames();

    // Récupération des photos
    $photoData = $photos->getBySportId(PETANQUE_SPORT_ID);

    // Récupération des poules
    $stmt = $db->prepare("SELECT id FROM poules WHERE sport_id = :sport_id");
    $stmt->bindValue(':sport_id', PETANQUE_SPORT_ID, PDO::PARAM_INT);
    $stmt->execute();
    $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\Exception $e) {
    // Log l'erreur et affiche un message utilisateur
    error_log($e->getMessage());
    $error = "Une erreur est survenue lors du chargement de la page. Veuillez réessayer plus tard.";
}
?>

<div class="center">
    <h1 class="mt-3"><img src="/assets/icones/Square item petanque.svg" class="me-3">PÉTANQUE</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php require_once 'templates/insideNav.php'; ?>

<section class="container-fluid">

    <!--Section News-->
<?php require_once 'templates/viewNews.php'; ?>

<section class="container-fluid">
        <h2 class="h2Sports line ">Compétitions</h2>
        <hr>
        <h3 id="calendrier" class="h3Sports text-center mt-3">Calendrier de la saison</h3>
        <a  class="center" aria-label="calendrier"><img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison"></a>

    <!-- Section Compétitions -->

        <!--Championnat-->
        <h3 class="h3Sports" id="compet">Championnat</h3>
<!--         <p class="lecture">Le championnat regroupe plusieurs poules où évoluent 8 équipes. Les matchs se déroulent en phase aller-retour.<br> 
            En fin de saison, les deux premiers montent en poule supérieure, les deux derniers descendent en poule inférieure</p>
 -->            <label for="sports" class="form-label me-2">Sélectionnez votre poule</label>
            <select name="poules" id="poules">
            <?php foreach ($poules as $poule): ?>
                <option value="<?= $poule['id'] ?>"><?= htmlspecialchars($poule['id']) ?></option>
            <?php endforeach; ?>

            </select>
            <div class="row d-flex justify-content-center mt-4">
                <div class="col-md-3">
                    <h4>Résultats</h4>
                    <div id="resultats-container">
                        <!-- Les résultats seront chargés ici -->
                    </div>
                </div>
                <div class="col-md-3">
                    <h4>Classements</h4>
                    <div id="classement-container">
                        <!-- Le classement sera chargé ici -->
                    </div>
                </div>
            </div>
        <!--Coupe-->
        <h3 class="h3Sports" id="cup">Coupe</h3>
<!--         <p class="lecture">La coupe est une compétition à élimination directe. Les matchs se jouent en 3 sets gagnants.</p>
 -->        <label for="coupe" class="form-label me-2">Sélectionnez votre information</label>
        <?php
        $cupNames = $results->getCupNames();
        ?>
        <select name="cupName" id="cupName">
            <?php foreach ($cupNames as $cupName): ?>
                <option value="<?= $cupName['name'] ?>"><?= ($cupName['name']) ?></option>
            <?php endforeach; ?>
        </select>
 
        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-3">
                <h4>Résultats</h4>
                <div id="resultCup-container">
                    <!-- Les résultats seront chargés ici -->
                </div>
            </div>
            <!-- <div class="col-md-3">
                <h4>Classements</h4>
                <div id="rankingCup-container"> -->
                    <!-- Le classement sera chargé ici -->
                <!-- </div>
            </div> -->
        </div>
        </div>
        <!--Tournois-->
        <h3 class="h3Sports" id="tourn">Tournois</h3>
<!--         <p class="lecture">Les tournois sont des compétitions individuelles ou par équipes sur une ou plusieurs journées.</p>
 -->        <label for="tournament" class="form-label me-2">Sélectionnez votre information</label>
        <?php
        $tournamentNames = $results->getTournamentNames();
        ?>
        <select name="tournamentName" id="tournamentName">
            <?php foreach ($tournamentNames as $tournamentName): ?>
                <option value="<?= $tournamentName['name'] ?>"><?= ($tournamentName['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-3">
                <h4>Résultats</h4>
                <div id="resultTournament-container">
                    <!-- Les résultats seront chargés ici -->
                </div>
            </div>
            <!-- <div class="col-md-3">
                <h4>Classements</h4>
                <div id="rankingTournament-container"> -->
                    <!-- Le classement sera chargé ici -->
                <!-- </div>
            </div> -->
        </div>
</section>



    <!-- Section Documents -->
    <section class="container-fluid" id="documents">
        <h2 class="h2Sports">Documents</h2>
        <hr>
        <p class="lecture">Accédez aux documents officiels et informations utiles.</p>
        <div class="row center gap-3">
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Demande d'adhésion</h3>
                <a href="/assets/documents/Demande_d_adhesions_2025.pdf" class="center" aria-label="Demande d'adhesion"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Demande d'engagement</h3>
                <a href="/assets/documents/Demande_engagement_2025.pdf" class="center" aria-label="Demande d'engagement"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Fiche d'inscription</h3>
                <a href="/assets/documents/" class="center" aria-label="Fiche d'inscription"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
        </div>
        <div class="row center gap-3 mt-3">
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Attestation certificats médicaux</h3>
                <a href="/assets/documents/Attestation_certificats_medicaux_2025.pdf" class="center" aria-label="certificats medicaux"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Autorisation de droit à l'image</h3>
                <a href="/assets/documents/Autorisation_droit_image_2025.pdf" class="center" aria-label="Droit à l'image"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Règlement pétanque</h3>
                <a href="/assets/documents/reglement_petanque.pdf" class="center" aria-label="reglement"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
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
        <h2 class="h2Sports mt-3">Chiffres</h2>
        <hr>
        <p class="lecture">Quelques statistiques clés pour mieux comprendre nos performances.</p>
    </section>

    <!-- Section Galerie Photos -->
    <?php require_once 'templates/viewPhotos.php'; ?>

    <!-- Section Liens Utiles -->
    <section class="container-fluid" id="link">
        <h2 class="h2Sports">Liens Utiles</h2>
        <hr>
    </section>
    <div id="backToTop" >
        <img src="/assets/icones/chevron-up.png">
    </div>

</section>
<?php
require_once 'templates/footer.php';