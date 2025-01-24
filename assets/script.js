
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
    const dayNumberSelect = document.getElementById('day_number');
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

document.addEventListener('DOMContentLoaded', () => {
    // ... (Le code pour la gestion des affichages reste identique) ...

    // Mise à jour de la partie chargement des résultats
    const pouleSelect = document.getElementById('poules');
    const resultsContainer = document.getElementById('resultats-container');
    const classementContainer = document.getElementById('classement-container');
    
    // Chargement des résultats du championnat
    if (pouleSelect) {
        loadResults(pouleSelect.value);
        pouleSelect.addEventListener('change', function() {
            loadResults(this.value);
        });
    }

    // Chargement des résultats de coupe
    const cupSelect = document.getElementById('cupName');
    const cupResultsContainer = document.getElementById('resultCup-container');
    const cupRankingContainer = document.getElementById('rankingCup-container');
    
    if (cupSelect) {
        loadCupResults(cupSelect.value);
        loadCupRanking(cupSelect.value);
        cupSelect.addEventListener('change', function() {
            loadCupResults(this.value);
            loadCupRanking(this.value);
        });
    }

    // Chargement des résultats de tournoi
    const tournamentSelect = document.getElementById('tournamentName');
    const tournamentResultsContainer = document.getElementById('resultTournament-container');
    const tournamentRankingContainer = document.getElementById('rankingTournament-container');

    if (tournamentSelect) {
        loadTournamentResults(tournamentSelect.value);
        loadTournamentRanking(tournamentSelect.value);
        tournamentSelect.addEventListener('change', function() {
            loadTournamentResults(this.value);
            loadTournamentRanking(this.value);
        });
    }

    // Fonctions de chargement mises à jour
    function loadResults(pouleId) {
        fetch(`get_results.php?poule_id=${pouleId}&competition_id=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            resultsContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            resultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
        });

        // Chargement du classement associé
        fetch(`get_classement.php?poule_id=${pouleId}&competition_id=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            classementContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            classementContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du classement.</p>';
        });
    }

    function loadCupResults(cupName) {
        fetch(`get_results.php?cupName=${encodeURIComponent(cupName)}&competition_id=2`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            cupResultsContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            cupResultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
        });
    }

    function loadCupRanking(cupName) {
        fetch(`get_classement.php?cupName=${encodeURIComponent(cupName)}&competition_id=2`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            cupRankingContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            cupRankingContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du classement.</p>';
        });
    }

    function loadTournamentResults(tournamentName) {
        fetch(`get_results.php?tournamentName=${encodeURIComponent(tournamentName)}&competition_id=3`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            tournamentResultsContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            tournamentResultsContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement des résultats.</p>';
        });
    }

    function loadTournamentRanking(tournamentName) {
        fetch(`get_classement.php?tournamentName=${encodeURIComponent(tournamentName)}&competition_id=3`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(data => {
            tournamentRankingContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            tournamentRankingContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du classement.</p>';
        });
    }
});

// Remplir la modal avec les données dynamiques
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('newsModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalContent = document.getElementById('modalContent');
    const modalDate = document.getElementById('modalDate');
    const moreArticles = document.getElementById('moreArticles');

    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget; // Bouton qui a déclenché la modal
        const title = button.getAttribute('data-title');
        const image = button.getAttribute('data-image');
        const content = button.getAttribute('data-content');
        const date = button.getAttribute('data-date');
        const sportId = button.getAttribute('data-sport-id');

        // Met à jour le contenu de la modal
        modalTitle.textContent = title;
        modalImage.src = image;
        modalContent.textContent = content;
        modalDate.textContent = `Publié le : ${date}`;
        if (sportId === '1') {
            moreArticles.href = 'tennisDT.php';
        } else if (sportId === '2') {
            moreArticles.href = 'badminton.php';
        } else if (sportId === '3') {
            moreArticles.href = 'petanque.php';
        } else if (sportId === '4') {
            moreArticles.href = 'volley.php';
        } else {
            moreArticles.href = 'index.php';
        }
    });
});