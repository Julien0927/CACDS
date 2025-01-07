<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/News.php';

$news = new App\News\News($db);
$sportId = 3;
$totalPages = $news->getTotalPages();
$pageActuelle = isset ($_GET['page']) ? $_GET['page'] : 1;
$newsPageActuelle = $news->getNewsBySport($sportId, $pageActuelle);

?>
<div class="center">
    <h1 class="mt-3"><img src="/assets/icones/Square item Volley.svg" class="me-3">PÉTANQUE</h1>
</div>

<?php
require_once 'templates/insideNav.php';
?>
<div class="container-fluid ms-3">
<!-- Section Actualités -->
    <section>
        <h2 class="h2Sports">Actualités</h2>
        <p>Retrouvez ici les dernières nouvelles importantes concernant le club.</p>
        <?php foreach ($newsPageActuelle as $new) {
            include 'templates/partial_news.php';
        } ?>
            
    </section>
</div>

<?php
require_once 'templates/footer.php';