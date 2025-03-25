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
    $pageActuelle = isset($_GET['pageNews']) ? (int)$_GET['pageNews'] : 1;
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

<!--inside nav-->
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
              <li><a class="dropdown-item" href="#adhesion">Trombinoscope</a></li>
              <li><a class="dropdown-item" href="#outils">Boite à outils</a></li>
              <li><a class="dropdown-item" href="#palmares">Palmarès Badminton CACDS</a></li>
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
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem">
            <a class="" href="#partners" style="text-decoration: none;">
              Partenaires
            </a>
          </li>
        </ul>
        <ul class="navbar-nav me-5">
          <li class="nav-item insideItem">
            <a class="" href="#badMarket" style="text-decoration: none;">
              Marché du Badminton
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
          <h2 class="h2Sports line">Compétitions</h2>
          <hr>
          <h3 id="calendrier" class="h3Sports text-center mt-3">Calendrier de la saison</h3>
          <a href="/assets/documents/Calendrier 2024 2025.pdf" class="center"><img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison"></a>

      <!--Section Résultats-->
  <?php require_once 'templates/viewCompetitions.php';?>

      <!-- Section Documents -->
      <section class="container-fluid" id="documents">
          <h2 class="h2Sports">Documents CACDS</h2>
          <hr>
          <p class="lecture">Accédez aux documents officiels et informations utiles.</p>
          <div class="row center gap-3">
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Règlement Badminton CACDS</h3>
                <a href="/assets/documents/Reglement_badminton_CACDS_Saison_2024_2025.pdf" class="center" aria-label="reglement"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Compte rendu<br> Réunion des capitaines</h3>
                <a href="/assets/documents/CR_capitaines_4_septembre_2024.pdf" class="center" aria-label="Fiche d'inscription"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Demande d'Engagement</h3>
                <a href="/assets/documents/Demande_engagement_2025.pdf" class="center" aria-label="Demande d'engagement"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
          </div>
          <div class="row center gap-3 mt-3">
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Demande d'Adhésions</h3>
                <a href="/assets/documents/Demande_d_adhesions_2025.pdf" class="center" aria-label="Demande d'adhésion"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Attestation<br> Certificats médicaux</h3>
                <a href="/assets/documents/Attestation_certificats_medicaux_2025.pdf" class="center" aria-label="certificats medicaux"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
            <div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">
                <h3 class="h3Sports text-center">Autorisation<br> Droit à l'image</h3>
                <a href="/assets/documents/Autorisation_droit_image_2025.pdf" class="center" aria-label="Droit à l'image"><img src="/assets/icones/attestation-64.png" class="zoom"></a>
            </div>
          </div>
      </section>

      <!-- Section Informations -->
          <?php require_once 'templates/infos.php'; ?>

      <!-- Section Les Chiffres -->
      <section class="container-fluid" id="chiffres">
          <h2 class="h2Sports mt-3">Chiffres et Statistiques</h2>
          <hr>
          <p class="lecture">Quelques statistiques clés pour mieux comprendre l'évolution du Badminton CACDS.</p>
          <div class="d-flex flex-column justify-content-center mx-auto col-12 col-md-2">
            <a href="/assets/documents/effectifs_badminton_2024.pdf" class="center"><img src="/assets/icones/stats-64.png" alt="Chiffres du badminton" title="Chiffres du badminton"></a>
            <p class="lecture text-center">Effectifs Badminton</p>
          </div>
      </section>

      <!-- Section Galerie Photos -->
  <?php require_once 'templates/viewPhotos.php'; ?>


  <!-- Section partenaires -->
  <section class="container-fluid" id="partners">
    <h2 class="h2Sports mt-3">Partenaires</h2>
    <hr>
    <p class="lecture">Cette rubrique vous informe des partenariats passés avec la CACDS, au nom et pour le compte de ses adhérents.</p>
    <div class="row">
      <div class="col-12 col">
        <a href="https://tennispassion79.com/" target="_blank" aria-label="Tennis Passion">
          <img src="/assets/logos/tennis-passion.jpg" style="border-radius: 5px" alt="Tennis passion">
        </a>
      </div>
    </div>
  </section>

  <!-- Section marché du badminton -->
  <section class="container-fluid" id="badMarket">
    <h2 class="h2Sports mt-3">Marché du Badminton</h2>
    <hr>
    <p class="lecture">
      Cette rubrique créée à l’initiative d’un adhérent (merci Jérôme !) vous permet de mettre en ligne une annonce en lien avec du matériel de badminton pouvant intéresser un club ou un adhérent.<br>
      Alors n’hésitez pas à nous transmettre vos offres.
    </p>
    <div class="row">
      <div class="col-12 col"></div>
    </div>
  </section>

  <!-- Section Liens Utiles -->
  <section class="container-fluid" id="link">
      <h2 class="h2Sports mt-3">Liens Utiles</h2>
      <hr>
      <div class="d-flex flex-column justify-content-around mt-3" >
          <a href="https://www.ffbad.org/" target="_blank" class="lecture" aria-label="Fédération Française de Badminton" style="text-decoration: none;">
              <img src="/assets/icones/fleche-droite-30.png" alt="fleche droite" class="mb-1">Lien vers la Fédération Française de Badminton
          </a>
          <a href="https://www.badmintoneurope.com/Cms/" target="_blank" class="lecture" aria-label="Badminton Europe" style="text-decoration: none;">
              <img src="/assets/icones/fleche-droite-30.png" alt="fleche droite" class="mb-1">Lien vers Badminton Europe
          </a>
          <a href="https://bwfbadminton.com/" target="_blank" class="lecture" aria-label="World Badminton Federation" style="text-decoration: none;">
              <img src="/assets/icones/fleche-droite-30.png" alt="fleche droite" class="mb-1">Lien vers la World Badminton Federation
          </a>
      </div>
  </section>


      <!-- Bouton Retour en haut -->
      <div id="backToTop" >
          <img src="/assets/icones/chevron-up.png" alt="Retour en haut" loading="lazy">
      </div>
</section>
<?php
require_once 'templates/footer.php';
