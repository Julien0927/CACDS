<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/config_session.php';
require_once 'App/News.php';
require_once 'lib/security.php';
require_once 'App/Actualite.php';

$news = new App\News\News($db);
$actualiteGenerale = new App\Actualite\Actualite($db);

// Structure des sports avec leurs données
$sports = [
    'tdt' => [
        'id' => 1,
        'name' => 'tennis de table',
        'icon' => 'Square item TdT.svg',
        'href' => 'tennisDT.php'
    ],
    'bad' => [
        'id' => 2,
        'name' => 'badminton',
        'icon' => 'Square item bad.svg',
        'href' => 'badminton.php'
    ],
    'petanque' => [
        'id' => 3,
        'name' => 'pétanque',
        'icon' => 'Square item petanque.svg',
        'href' => 'petanque.php'
    ],
    'volley' => [
        'id' => 4,
        'name' => 'volleyball',
        'icon' => 'Square item Volley.svg',
        'href' => 'volley.php'
    ],
    'actus' => [
        'id' => 5,
        'name' => 'actus',
        'icon' => 'cacds_logo.jpg',
        'href' => 'actus.php'
    ]
];

// Récupération des actualités
$page = $_GET['page'] ?? 1;
$latestNews = [];

foreach ($sports as $key => $sport) {
    $sportNews = $news->getNewsBySport($sport['id'], $page);
    $latestNews[$key] = !empty($sportNews) ? $sportNews[0] : null;
}

$actusGenerale = $actualiteGenerale->getLatestActualite(1); // par exemple une par défaut
$latestActus['actus'] = !empty($actusGenerale) ? $actusGenerale[0] : null;

function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Fonction pour afficher une section d'actualité
function displayNewsSection($news, $sport) {
?>
<section>
<div class="col-12 col-md-12">
    <div class="news-container">
        <div class="news-wrapper">
            <!-- Partie gauche avec le texte -->
            <div class="news-text-content">
                <!-- En-tête avec icône et titre -->
                <div class="news-header">
                    <a  href="<?= htmlspecialchars($sport['href']) ?>">
                    <img class="sport-icon" src="/assets/icones/<?= $sport['icon'] ?>" alt="<?= $sport['name'] ?>" loading="lazy">
                    </a>
                    <?php if ($news): ?>
                        <h3 class="news-title"><?= ($news['title']) ?></h3>
                    <?php endif; ?>
                </div>
                
                <?php if ($news): ?>
                    <div class="news-body">
                        <p class="content" style="color: #12758C"><?= (truncateText($news['content'])) ?></p>
                        <p class="date"><?= date ('d/m/Y', strtotime($news['date'])) ?></p>
                    </div>
                <?php else: ?>
                    <p style="color: #EC930F">Aucun article disponible pour le <?= $sport['name'] ?>.</p>
                <?php endif; ?>
                
                <?php if ($news): ?>
                    <button class="btn btn-original bold" 
                            data-bs-toggle="modal" 
                            data-bs-target="#newsModal"
                            data-title="<?=($news['title']) ?>"
                            data-image="<?=($news['image']) ?>"
                            data-content="<?=($news['content']) ?>"
                            data-sport-id="<?= $news['sport_id'] ?>"
                            data-date="<?= date('d/m/Y', strtotime($news['date'])) ?>">
                            Lire
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Modal News -->
             <div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <h3 class="h3Sports" id="modalTitle"></h3>
                            <div class="center">
                                <img id="modalImage" class="img-fluid center" alt="Image de l'article" loading="lazy">
                            </div>
                            <p class="lecture" id="modalContent"></p>
                            <p id="modalDate" class="text-muted"></p>
                            <div class="center">
                                <button type="button" class="btn btn-original bold" data-bs-dismiss="modal">Fermer</button>
                                <a id="moreArticles" href="cacds.php" class="btn btn-card bold ms-3">Plus d'articles</a>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>         
  
            <!-- Image à droite -->
            <?php if ($news && !empty($news['image'])): ?>
                <div class="news-image-container col-12 col-md-4">
                    <img class="news-image img-fluid" src="<?= $news['image'] ?>" alt="<?= ($news['title']) ?>" loading="lazy">
                </div>
            <?php endif; ?>       
        </div>
    </div>
</div>     
<?php
}
function displayActuGenerale($actualite) {
    if (!$actualite) return;
    ?>
    <section>
    <div class="col-12 col-md-6">
        <div class="news-container">
            <div class="news-wrapper">
                <!-- Partie gauche avec le texte -->
                <div class="news-text-content">
                    <!-- En-tête avec icône et titre -->
                    <div class="news-header">
                        <a href="cacds.php">
                            <img class="sport-icon" src="/assets/icones/Square Cacds.svg" alt="Cacds" loading="lazy">
                        </a>
                        <h3 class="news-title"><?= ($actualite['titre']) ?></h3>
                    </div>
                    
                    <div class="news-body">
                        <p class="content" style="color: #12758C"><?= (substr($actualite['contenu'], 0, 100)) ?>...</p>
                        <p class="date"><?= date('d/m/Y', strtotime($actualite['date_publication'])) ?></p>
                    </div>
                    
                    <button class="btn btn-original bold" 
                            data-bs-toggle="modal" 
                            data-bs-target="#actuModal"
                            data-title="<?= ($actualite['titre']) ?>"
                            data-content="<?= ($actualite['contenu']) ?>"
                            data-date="<?= date('d/m/Y', strtotime($actualite['date_publication'])) ?>"
                            data-document="<?= ($actualite['document_path']) ?>">
                        Lire
                    </button>
                </div>

                <!-- Document ou image à droite -->
                <?php if (!empty($actualite['document_path'])): ?>
                    <div class="actus-image-container col-12 col-md-4 ">
                        <?php
                        $ext = strtolower(pathinfo($actualite['document_path'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                            echo '<img class="news-image img-fluid " src="' . htmlspecialchars($actualite['document_path']) . '" alt="Image de l\'actualité" loading="lazy">';
                        } else {
                            echo '<a href="' . htmlspecialchars($actualite['document_path']) . '" target="_blank">
                            <img src="/assets/icones/pdf-250.png" style="width: 100px; height: 100px" alt="Document PDF" class="img-fluid" loading="lazy">
                            </a>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </section>
    <?php
}
?>
<!-- Modal Actu --> 
<div class="modal fade" id="actuModal" tabindex="-1" aria-labelledby="actuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <!-- En-tête sans titre mais avec bouton de fermeture -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            
            <!-- Corps de la modale -->
            <div class="modal-body">
                <h3 class="h3Sports" id="actuModalTitle"></h3>
                
                <!-- Container pour l'image ou le document -->
                <div id="actuModalImage" class="text-center mb-3"></div>
                
                <p class="lecture" id="actuModalContent"></p>
                <p id="actuModalDate" class="text-muted"></p>
                
                <div class="center">
                    <button type="button" class="btn btn-original bold" data-bs-dismiss="modal">Fermer</button>
                    <a id="actuMoreArticles" href="cacds.php" class="btn btn-card bold ms-3">Plus d'articles</a>
                </div>
            </div>
        </div>
    </div>
</div>

<h1 class="text-center mt-3">Coupe de l'Amitié Corporative des Deux-Sèvres</h1>
<div class="d-flex justify-content-evenly align-items-center mt-3">
    <img src="/assets/img/imgBadminton.jpg" alt="badminton" class="img-fluid itemSquare" loading="lazy">
    <img src="/assets/img/imgPetanque.jpg" alt="badminton" class="img-fluid itemSquare" loading="lazy">
    <img src="/assets/logos/cacds_logo_CACDS.webp" style="width: 10%; height: auto" alt="cacds" class="img-fluid" loading="lazy">
    <img src="/assets/img/imgTdt.jpg" alt="badminton" class="img-fluid itemSquare" loading="lazy">
    <img src="/assets/img/imgVolley.jpg" alt="badminton" class="img-fluid itemSquare" loading="lazy">
</div>

<section class="mt-3">
    <div class="container ">
        <div class="col-12 col-md-12">
            <fieldset>
                <legend id="menu">Actualités</legend>
                <div class="row ">
                    <div class="col-12 col-md-12 mt-3 ms-3">
                        <?php displayActuGenerale($latestActus['actus']); ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['bad'], $sports['bad']); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['volley'], $sports['volley']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['tdt'], $sports['tdt']); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['petanque'], $sports['petanque']); ?>
                    </div>
                </div>
                </div>
            </fieldset>
        </div>
    </div>
