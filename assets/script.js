/*Sélection dynamique des poules badminton*/
document.addEventListener('DOMContentLoaded', () => {
const sportsSelect = document.getElementById('sports');
    const poulesContainer = document.getElementById('poules-container');
    const poulesSelect = document.getElementById('poules');

    // Ajouter un écouteur d'événement pour le select des sports
    sportsSelect.addEventListener('change', ()  => {
        // Vérifier si "Badminton" est sélectionné
        if (sportsSelect.value === '2') {
            // Afficher le conteneur du nouveau select
            poulesContainer.style.display = 'block';
            
            // Vider le menu déroulant des poules avant de le remplir
            poulesSelect.innerHTML = '';
            
            // Ajouter les options de 1 à 8
            for (let i = 1; i <= 8; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Poule ${i}`;
                poulesSelect.appendChild(option);
            }
        } else {
            // Masquer le conteneur si "Badminton" n'est pas sélectionné
            poulesContainer.style.display = 'none';
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
    // Récupération des éléments
    const competitionSelect = document.getElementById('results');
    const poulesResultsContainer = document.getElementById('poules-results-container');
    const poulesResultsSelect = document.getElementById('poulesResults');
    const competitionNameContainer = document.getElementById('competitionNameContainer');
    const nameInput = document.getElementById('name'); // Ajout du nouvel élément

    // Écouteur d'événement sur le select des compétitions
    competitionSelect.addEventListener('change', () => {
        // Vérifier si "Championnat" est sélectionné
        if (competitionSelect.value === 'Championnat') {
            // Afficher le conteneur des poules
            poulesResultsContainer.style.display = 'block';
            
            // Vider le select des poules
            poulesResultsSelect.innerHTML = '';
            
            // Générer les options de 1 à 8
            for (let i = 1; i <= 8; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Poule ${i}`;
                poulesResultsSelect.appendChild(option);
            }
            
            // Masquer la rubrique "nom de la compétition"
            competitionNameContainer.style.display = 'none';
            
            // Réinitialiser et rendre le champ nom non obligatoire
            if (nameInput) {
                nameInput.value = '';
                nameInput.required = false;
            }
        } 
        // Vérifier si "Coupe" ou "Tournoi" est sélectionné
        else if (competitionSelect.value === 'Coupe' || competitionSelect.value === 'Tournoi') {
            // Masquer le conteneur des poules
            poulesResultsContainer.style.display = 'none';

            // Afficher la rubrique "nom de la compétition"
            competitionNameContainer.style.display = 'block';
            
            // Rendre le champ nom obligatoire
            if (nameInput) {
                nameInput.required = true;
            }
        } 
        // Sinon, tout masquer
        else {
            poulesResultsContainer.style.display = 'none';
            competitionNameContainer.style.display = 'none';
            
            // Réinitialiser le champ nom
            if (nameInput) {
                nameInput.value = '';
                nameInput.required = false;
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const competitionSelect = document.getElementById('results');
    const dayNumberSelect = document.getElementById('dayNumber');
    const dayNumberContainer = document.getElementById('dayNumber-container');
    competitionSelect.addEventListener('change', () => {
        if (competitionSelect.value === 'Championnat') {
            dayNumberContainer.style.display = 'block';
            dayNumberSelect.innerHTML = '';
        } else {
            dayNumberContainer.style.display = 'none';
        }
    });
    
});

document.addEventListener('DOMContentLoaded', function() {
    const pouleSelect = document.getElementById('poules');
    const resultsContainer = document.getElementById('resultats-container');
    
    // Charger les résultats initiaux
    loadResults(pouleSelect.value);
    
    // Écouter les changements de poule
    pouleSelect.addEventListener('change', function() {
        loadResults(this.value);
    });
    
    function loadResults(pouleId) {
        fetch(`get_results.php?poule_id=${pouleId}&competition_id=1`) // 1 pour le championnat
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                resultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
            });
    }
});
/*Affichage dynamique des résultats de coupe*/
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire existant pour les poules
    const pouleSelect = document.getElementById('poules');
    const resultsContainer = document.getElementById('resultats-container');
    
    if (pouleSelect) {
        loadResults(pouleSelect.value);
        pouleSelect.addEventListener('change', function() {
            loadResults(this.value);
        });
    }

    // Nouveau gestionnaire pour les coupes
    const cupSelect = document.getElementById('cupName');
    const cupResultsContainer = document.getElementById('resultCup-container');
    const cupRankingContainer = document.getElementById('rankingCup-container');
    
    if (cupSelect) {
        loadCupResults(cupSelect.value);
        cupSelect.addEventListener('change', function() {
            loadCupResults(this.value);
        });
    }
    if (cupSelect) {
        loadCupRanking(cupSelect.value);
        cupSelect.addEventListener('change', function() {
            loadCupRanking(this.value);
        });
    }
    
    function loadResults(pouleId) {
        fetch(`get_results.php?poule_id=${pouleId}&competition_id=1`)
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                resultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
            });
    }
    
    function loadCupResults(cupName) {
        fetch(`get_results.php?cupName=${encodeURIComponent(cupName)}&competition_id=2`) // 2 pour la coupe
            .then(response => response.text())
            .then(data => {
                cupResultsContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                cupResultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
            });
    }

    function loadCupRanking(cupName) {
        fetch(`get_cup_classement.php?cupName=${encodeURIComponent(cupName)}&competition_id=2`) // 2 pour la coupe
            .then(response => response.text())
            .then(data => {
                cupRankingContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                cupRankingContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du classement.</p>';
            });
    }

    // Gestionnaire pour les tournois
    const tournamentSelect = document.getElementById('tournamentName');
    const tournamentResultsContainer = document.getElementById('resultTournament-container');
    const tournamentRankingContainer = document.getElementById('rankingTournament-container');

    if (tournamentSelect) {
        loadTournamentResults(tournamentSelect.value);
        tournamentSelect.addEventListener('change', function() {
            loadTournamentResults(this.value);
        });
    }

    if (tournamentSelect) {
        loadTournamentRanking(tournamentSelect.value);
        tournamentSelect.addEventListener('change', function() {
            loadTournamentRanking(this.value);
        });
    }

    function loadTournamentResults(tournamentName) {
        fetch(`get_results.php?tournamentName=${encodeURIComponent(tournamentName)}&competition_id=3`) // 3 pour le tournoi
            .then(response => response.text())
            .then(data => {
                tournamentResultsContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                tournamentResultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
            });
    }

    function loadTournamentRanking(tournamentName) {
        fetch(`get_tournament_classement.php?tournamentName=${encodeURIComponent(tournamentName)}&competition_id=3`) // 3 pour le tournoi
            .then(response => response.text())
            .then(data => {
                tournamentRankingContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                tournamentRankingContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du classement.</p>';
            });
    }
    });

/*Affichage dynamique des résultats de tournoi*/


document.addEventListener('DOMContentLoaded', function() {
    const pouleSelect = document.getElementById('poules');
    const resultsContainer = document.getElementById('resultats-container');
    const classementContainer = document.getElementById('classement-container');
    
    // Charger les données initiales
    loadData(pouleSelect.value);
    
    // Écouter les changements de poule
    pouleSelect.addEventListener('change', function() {
        loadData(this.value);
    });
    
    function loadData(pouleId) {
        // Charger les résultats
        fetch(`get_results.php?poule_id=${pouleId}&competition_id=1`)
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                resultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
            });
            
        // Charger le classement
        fetch(`get_classement.php?poule_id=${pouleId}&competition_id=1`)
            .then(response => response.text())
            .then(data => {
                classementContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur:', error);
                classementContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du classement.</p>';
            });
    }
});