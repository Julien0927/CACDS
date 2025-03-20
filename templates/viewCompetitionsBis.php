    <!-- Section Compétitions -->

        <!--Championnat-->
        <h3 class="h3Sports" id="compet">Championnat</h3>
<!--         <p class="lecture">Le championnat regroupe plusieurs poules où évoluent 8 équipes. Les matchs se déroulent en phase aller-retour.<br> 
            En fin de saison, les deux premiers montent en poule supérieure, les deux derniers descendent en poule inférieure</p>
 -->            <label for="sports" class="form-label me-2">Sélectionnez votre poule</label>
            <select name="poules" id="poules">
            <?php foreach ($poules as $poule): ?>
                <option value="<?= $poule['id'] ?>"><?= htmlspecialchars($poule['id']) ?></option>
            <?php endforeach; ?>

            </select>
            <div class="row d-flex justify-content-center mt-4">
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
            </div>
        <!--Coupe-->
        <h3 class="h3Sports" id="cup">Coupe</h3>
<!--         <p class="lecture">La coupe est une compétition à élimination directe. Les matchs se jouent en 3 sets gagnants.</p>
 -->        <label for="coupe" class="form-label me-2">Sélectionnez votre information</label>
        <?php
        $cupNames = $results->getCupNames();
        ?>
        <select name="cupName" id="cupName">
            <?php foreach ($cupNames as $cupName): ?>
                <option value="<?= $cupName['name'] ?>"><?= ($cupName['name']) ?></option>
            <?php endforeach; ?>
        </select>
 
        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <img src="/assets/icones/cercle-plein-15.png" class="mb-1 me-2" alt="icone" loading="lazy"/>
                    <h4 class="h4Results">Résultats</h4>
                </div>
                <div id="resultCup-container">
                    <!-- Les résultats seront chargés ici -->
                </div>
            </div>
            <!-- <div class="col-md-3">
                <h4>Classements</h4>
                <div id="rankingCup-container"> -->
                    <!-- Le classement sera chargé ici -->
                <!-- </div>
            </div> -->
        </div>
        </div>
        <!--Tournois-->
        <h3 class="h3Sports" id="tourn">Tournois</h3>
<!--         <p class="lecture">Les tournois sont des compétitions individuelles ou par équipes sur une ou plusieurs journées.</p>
 -->        <label for="tournament" class="form-label me-2">Sélectionnez votre information</label>
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
