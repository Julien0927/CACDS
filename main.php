<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <div class="col-12 col-md-6">
        <img src="/assets/icones/<?= $sport['icon'] ?>" alt="<?= $sport['name'] ?>">
        <?php if ($news): ?>
            <p class="date"><?= htmlspecialchars($news['date']) ?></p>
            <h3><?= htmlspecialchars($news['title']) ?></h3>
            <p><?= htmlspecialchars(truncateText($news['content'])) ?></p>
            <img class="imgHome" src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>">
        <?php else: ?>
            <p>Aucun article disponible pour le <?= $sport['name'] ?>.</p>
        <?php endif; ?>
        <button class="btn btn-primary">Lire</button>
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
                <legend>Actualités</legend>
                <div class="row">
                    <?php displayNewsSection($latestNews['bad'], $sports['bad']); ?>
                    <?php displayNewsSection($latestNews['volley'], $sports['volley']); ?>
                </div>
                <div class="row mt-5">
                    <?php displayNewsSection($latestNews['tdt'], $sports['tdt']); ?>
                    <?php displayNewsSection($latestNews['petanque'], $sports['petanque']); ?>
                </div>
                <p>Contenu à l'intérieur du cadre.</p>
            </fieldset>
        </div>
    </div>
</section>

            </div>
        </div>
    </div>
</section>
<section class="mt-3">
    <h1 style="text-align: center;">TEXTE DE PRÉSENTATION DE L'ASSOCIATION</h1>
</section>
<section class="d-flex flex-md-row flex-column justify-content-around mt-5 mb-5">
        <div >
            <img src="/assets/icones/Badminton item.svg" alt="">
        </div>
        <div >
            <img src="/assets/icones/Volley Item.svg" alt="">
        </div>
        <div >
            <img src="/assets/icones/TdT Item.svg" alt="">
        </div>
        <div >
            <img src="/assets/icones/petanque item.svg" alt="">
        </div>

</section>

<?php
require_once 'templates/footer.php';
