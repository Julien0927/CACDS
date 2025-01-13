/*Sélection dynamique des poules*/
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

// Écouteur d'événement sur le select des compétitions
competitionSelect.addEventListener('change', () => {
    // Vérifier si "Championnat" est sélectionné
    if (competitionSelect.value === 'Championnat') {
        // Afficher le conteneur
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
        
    } else {
        // Masquer le conteneur si ce n'est pas "Championnat"
        poulesResultsContainer.style.display = 'none';
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