<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/config_session.php';
require_once 'App/News.php';
require_once 'lib/security.php';
?>
<div class="center">
    <img src="/assets/logos/cacds_logo_CACDS.webp" style="width: 10%; height: auto" alt="cacds" class="img-fluid">
</div>

<section class="container-fluid">
    <h4 class="h3Sports">Histoire
        <img class="toggle-icon" id="toggleCollapse" data-target="collapseContent" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContent" class="collapse">
    <div class="row">
        <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
            <p class="lecture text-center mt-3"> 
                Début 1963, un groupe d’amis et sportifs, dirigé par Jacques ROUSSEL décident de créer une association sportive.<br>
                Le 15 juillet 1963 nait la COUPE DE L’AMITIÉ DE FOOT-BALL DES DEUX-SÈVRES, avec comme objectif de "former un groupement corporatif, afin de permettre aux amateurs ne pouvant opérer dans une équipe officielle de pratiquer leur sport favori." Son siège social est situé au café "Le Glacier".<br>
                Le 17 septembre 1969 elle change de nom et devient l’actuelle COUPE DE L’AMITIÉ CORPORATIVE DES DEUX-SÈVRES. Son siège social est transféré au café "Moderne".<br>
                Le 29 octobre 1975, nouveau siège social au 108 avenue de Paris.<br>
                Le 24 septembre 1976 : la CACDS, elle devient propriétaire de son siège social, au 33 rue de l’Arsenal. Le 11 octobre 1982 elle revend cet immeuble pour s’installer au 38 rue Laurent Bonnevay dans une traverse aménagée de HLM.<br>
                Avec la destruction de l’immeuble, la CACDS transfère son siège à la maison des associations, rue Joseph Cugnot à Niort.
            </p>
            <p class="lecture text-center">Depuis sa création 7 Présidents ont dirigé la C.A.C.D.S. :</p>
                <ul class="lecture text-center" style="list-style-type: none;">
                    <li>M. ROUSSEL Jacques, Président fondateur</li>
                    <li>M. ÉLIE Roland</li>
                    <li>M. DRILLET Marcel</li>
                    <li>M. MORIN Jacques</li>
                    <li>M. LAMY Michel (1981 à 2006)</li>
                    <li>M. MOINARD Laurent (2006 à 2023),</li>
                    <li>M. PEROCHON Eric, l’actuel Président</li>
                </ul>
           
        </div>
    </div>
    </div>
</section>
<section class="container-fluid mt-3">
    <h4 class="h3Sports">Le Bureau
        <img class="toggle-icon" id="toggleCollapsePresidentWord" data-target="collapseContentPresidentWord" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContentPresidentWord" class="collapse">
        <div class="row">
            <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
                <p class="text-center  mt-3"><a href="/assets/documents/mot du president.pdf" class="lecture" style="text-decoration: none;" alt="Le mot du président" title="Le mot du président">Le mot du Président</a></p>
                <p class="text-center  mt-3"><a href="/assets/documents/bureau.pdf" class="lecture" style="text-decoration: none;" alt="Composition du bureau" title="La composition du bureau">La composition du bureau</a></p>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid mt-3">
    <h4 class="h3Sports">Les Statuts
        <img class="toggle-icon" id="toggleCollapseStatuts" data-target="collapseContentStatuts" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContentStatuts" class="collapse">
        <div class="row">
            <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
                <p class="text-center  mt-3"><a href="/assets/documents/statuts_cacds.pdf" class="lecture" style="text-decoration: none;" alt="Statuts du club" title="Statuts de l'association">Les Statuts</a></p>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid mt-3">
    <h4 class="h3Sports">Les Réglements Généraux
        <img class="toggle-icon" id="toggleCollapseReglement" data-target="collapseContentReglement" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContentReglement" class="collapse">
        <div class="row">
            <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
                <p class="text-center  mt-3"><a href="/assets/documents/reglements_generaux.pdf" class="lecture" style="text-decoration: none;" alt="Reglements généraux" title="Reglements généraux">Les Reglements Généraux</a></p>
            </div>
        </div>
    </div>
</section>
<?php
require_once 'templates/footer.php';