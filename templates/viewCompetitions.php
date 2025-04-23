<!-- Section Compétitions -->

        <!--Championnat-->
        <h3 class="h3Sports" id="compet">Championnat</h3>
        <p class="lecture">Le championnat regroupe plusieurs poules où évoluent 6 équipes. Les matchs se déroulent en phase aller-retour.<br> 
            En fin de saison, les deux premiers montent en poule supérieure, les deux derniers descendent en poule inférieure.</p>
            <label for="sports" class="form-label me-2">Sélectionnez votre poule</label>
            <select name="poules" id="poules">
            <?php foreach ($poules as $poule): ?>
                <option value="<?= $poule['id'] ?>"><?= htmlspecialchars($poule['id']) ?></option>
            <?php endforeach; ?>

            </select>
            <div class="row d-flex justify-content-evenly gap-2 mt-4">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                        <h4 class="h4Results">Résultats</h4>
                    </div>
                    <div id="resultats-container">
                        <!-- Les résultats seront chargés ici -->
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                        <h4 class="h4Results">Classements</h4>
                    </div>
                    <div id="classement-container">
                        <!-- Le classement sera chargé ici -->
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                        <h4 class="h4Results">Feuilles de matchs</h4>
                    </div>
                    <!-- <select class="fm-select mx-auto" id="pouleSelect">
                        <option value="1">Poule 1</option>
                        <option value="2">Poule 2</option>
                        <option value="3">Poule 3</option>
                        <option value="4">Poule 4</option>
                        <option value="5">Poule 5</option>
                        <option value="6">Poule 6</option>
                        <option value="7">Poule 7</option>
                        <option value="8">Poule 8</option>
                    </select> -->
                    <div class="mx-auto" id="fm-container"></div>
                </div>
            </div>
        <!--Coupe-->
        <h3 class="h3Sports" id="cup">Coupe</h3>
        <p class="lecture">La coupe est une compétition à élimination directe. </p>
        <p class="lecture">Un tour préliminaire permet aux vainqueurs de disputer la Coupe Principale. Les autres disputent la Coupe Consolante.</p>
        <label for="coupe" class="form-label me-2">Sélectionnez votre information</label>
        <?php
        $cupNames = $results->getCupNames();
        ?>
        <select name="cupName" id="cupName">
            <?php foreach ($cupNames as $cupName): ?>
                <option value="<?= $cupName['name'] ?>"><?= ($cupName['name']) ?></option>
            <?php endforeach; ?>
        </select>
 
        <div class="row d-flex center gap-3 mt-4">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                <h4 class="h4Results">Résultats</h4>
                </div>
                <div id="resultCup-container">
                    <!-- Les résultats seront chargés ici -->
                </div>
            </div>
            <!-- <div class="d-flex flex-column col-md-3">
                <div class="d-flex align-items-center">
                    <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                    <h4 class="h4Results text-center adjustFm">Feuilles de matchs</h4>
                </div>
                    <a href="/assets/documents/fm_coupe_.pdf" class="mx-auto mt-4" alt="feuille de match coupe" target="_blank">
                        <img src="/assets/icones/attestation-64.png" alt="feuille de match coupe" loading="lazy">
                    </a>
            </div> -->
        </div>
        </div>
        <!--Tournois-->
        <h3 class="h3Sports" id="tourn">Tournois</h3>
        <p class="lecture">Les tournois sont des compétitions individuelles ou par équipes sur une ou plusieurs journées.</p>
        <label for="tournament" class="form-label me-2">Sélectionnez votre information</label>
        <?php
        $tournamentNames = $results->getTournamentNames();
        ?>
        <select name="tournamentName" id="tournamentName">
            <?php foreach ($tournamentNames as $tournamentName): ?>
                <option value="<?= $tournamentName['name'] ?>"><?= ($tournamentName['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                    <h4 class="h4Results">Résultats</h4>
                </div>
                <div id="resultTournament-container">
                    <!-- Les résultats seront chargés ici -->
                </div>
            </div>
            <!-- <div class="col-md-3">
                <h4>Classements</h4>
                <div id="rankingTournament-container"> -->
                    <!-- Le classement sera chargé ici -->
                <!-- </div>
            </div> -->
        </div>
</section>
