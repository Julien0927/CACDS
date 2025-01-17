<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';
require_once 'App/Classements.php';
require_once 'App/Results.php';

$news = new App\News\News($db);
$classement = new App\Classements\Classements($db);
$sportId = 2; // ID du badminton

// Récupération de la page courante
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageActuelle < 1) $pageActuelle = 1;

// Calcul du nombre total de pages
$totalNews = $news->getTotalNewsBySport($sportId);
$totalPages = ceil($totalNews / $news->getNewsParPage());

// Récupération des news pour la page actuelle
$newsPageActuelle = $news->getNewsBySport($sportId, $pageActuelle);

// Récupération des noms des compétitions
$cupNames = $classement->getCupNames();
$tournamentNames = $classement->getTournamentNames();

?>

<div class="center">
    <h1 class="mt-3"><img src="/assets/icones/Square item bad.svg" class="me-3">BADMINTON</h1>
</div>


<?php
require_once 'templates/insideNav.php';
/*Affichage dynamique des poules*/
$sql = "SELECT id FROM poules WHERE sport_id = :sport_id";
$stmt = $db->prepare($sql);
$stmt->bindValue(':sport_id', $sportId, PDO::PARAM_INT);
$stmt->execute();
$poules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid ms-3">
<!-- Section Actualités -->
<section class="container-fluid">
    <h2 class="h2Sports">Actualités</h2>
    <p>Retrouvez ici les dernières nouvelles importantes concernant le club.</p>
    <div class="row mb-2">
        <?php foreach ($newsPageActuelle as $new): ?>
            <?php include 'templates/partial_news.php'; ?>
        <?php endforeach; ?>
    </div>
    
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Navigation des articles">
        <ul class="pagination justify-content-center">
            <?php if ($pageActuelle > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pageActuelle - 1 ?>" aria-label="Précédent">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $pageActuelle == $i ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            
            <?php if ($pageActuelle < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pageActuelle + 1 ?>" aria-label="Suivant">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</section>
<!-- Section Compétitions -->
    <section>
        <h2 class="h2Sports ms-5">Compétitions</h2>
        <h3 id="calendrier" class="h3Sports ms-4 text-center">Calendrier de la saison</h3>
        <a href="/assets/documents/Calendrier 2024 2025.pdf" class="center"><img src="/assets/icones/calendrier.gif" alt="calendrier saison" titre="Calendrier de la saison"></a>

        <!--Championnat-->
        <h3 class="h3Sports ms-4" id="compet">Championnat</h3>
        <p>Le championnat regroupe plusieurs poules où évoluent 8 équipes. Les matchs se déroulent en phase aller-retour.<br> 
            En fin de saison, les deux premiers montent en poule supérieure, les deux derniers descendent en poule inférieure</p>
            <label for="sports" class="form-label me-2">Sélectionnez votre poule</label>
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
                    <h4>Classement</h4>
                    <div id="classement-container">
                        <!-- Le classement sera chargé ici -->
                    </div>
                </div>
            </div>
        <!--Coupe-->
        <h3 class="h3Sports ms-4" id="cup">Coupe</h3>
        <p>La coupe est une compétition à élimination directe. Les matchs se jouent en 3 sets gagnants.</p>
        <label for="coupe" class="form-label me-2">Sélectionnez votre coupe</label>
        <?php
        $results = new App\Results\Results($db);
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
            <div class="col-md-3">
                <h4>Classement</h4>
                <div id="rankingCup-container">
                    <!-- Le classement sera chargé ici -->
                </div>
            </div>
        </div>


        <!--Tournois-->
        <h3 class="h3Sports ms-4" id="tourn">Tournois</h3>
        <p>Les tournois sont des compétitions individuelles ou par équipes sur une ou plusieurs journées.</p>
        <label for="tournament" class="form-label me-2">Sélectionnez votre tournoi</label>
        <?php
        $results = new App\Results\Results($db);
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
            <div class="col-md-3">
                <h4>Classement</h4>
                <div id="rankingTournament-container">
                    <!-- Le classement sera chargé ici -->
                </div>
            </div>
        </div>
</section>

    <!-- Section Documents -->
    <section>
        <h2 class="h2Sports">Documents</h2>
        <p>Accédez aux documents officiels et informations utiles.</p>
        <h3 class="h3Sports ms-4">CR Réunion des capitaines</h3>
        <h3 class="h3Sports ms-4">Demande d'adhésion</h3>
        <h3 class="h3Sports ms-4">Demande d'engagement</h3>
        <h3 class="h3Sports ms-4">Attestation certificats médicaux</h3>
        <h3 class="h3Sports ms-4">Autorisation de droit à l'image</h3>
        <h3 class="h3Sports ms-4">Règlement badminton</h3>
    </section>

    <!-- Section Informations -->
<!--     <section>
        <h2 class="h2Sports">Informations</h2>
        <p>Toutes les informations à propos de nos événements et activités.</p>
    </section>
 -->
    <!-- Section Les Chiffres -->
    <section id="chiffres">
        <h2 class="h2Sports">Les Chiffres</h2>
        <p>Quelques statistiques clés pour mieux comprendre nos performances.</p>
    </section>

    <!-- Section Galerie Photos -->
    <section id="gallery">
        <h2 class="h2Sports">Galerie Photos</h2>
        <p>Découvrez les meilleurs moments du club en images.</p>
    </section>

    <!-- Section Liens Utiles -->
    <section id="link">
        <h2 class="h2Sports">Liens Utiles</h2>
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
