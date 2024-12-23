<?php
require_once 'header.php';
require_once 'templates/nav.php';

?>

<div class="center">
    <h1 class="mt-3"><img src="/assets/icones/Square item bad.svg" class="me-3">BADMINTON</h1>
</div>

<?php
require_once 'templates/insideNav.php';
?>
<div class="container-fluid ms-3">
<!-- Section Actualités -->
    <section>
        <h2 class="h2Sports">Actualités</h2>
        <p>Retrouvez ici les dernières nouvelles importantes concernant le club.</p>
    </section>

    <!-- Section Compétitions -->
    <section>
        <h2 class="h2Sports">Compétitions</h2>
        <h3 class="h3Sports ms-4">Calendrier de la saison</h3>
        <p class="center">Ici image du calendrier</p>
        <h3 class="h3Sports ms-4" id="compet">Championnat</h3>
        <p>Le championnat regroupe plusieurs poules où évoluent 6 équipes. Les matchs se déroulent en phase aller-retour.<br> 
            En fin de saison, les deux premiers montent en poule supérieure, les deux derniers descendent en poule inférieure</p>
            <label for="sports" class="form-label me-2">Sélectionnez votre poule</label>
            <select name="poules" id="poules">
                <option value="">Poule 1</option>
                <option value="">Poule 2</option>
                <option value="">Poule 3</option>
                <option value="">Poule 4</option>
                <option value="">Poule 5</option>
                <option value="">Poule 6</option>
                <option value="">Poule 7</option>
                <option value="">Poule 8</option>
            </select>
        <h3 class="h3Sports ms-4" id="cup">Coupe</h3>
        <h3 class="h3Sports ms-4" id="tournament">Tournois</h3>
    </section>

    <!-- Section Documents -->
    <section>
        <h2 class="h2Sports">Documents</h2>
        <p>Accédez aux documents officiels et informations utiles.</p>
    </section>

    <!-- Section Informations -->
    <section>
        <h2 class="h2Sports">Informations</h2>
        <p>Toutes les informations à propos de nos événements et activités.</p>
    </section>

    <!-- Section Les Chiffres -->
    <section>
        <h2 class="h2Sports">Les Chiffres</h2>
        <p>Quelques statistiques clés pour mieux comprendre nos performances.</p>
    </section>

    <!-- Section Galerie Photos -->
    <section>
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