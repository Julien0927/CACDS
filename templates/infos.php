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
                <div class="d-flex flex-column col-12 col-md-2 salle-card">
                    <h4 class="h4Sports text-center">Fournir un certificat médical</h4>
                    <a href="/assets/documents/article_certificat_medical.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="article certificats médicaux" style="text-decoration: none;" title="Article certificats médicaux">
                        <img src="/assets/icones/attestation-64.png" alt="fleche droite" class="mb-1" loading="lazy">
                    </a>
                </div>
                <div class="d-flex center flex-column col-12 col-md-2 salle-card">
                    <h4 class="h4Sports text-center">Coordonnées des capitaines</h4>
                    <a href="/assets/documents/Capitaines 2024 2025.pdf" class="mx-auto" style="margin-left: 7rem; text-decoration: none;" alt="coordonnées des capitaines" title="Coordonnées des capitaines">
                        <img src="/assets/icones/attestation-64.png" alt="fleche droite" class="mb-1" loading="lazy">
                    </a>
                </div>
                <div class="d-flex center flex-column col-12 col-md-2 salle-card">
                    <h4 class="h4Sports text-center">Créneaux des équipes</h4>
                    <a href="/assets/documents/Créneaux 2024 2025.pdf" class="mx-auto" style="margin-left: 7rem; text-decoration: none;" alt="coordonnées des capitaines" title="Créneaux des équipes">
                        <img src="/assets/icones/attestation-64.png" alt="fleche droite" class="mb-1" loading="lazy">
                    </a>
                </div>
                <div class="d-flex center flex-column col-12 col-md-2 salle-card">
                    <h4 class="h4Sports text-center">Relais de l'information</h4>
                    <a href="/assets/documents/relais_info_2025.pdf" class="mx-auto" style="margin-left: 7rem; text-decoration: none;" alt="coordonnées des capitaines" title="Relais information">
                        <img src="/assets/icones/attestation-64.png" alt="fleche droite" class="mb-1" loading="lazy">
                    </a>
                </div>
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
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Palmarès championnat</h4>
                        <a href="/assets/documents/palmares_championnat_cacds.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="Palmarès championnat" style="text-decoration: none;" title="Palmarès championnat">
                            <img src="/assets/icones/trophée-64.png" alt="trophée" class="mb-1" loading="lazy">
                        </a>
                    </div>
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Palmarès coupe</h4>
                        <a href="/assets/documents/palmares_coupe_cacds.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="Palmarès championnat" style="text-decoration: none;" title="Palmarès championnat">
                            <img src="/assets/icones/trophée-64.png" alt="trophée" class="mb-1" loading="lazy">
                        </a>
                    </div>
                    <div class="d-flex center flex-column col-12 col-md-3 salle-card">
                        <h4 class="h4Sports text-center">Palmarès titres et double</h4>
                        <a href="/assets/documents/nombre_de_titres_et_de_doubles.pdf" class="mx-auto" style="margin-left: 7rem" aria-label="Palmarès championnat" style="text-decoration: none;" title="Palmarès championnat">
                            <img src="/assets/icones/trophée-64.png" alt="trophée" class="mb-1" loading="lazy">
                        </a>
                    </div>
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