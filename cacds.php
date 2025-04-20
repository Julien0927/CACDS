<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/config_session.php';
require_once 'App/News.php';
require_once 'lib/security.php';
require_once 'App/Actualite.php';

$actualiteManager = new App\Actualite\Actualite($db); 
$actualites = $actualiteManager->getAllActualites();
?>
<div class="center">
    <img src="/assets/logos/cacds_logo_CACDS.webp" style="width: 10%; height: auto" alt="cacds" class="img-fluid">
</div>
<!-- <section class="container-fluid mt-3">
    <h4 class="h3Sports">Actualité
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
 -->
 <section class="container-fluid mt-3">
    <h4 class="h3Sports">Actualité
        <img class="toggle-icon" id="toggleCollapsePresidentWord" data-target="collapseContentPresidentWord" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContentPresidentWord" class="collapse">
        <div class="row justify-content-center mt-4">
            <?php if (!empty($actualites)): ?>
                <?php foreach ($actualites as $actu): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-2" style="border-color: #12758C;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="h5Sports card-title"><?= ($actu['titre']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= date('d/m/Y', strtotime($actu['date_publication'])) ?></h6>
                                <p class="card-text mt-2"><?= nl2br((mb_strimwidth($actu['contenu'], 0, 200, '...'))) ?></p>
                                <button type="button" class="btn btn-card mt-auto p-2 text-start" data-bs-toggle="modal" data-bs-target="#actualiteModal<?= $actu['id'] ?>">
                                    Lire la suite
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal "Lire la suite" -->
                    <div class="modal fade" id="actualiteModal<?= $actu['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $actu['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="h5Sports modal-title" id="modalLabel<?= $actu['id'] ?>"><?= ($actu['titre']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($actu['date_publication'])) ?></p>
                                    <hr>
                                    <p><?= nl2br(($actu['contenu'])) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-original" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p style="color: #EC930F">Aucune actualité pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="container-fluid">
    <h4 class="h3Sports">Histoire
        <img class="toggle-icon" id="toggleCollapse" data-target="collapseContent" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContent" class="collapse">
    <div class="row">
        <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
            <p class="lecture text-justify mt-3 lh-lg"> 
                Début 1963, un groupe d’amis et sportifs, dirigé par Jacques ROUSSEL décident de créer une association sportive.<br>
                Le 15 juillet 1963 nait la COUPE DE L’AMITIÉ DE FOOT-BALL DES DEUX-SÈVRES, avec comme objectif de "former un groupement corporatif, afin de permettre aux amateurs ne pouvant opérer dans une équipe officielle de pratiquer leur sport favori.<br>Son siège social est situé au café "Le Glacier".<br>
                Le 17 septembre 1969 elle change de nom et devient l’actuelle COUPE DE L’AMITIÉ CORPORATIVE DES DEUX-SÈVRES.<br> Son siège social est transféré au café "Moderne".<br>
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
    <h4 class="h3Sports">Le Comité de gestion
        <img class="toggle-icon" id="toggleCollapseComity" data-target="collapseContentComity" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContentComity" class="collapse">
        <div class="row">
            <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
                <p class="text-center  mt-3"><a href="#" class="lecture" style="text-decoration: none;" alt="Le comité de gestion" title="Le comité de gestion">Le Comité de gestion</a></p>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid mt-3">
    <h4 class="h3Sports">L'Assemblée Générale
        <img class="toggle-icon" id="toggleCollapseAssemblee" data-target="collapseContentAssemblee" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContentAssemblee" class="collapse">
        <div class="row">
            <div class=" d-flex flex-column col-12 col-md-8 mx-auto">
                <p class="text-center  mt-3"><a href="#" class="lecture" style="text-decoration: none;" alt="L'Assemblée Générale" title="L'Assemblée Générale">L'Assemblée Générale</a></p>
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
<section class="container-fluid mt-3">
    <h4 class="h3Sports">L’Assurance "Dommages Corporels"
        <img class="toggle-icon" id="toggleCollapseAssurance" data-target="collapseContenteAssurance" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
    </h4>
    <div class="blueLine"></div>
    <div id="collapseContenteAssurance" class="collapse">
        <div class="row">
            <div class=" d-flex flex-column col-12 col-md-10 mx-auto">
                <p class="lecture text-justify mt-3 lh-lg">
                    Comme vous le savez, la CACDS a souscrit au nom et pour le compte de ses adhérents, une garantie "Dommages Corporels" en cas de survenance d’un accident corporel résultant de la pratique du sport en tant qu’adhérent CACDS.<br>
                    Lors de la dernière Assemblée Générale, la garantie vous a été présentée.<br>
                    Elle peut intervenir en cas de reste à charge pour l’adhérent, après intervention de la Sécurité Sociale et de sa mutuelle Santé.<br>

                    Vous trouverez ci-dessous, le document type à télécharger, pour faire votre déclaration de sinistre.<br>

                    La procédure est donc la suivante :<br>

                    1/ En cas d’accident entrainant une déclaration de sinistre, le responsable de votre équipe informe le responsable de la section sportive qu’une déclaration va être transmise.<br>
                    2/ Vous renseignez le document le plus précisément possible. Pour faciliter la lecture des informations par l’assureur, merci de remplir le document au format WORD (pas de mention manuscrite).<br>
                    3/ Vous nous transmettez ce document renseigné par mail à l’adresse suivante : assocacds@gmail.com<br>
                    A réception, nous transmettrons votre déclaration à la MAIF.<br>
                    La MAIF aura ainsi vos coordonnées et gérera ensuite le dossier directement avec vous.

                    En espérant malgré tout ne jamais avoir à traiter ce genre de situation !
                </p>
                <p class="text-center  mt-3"><a href="/assets/documents/Documents_declaration_sinistre.pdf" class="lecture" style="text-decoration: none;" alt="Reglements généraux" title="Reglements généraux">Document de déclaration de sinistre</a></p>
            </div>
        </div>
    </div>
</section>
<?php
require_once 'templates/footer.php';