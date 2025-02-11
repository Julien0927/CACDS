<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/config_session.php';
require_once 'App/News.php';
require_once 'lib/security.php';

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
<section>
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
                                <img id="modalImage" class="img-fluid center" alt="Image de l'article" loading="lazy">
                            </div>
                            <p id="modalContent"></p>
                            <p id="modalDate" class="text-muted"></p>
                            <div class="center">
                                <button type="button" class="btn btn-original bold" data-bs-dismiss="modal">Fermer</button>
                                <a id="moreArticles" href="#" class="btn btn-card bold ms-3">Plus d'articles</a>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image à droite -->
            <?php if ($news): ?>
                <div class="news-image-container">
                    <img class="news-image img-fluid" src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>" loading="lazy">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>     
<?php
}
?>

<h1 class="text-center mt-3">Coupe de l'Amitié Corporative des Deux-Sèvres</h1>
<div class="d-flex justify-content-center align-items-center mt-3 gap-5">
    <img src="/assets/icones/Square item bad.svg" alt="badminton" class="img-fluid itemSquare">
    <img src="/assets/icones/Square item Volley.svg" alt="volleyball" class="img-fluid itemSquare">
    <img src="/assets/logos/cacds_logo_CACDS.webp" style="width: 10%; height: auto" alt="cacds" class="img-fluid">
    <img src="/assets/icones/Square item TdT.svg" alt="tennis de table" class="img-fluid itemSquare">
    <img src="/assets/icones/Square item petanque.svg" alt="pétanque" class="img-fluid itemSquare">
</div>

<section class="mt-3">
    <div class="container ">
        <div class="col-12 col-md-12">
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

<section class="container-fluid mt-3">
    <h2 class="h3Sports text-center">PRÉSENTATION DE L'ASSOCIATION</h2>
    <h2 class="h3Sports text-center">HISTOIRE</h2>
    <p>
    
    </p>
    <h2 class="h3Sports">Assurance dommage corporels</h2>
    <p>
    Comme vous le savez, la CACDS a souscrit au nom et pour le compte de ses adhérents, une garantie "Dommages Corporels" en cas de survenance d’un accident corporel résultant de la pratique du sport en tant qu’adhérent CACDS.
Lors de la dernière Assemblée Générale, la garantie vous a été présentée.
Elle peut intervenir en cas de reste à charge pour l’adhérent, après intervention de la Sécurité Sociale et de sa mutuelle Santé.

Vous trouverez ci-dessous, le document type à télécharger, pour faire votre déclaration de sinistre.

La procédure est donc la suivante :

1/ En cas d’accident entrainant une déclaration de sinistre, le responsable de votre équipe informe le responsable de la section sportive qu’une déclaration va être transmise.
2/ Vous renseignez le document le plus précisément possible. Pour faciliter la lecture des informations par l’assureur, merci de remplir le document au format WORD (pas de mention manuscrite).
3/ Vous nous transmettez ce document renseigné par mail à l’adresse suivante : assocacds@gmail.com
A réception, nous transmettrons votre déclaration à la MAIF.
La MAIF aura ainsi vos coordonnées et gérera ensuite le dossier directement avec vous.

En espérant malgré tout ne jamais avoir à traiter ce genre de situation !
    </p>

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
        <p>Maison des Associations, Rue Joseph Cugnot, Niort 79000</p>
        <h3 class="h3Sports">Téléphone</h3>
        <p>06 64 28 58 40</p>
        <h3 class="h3Sports">Email</h3>
        <p>assocacds@gmail.com</p>
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
