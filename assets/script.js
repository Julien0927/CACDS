/*Sélection dynamique des poules*/
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