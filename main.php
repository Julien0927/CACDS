<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/config_session.php';
require_once 'App/News.php';

$news = new App\News\News($db);

// Structure des sports avec leurs données
$sports = [
    'tdt' => [
        'id' => 1,
        'name' => 'tennis de table',
        'icon' => 'Square item TdT.svg'
    ],
    'bad' => [
        'id' => 2,
        'name' => 'badminton',
        'icon' => 'Square item bad.svg'
    ],
    'petanque' => [
        'id' => 3,
        'name' => 'pétanque',
        'icon' => 'Square item petanque.svg'
    ],
    'volley' => [
        'id' => 4,
        'name' => 'volleyball',
        'icon' => 'Square item Volley.svg'
    ]
];

// Récupération des actualités
$page = $_GET['page'] ?? 1;
$latestNews = [];

foreach ($sports as $key => $sport) {
    $sportNews = $news->getNewsBySport($sport['id'], $page);
    $latestNews[$key] = !empty($sportNews) ? $sportNews[0] : null;
}

function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}
// Fonction pour afficher une section d'actualité
function displayNewsSection($news, $sport) {
?>
<div class="col-12 col-md-12">
    <div class="news-container">
        <div class="news-wrapper">
            <!-- Partie gauche avec le texte -->
            <div class="news-text-content">
                <!-- En-tête avec icône et titre -->
                <div class="news-header">
                    <img class="sport-icon" src="/assets/icones/<?= $sport['icon'] ?>" alt="<?= $sport['name'] ?>">
                    <?php if ($news): ?>
                        <h3 class="news-title"><?= ($news['title']) ?></h3>
                    <?php endif; ?>
                </div>
                
                <?php if ($news): ?>
                    <div class="news-body">
                        <p class="content"><?= (truncateText($news['content'])) ?></p>
                        <p class="date"><?= date ('d/m/Y', strtotime($news['date'])) ?></p>
                    </div>
                <?php else: ?>
                    <p>Aucun article disponible pour le <?= $sport['name'] ?>.</p>
                <?php endif; ?>
                
                <!-- <button class="btn btn-original" href="">Lire</button> -->
                <?php if ($news): ?>
                    <button class="btn btn-original" 
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
            <!-- Modal Bootstrap -->
            <div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Contenu dynamique -->
                            <h3 class="h3Sports" id="modalTitle"></h3>
                            <div class="center">
                                <img id="modalImage" class="img-fluid center" alt="Image de l'article">
                            </div>
                            <p id="modalContent"></p>
                            <p id="modalDate" class="text-muted"></p>
                            <div class="center">
                                <button type="button" class="btn btn-original" data-bs-dismiss="modal">Fermer</button>
                                <a id="moreArticles"  href="#" class="btn btn-card ms-3">Plus d'articles</a>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image à droite -->
            <?php if ($news): ?>
                <div class="news-image-container">
                    <img class="news-image" src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>     
<?php
}
?>

<h1 class="cacds">Coupe de l'Amitié Corporative des Deux-Sèvres</h1>

<div class="d-flex justify-content-center">
    <img src="/assets/logos/cacds_logo_CACDS.jpg" style="width: 10%; height: 10%" alt="cacds" class="img-fluid">
</div>

<section class="mt-3">
    <div class="container">
        <div class="col-12">
            <fieldset>
                <legend id="menu">Actualités</legend>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['bad'], $sports['bad']); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['volley'], $sports['volley']); ?>
                    </div>
                    <div class="row mt-5">
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['tdt'], $sports['tdt']); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php displayNewsSection($latestNews['petanque'], $sports['petanque']); ?>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</section>

<section class="mt-3">
    <h1 style="text-align: center;">TEXTE DE PRÉSENTATION DE L'ASSOCIATION</h1>
</section>
<section class="d-flex flex-md-row flex-column justify-content-around mt-5 mb-5">
        <div >
            <a href="badminton.php"><img src="/assets/icones/Badminton Item.svg" alt=""></a>
        </div>
        <div >
        <a href="volley.php"><img src="/assets/icones/Volley Item.svg" alt=""></a>
        </div>
        <div >
        <a href="tennisDT.php"><img src="/assets/icones/TdT item.svg" alt=""></a>
        </div>
        <div >
        <a href="petanque.php"><img src="/assets/icones/petanque item.svg" alt=""></a>
        </div>

</section>

<?php
require_once 'templates/footer.php';
