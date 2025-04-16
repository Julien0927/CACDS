<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';
require_once 'App/Classements.php';
require_once 'App/Results.php';
require_once 'App/Photos.php';
require_once 'App/Documents.php';
require_once 'App/Annonces.php';
require_once 'App/Partenaires.php';

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

    // Initialisation de Documents
    $documentsManager = new App\Documents\Documents($db);

    // Initialisation de Partenaires
    $partenaireManager = new App\Partenaires\Partenaires($db);

    // Initialisation des annonces
    $annonces = new App\Annonces\Annonces($db);
    
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

    //Récupération des partenaires
    $listePartners = $partenaireManager->getAllPartenaires();

    //Récupération des annonces
    $listeAnnonces = $annonces->getAllAnnonces();

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
          <!-- <a href="/assets/documents/Calendrier 2024 2025.pdf" class="center"><img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison"></a> -->
          <?php
          $categories = [
                "Calendrier de la saison",
                
                ];
                            // Récupérer les documents pour chaque catégorie
                foreach ($categories as $categorie) {
                    $documents = $documentsManager->getDocumentsByCategory(htmlspecialchars($categorie));

                    if (!empty($documents)) {
                        foreach ($documents as $document) {
                            // Affichage de chaque document sous forme de carte
                            echo '<a href="' . htmlspecialchars($document['fichier']) . '" class="center" aria-label="">';
                            echo '<img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison">';
                            echo '</a>';
                        }
                    } else {
                        echo '<p>Aucun document disponible pour cette catégorie.</p>';
                    }
                }
            ?>    

      <!--Section Résultats-->
  <?php require_once 'templates/viewCompetitions.php';?>

      <!-- Section Documents -->
 <section class="container-fluid" id="documents">
    <h2 class="h2Sports">Documents CACDS</h2>
    <hr>
    <p class="lecture">Accédez aux documents officiels et informations utiles.</p>
    <div class="row center gap-3">

    <?php
    // Tableau des catégories de documents à afficher
    $categories = [
      "Règlement Badminton CACDS", 
      "Compte rendu réunion des capitaines",
      "Demande d'engagement",
      "Demande d'adhésions", 
      "Attestation certificats médicaux",
      "Autorisation droit à l'image"
    ];

    // Récupérer les documents pour chaque catégorie
    foreach ($categories as $categorie) {
        $documents = $documentsManager->getDocumentsByCategory(htmlspecialchars($categorie));

        if (!empty($documents)) {
            foreach ($documents as $document) {
                // Affichage de chaque document sous forme de carte
                echo '<div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">';
                echo '<h3 class="h3Sports text-center">' . ($document['categorie']) . '</h3>';
                echo '<a href="' . htmlspecialchars($document['fichier']) . '" class="center" aria-label="">';
                echo '<img src="/assets/icones/attestation-64.png" class="zoom">';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Aucun document disponible pour cette catégorie.</p>';
        }
    }
    ?>

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

    <div class="row justify-content-center align-items-center mt-5">
      <?php foreach ($listePartners as $partner): ?>
        <?php if (!empty($partner['logo'])): ?>
          <div class="col-6 col-md-3 mb-3 text-center">
            <a href="<?= htmlspecialchars($partner['url']) ?>" target="_blank" aria-label="Partenaire">
              <img src="<?= htmlspecialchars($partner['logo']) ?>" alt="Logo partenaire" class="img-fluid" style="max-height: 100px; border-radius: 5px;" loading="lazy">
            </a>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Section marché du badminton -->
   <?php 
   // Nombre d’annonces par page
        $annoncesParPage = 3;

        // Page actuelle (par défaut 1)
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calcul de l’offset
        $offset = ($page - 1) * $annoncesParPage;

        // Récupération des annonces paginées
        $listeAnnonces = $annonces->getAnnoncesLimite($annoncesParPage, $offset);

        // Nombre total d’annonces (pour le nombre de pages)
        $nombreTotal = $annonces->countAnnonces();
        $nombrePages = ceil($nombreTotal / $annoncesParPage);
        ?>
  <section class="container-fluid" id="badMarket">
    <h2 class="h2Sports mt-3">Marché du Badminton</h2>
    <hr>
    <p class="lecture">
        Cette rubrique créée à l’initiative d’un adhérent (merci Jérôme !) vous permet de mettre en ligne une annonce en lien avec du matériel de badminton pouvant intéresser un club ou un adhérent.<br>
        Alors n’hésitez pas à nous transmettre vos offres.
    </p>

    <div class="row justify-content-center">
        <?php foreach ($listeAnnonces as $annonce): ?>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="card shadow-sm fixed-height">
                    <?php if (!empty($annonce['image_path'])): ?>
                        <img src="<?= htmlspecialchars($annonce['image_path']) ?>" class="card-img-top" alt="Image annonce">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($annonce['texte'])) ?></p>
                    </div>
                    <div class="card-footer text-muted text-end small">
                        Publié le <?= date('d/m/Y', strtotime($annonce['created_at'])) ?>
                    </div>
                </div>
            </div>
        <?php endforeach;
        if ($nombrePages > 1): ?>
          <nav class="mt-4">
              <ul class="pagination justify-content-center">
                  <?php for ($i = 1; $i <= $nombrePages; $i++): ?>
                      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                          <a class="page-link" href="?tab=annonces&page=<?= $i ?>#badMarket"><?= $i ?></a>
                      </li>
                  <?php endfor; ?>
              </ul>
          </nav>
      <?php endif; ?>
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
