<section class="container-fluid" id="informations">
        <h2 class="h2Sports mt-3">Informations</h2>
        <hr>
        <section class="container-fluid">
            <h3 class="h3Sports" id="adhesion">Trombinoscope
                <img class="toggle-icon" id="toggleCollapseTrombi" data-target="collapseContentTrombi" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
            </h3>
            <div id="collapseContentTrombi" class="collapse">
                <p class="lecture">
                    Vous trouverez dans cette rubrique les demandes d’adhésion des joueurs et joueuses CACDS renseignées par les clubs. 
                </p>
                <div class="row gap-3 ">
                    <select class="fm-select ms-3" id="pouleAdhesionSelect">
                        <option value="">Sélectionner une poule</option>
                        <option value="1">Poule 1</option>
                        <option value="2">Poule 2</option>
                        <option value="3">Poule 3</option>
                        <option value="4">Poule 4</option>
                        <option value="5">Poule 5</option>
                        <option value="6">Poule 6</option>
                        <option value="7">Poule 7</option>
                        <option value="8">Poule 8</option>
                    </select>
                    <div class="mx-auto" id="AdhesionContainer"></div>
                </div>
            </div>
        </section>
        <section class="container-fluid">
        <h3 class="h3Sports" id="outils">Boîte à outils
            <img class="toggle-icon" id="toggleCollapseTools" data-target="collapseContentTools" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
        </h3>
        <div id="collapseContentTools" class="collapse">
            <div class="row justify-content-evenly gap-3">
                <?php
                // Tableau des catégories de documents à afficher
                $categories = [
                "Fournir un certificat médical",
                "Coordonnées des capitaines",
                "Créneaux des équipes",
                "Relais de l'information",
                "Feuille de match"
                ];
                            // Récupérer les documents pour chaque catégorie
                foreach ($categories as $categorie) {
                    $documents = $documentsManager->getDocumentsByCategory(htmlspecialchars($categorie));

                    if (!empty($documents)) {
                        foreach ($documents as $document) {
                            // Affichage de chaque document sous forme de carte
                            echo '<div class="d-flex flex-column justify-content-center col-12 col-md-2 salle-card">';
                            echo '<h4 class="h4Sports text-center">' . ($document['categorie']) . '</h4>';
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
                <h4 class="h4Sports mt-3 text-center">Trouver une salle</h4>
                <p class="lecture text-center">Retrouvez les adresses de toutes les salles</p>
                <div class="grid-container" id="salles-container"></div>
        </div>
        </section>
        <section class="container-fluid">
            <h3 class="h3Sports" id="palmares">Palmarès Badminton CACDS
                <img class="toggle-icon" id="toggleCollapsePalmares" data-target="collapseContentPalmares" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
            </h3>
            <div id="collapseContentPalmares" class="collapse">
                <p class="lecture">
                    Retrouvez ici les palmarès des différentes compétitions de la saison.
                </p>
                <div class="row center gap-3">
                <?php
                    // Tableau des catégories de documents à afficher
                    $categories = [
                        "Palmarès championnat",
                        "Palmarès coupe",
                        "Palmarès titres et double"
                    ];

                    // Récupérer les documents pour chaque catégorie
                    foreach ($categories as $categorie) {
                        $documents = $documentsManager->getDocumentsByCategory(htmlspecialchars($categorie));

                        if (!empty($documents)) {
                            foreach ($documents as $document) {
                                // Affichage de chaque document sous forme de carte
                                echo '<div class="d-flex flex-column justify-content-center col-12 col-md-3 salle-card">';
                                echo '<h4 class="h4Sports text-center">' . ($document['categorie']) . '</h4>';
                                echo '<a href="' . htmlspecialchars($document['fichier']) . '" class="center" aria-label="">';
                                echo '<img src="/assets/icones/trophée-64.png" class="zoom">';
                                echo '</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Aucun document disponible pour cette catégorie.</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
<!--         <section class="container-fluid">
            <h3 class="h3Sports" id="anniversaire">20 ans du badminton
                <img class="toggle-icon" id="toggleCollapseAnniv" data-target="collapseContentAnniv" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
            </h3>
            <div id="collapseContentAnniv" class="collapse">
                <p class="lecture text-center">
                    2018 restera l’année d’un anniversaire important : les 20 ans de la section Badminton CACDS.
                    L’association CACDS a donc organisé une soirée pour cet anniversaire en invitant tous les adhérent(e)s Badminton CACDS. Un apéritif dinatoire a été offert par l’association afin de passer un moment ensemble le plus agréable possible.
                    95 personnes ont répondu présent le 23 mars 2018 pour cette soirée.
                    Merci à tous pour ce moment très sympathique.
                    Ci-dessous les photos prises par Michel Hartmann
                </p>
                <div class="row center gap-3">
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Photos 20 ans</h4>
                        <a href="http://www.ipernity.com/doc/hartmann/album/1051842" class="mx-auto" style="margin-left: 7rem" aria-label="Photos 20 ans" style="text-decoration: none;" title="Photos 20 ans">
                            <img src="/assets/icones/photos-64.png" alt="photos" class="mb-1" title="Photos 20 ans du club" loading="lazy">
                        </a>
                    </div>
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Discours</h4>
                        <a href="/assets/documents/discours_20_ans.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="Photos 20 ans" style="text-decoration: none;" title="Photos 20 ans">
                            <img src="/assets/icones/attestation-64.png" alt="discours" class="mb-1" title="Discours 20 ans du club" loading="lazy">
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-fluid">
            <h3 class="h3Sports" id="media">Photos et videos
                <img class="toggle-icon" id="toggleCollapseMedia" data-target="collapseContentMedia" src="/assets/icones/tri-décroissant-30.png" alt="toggle collapse" style="cursor: pointer;" loading="lazy">
            </h3>
            <div id="collapseContentMedia" class="collapse">
                <div class="row center gap-3">
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Finales de Coupe 2018</h4>
                        <a href="/assets/documents/diaporama_finales_de_coupe_2018.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="Photos 20 ans" style="text-decoration: none;" title="Photos 20 ans">
                            <img src="/assets/icones/photos-64.png" alt="photos" class="mb-1" title="Photos 20 ans du club" loading="lazy">
                        </a>
                    </div>
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Affiche tournoi du 17 mars 2024</h4>
                        <a href="/assets/documents/affiche_tournoi_17_mars.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="Photos 20 ans" style="text-decoration: none;" title="Photos 20 ans">
                            <img src="/assets/icones/attestation-64.png" alt="discours" class="mb-1" title="Discours 20 ans du club" loading="lazy">
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </section>
 -->