<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';
require_once 'App/Classements.php';
require_once 'App/Results.php';
require_once 'App/Photos.php';

// Définition constante pour l'ID du badminton
const VOLLEY_SPORT_ID = 4;

// Initialisation des classes avec l'ID du badminton
try {
    // Initialisation de News
    $news = new App\News\News($db);
    
    // Initialisation de Classements avec l'ID du badminton
    $classement = new App\Classements\Classements($db);
    
    // Initialisation de Results avec l'ID du badminton explicite
    $results = new App\Results\Results($db, null, null, VOLLEY_SPORT_ID);
    
    // Initialisation de Photos
    $photos = new App\Photos\Photos($db);

    // Récupération de la page courante pour la pagination
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($pageActuelle < 1) $pageActuelle = 1;

    // Calcul du nombre total de pages
    $totalNews = $news->getTotalNewsBySport(VOLLEY_SPORT_ID);
    $totalPages = ceil($totalNews / $news->getNewsParPage());

    // Récupération des news pour la page actuelle
    $newsPageActuelle = $news->getNewsBySport(VOLLEY_SPORT_ID, $pageActuelle);

    // Récupération des noms des compétitions
    $cupNames = $results->getCupNames();
    $tournamentNames = $results->getTournamentNames();

    // Récupération des photos
    $photoData = $photos->getBySportId(VOLLEY_SPORT_ID);

    // Récupération des poules
    $stmt = $db->prepare("SELECT id FROM poules WHERE sport_id = :sport_id");
    $stmt->bindValue(':sport_id', VOLLEY_SPORT_ID, PDO::PARAM_INT);
    $stmt->execute();
    $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\Exception $e) {
    // Log l'erreur et affiche un message utilisateur
    error_log($e->getMessage());
    $error = "Une erreur est survenue lors du chargement de la page. Veuillez réessayer plus tard.";
}
?>

<div class="center">
    <h1 class="mt-3"><img src="/assets/icones/Square item Volley.svg" class="me-3">VOLLEYBALL</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!--Inside Nav-->
<section class="insideNav mt-2 mb-3">
  <div class="navbar navbar-expand-lg sportsNav">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem dropdown">
            <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown" style="text-decoration: none;" aria-expanded="false">
              Compétitions
            </a>
            <ul class="dropdown-menu dropdown-menu">
              <li><a class="dropdown-item" href="#calendrier">Calendrier de la saison</a></li>
              <li><a class="dropdown-item" href="#compet">Championnat</a></li>
              <li><a class="dropdown-item" href="#cup">Coupe</a></li>
              <li><a class="dropdown-item" href="#tournament">Tournois</a></li>
            </ul>
          </li>
        </ul>
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem dropdown">
            <a class="" href="#documents" style="text-decoration: none;">
              Documents CACDS
            </a>
          </li>
        </ul>
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem dropdown">
            <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown" style="text-decoration: none;" aria-expanded="false">
              Informations
            </a>
            <ul class="dropdown-menu dropdown-menu">
              <li><a class="dropdown-item" href="#lastSeason">Saisons précédentes</a></li>
            </ul>
          </li>
        </ul>
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem">
            <a class="" href="#chiffres" style="text-decoration: none;" >
              Chiffres et Statistiques
            </a>
          </li>
        </ul>
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem">
            <a class="" href="#gallery" style="text-decoration: none;">
              Photos et Vidéos
            </a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item insideItem">
            <a class="" href="#link" style="text-decoration: none;">
              Liens utiles
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
<section class="container-fluid">

    <!--Section News-->
<?php require_once 'templates/viewNews.php'; ?>

<section class="container-fluid">
        <h2 class="h2Sports line ">Compétitions</h2>
        <hr>
        <h3 id="calendrier" class="h3Sports text-center mt-3">Calendrier de la saison</h3>
        <a class="center"><img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison"></a>

    <!--Section Résultats-->
<?php require_once 'templates/viewCompetitionsBis.php';?>
    
    <!-- Section Documents -->
    <section class="container-fluid" id="documents">
        <h2 class="h2Sports mt-3">Documents CACDS</h2>
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
                <h3 class="h3Sports text-center">Feuille de match</h3>
                <a href="/assets/documents/feuille_de_matchVB.pdf" class="center" aria-label="Droit à l'image"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
        </div>
    </section>


    <!-- Section Informations -->
     <section class="container-fluid" id="informations">
        <h2 class="h2Sports mt-3">Informations</h2>
        <hr>
        <p class="lecture">Toutes les informations à propos de nos événements et activités.</p>
        <section class="container-fluid">
            <h3 class="h3Sports" id="lastSeason">Saisons précédentes
                <img class="toggle-icon" id="toggleCollapselastSeason" data-target="collapseContentlastSeason" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
            </h3>
            <div id="collapseContentlastSeason" class="collapse">
                <p class="lecture">
                    Vous trouverez dans cette rubrique les résultats et classements des saisons précédentes.
                </p>
                <div class="row center">
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Saisons</h4>
                        <select class="season-select mx-auto" id="seasonSelect">
                            <option value="">Sélectionner une saison</option>
                            <option value="23">Saison 2023-24</option>
                            <option value="22">Saison 2022-23</option>
                            <option value="21">Saison 2021-22 </option>
                            <option value="20">Saison 2020-21 (arrêtée)</option>
                            <option value="19">Saison 2019-20 (arrêtée)</option>
                            <option value="18">Saison 2018-19</option>
                            <option value="17">Saison 2017-18</option>
                            <option value="16">Saison 2016-17</option>
                            <option value="15">Saison 2015-16</option>
                        </select>
                        <div class="mx-auto" id="linkSeasonContainer"></div>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </section>

    <!-- Section Les Chiffres -->
    <section class="container-fluid" id="chiffres">
        <h2 class="h2Sports mt-3">Chiffres et Statistiques</h2>
        <hr>
        <p class="lecture">Quelques statistiques clés pour mieux comprendre nos performances.</p>
    </section>

    <!-- Section Galerie Photos -->
    <?php require_once 'templates/viewPhotos.php'; ?>
    <!-- Section Liens Utiles -->
    <section class="container-fluid" id="link">
        <h2 class="h2Sports mt-3">Liens Utiles</h2>
        <hr>
        <div class="d-flex justify-content-around mt-5">
        </div>
    </section>
<div id="backToTop" >
    <img src="/assets/icones/chevron-up.png">
</div>

</section>
<?php
require_once 'templates/footer.php';