</section>

<section class="container-fluid mx-auto row mt-5">
    <div class="center col-12 col-md-6">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5511.415663923563!2d-0.4802229241907535!3d46.31563967622913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48072e2f1b910733%3A0x448a1ec76d99546a!2sNIORT-ASSOCIATIONS!5e0!3m2!1sfr!2sfr!4v1738249707293!5m2!1sfr!2sfr" 
            class="map img-fluid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Adresse de l'association">
        </iframe>
    </div>
    <div class="center flex-column text-center col-12 col-md-6">
        <img src="/assets/logos/square_logo.webp" alt="logo CACDS" style="width: 20%; height: auto" class="img-fluid mx-auto">
        <h3 class="h3Sports">Adresse</h3>
        <p class="lecture">Maison des Associations, Rue Joseph Cugnot, Niort 79000</p>
        <h3 class="h3Sports">Téléphone</h3>
        <p class="lecture">06 64 28 58 40</p>
        <h3 class="h3Sports">Email</h3>
        <p class="lecture">assocacds@gmail.com</p>
    </div>
</section>
<section class="container-fluid mt-5 mb-5">
    <div class="row g-3 justify-content-center">
        <div class="col-6 col-md-3 text-center">
            <a href="badminton.php" aria-label="badminton">
                <img src="/assets/icones/Badminton Item.svg" alt="badminton" class="img-fluid">
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="volley.php" aria-label="volleyball">
                <img src="/assets/icones/Volley Item.svg" alt="volleyball" class="img-fluid">
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="tennisDT.php" aria-label="tennis de table">
                <img src="/assets/icones/TdT item.svg" alt="Tennis de table" class="img-fluid">
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="petanque.php" aria-label="pétanque">
                <img src="/assets/icones/petanque item.svg" alt="pétanque" class="img-fluid">
            </a>
        </div>
    </div>
</section>

<?php
require_once 'templates/footer.php';
