
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

    // Mise à jour de la partie chargement des résultats
    const pouleSelect = document.getElementById('poules');
    const resultsContainer = document.getElementById('resultats-container');
    const classementContainer = document.getElementById('classement-container');
    const fmContainer = document.getElementById('fm-container');
    
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

        // Chargement de la feuille de match associée
        fetch(`get_fm.php?poule_id=${pouleId}&competition_id=1`, {
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
            fmContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Erreur:', error);
            fmContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement de la feuille de match.</p>';
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
//Fleche pour remonter en haut de la page
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

  //Faire pivoter l'icône de la flèche
  const toggleIcons = document.querySelectorAll(".toggle-icon");

  // Ajoute ou retire la classe "rotated" au clic
  toggleIcons.forEach(icon => {
    icon.addEventListener("click", () => {
      icon.classList.toggle("rotated"); // Alterne la classe "rotated"
    });
  });

  // Données du menu //

  document.querySelectorAll('[data-target]').forEach(element => {
    element.addEventListener('click', () => {
      const targetId = element.getAttribute('data-target');
      const targetElement = document.getElementById(targetId);
      targetElement.classList.toggle('show'); // Alterne entre affiché et masqué
    });
  });

  // Cards salles //
  const salles = [
    {
        nom: "Salle IMS, Niort",
        adresse: "100 route de Cherveux, 79000 Niort"
    },
    {
        nom: "Salle Cavaillès",
        adresse: "Rue Cavaillès, 79400 Saint-Maixent-l'École"
    },
    {
        nom: "Salle Omnisport de Magné",
        adresse: "Avenue du Marais Poitevin, 79460 Magné"
    },
    {
        nom: "Gymnase de Prahecq",
        adresse: "Rue des Écoles, 79230 Prahecq"
    },
    {
        nom: "Gymnase de Vouillé",
        adresse: "Rue des Écoles, 79230 Vouillé"
    },
    {
        nom: "Gymnase de Frontenay Rohan Rohan",
        adresse: "Rue des Moulins, 79270 Frontenay Rohan Rohan"
    },
    {
        nom: "Salle de la Venise Verte, Niort",
        adresse: "Rue Jacques Daguerre, 79000 Niort"
    },
    {
        nom: "Gymnase de Sainte-Néomaye",
        adresse: "Rue des Olympiades, 79260 Sainte-Néomaye"
    },
    {
        nom: "Complexe sportif de Celles-sur-Belle",
        adresse: "Rue des Cèdres, 79370 Celles-sur-Belle"
    },
    {
        nom: "Salle de sport de Chauray",
        adresse: "Rue du Pied Griffier, 79180 Chauray"
    },
    {
        nom: "Gymnase du collège de Coulonges-sur-l'Autize",
        adresse: "Rue du Calvaire, 79160 Coulonges-sur-l'Autize"
    },
    {
        nom: "Salle Gros Buisson, Bessines",
        adresse: "Le Gros Buisson, 79000 Bessines"
    },
    {
        nom: "Salle Omnisport de Champdeniers",
        adresse: "Chemin du Pré de l'Eteuf, 79220 Champdeniers-Saint-Denis"
    },
    {
        nom: "Salle Omnisports de Chatillon sur Thouet",
        adresse: "41 Av. St Exupéry, 79200 Chatillon sur Thouet"
    },
    {
        nom: "Gymnase Lycée Desfontaines, Melle",
        adresse: "79500 Melle"
    },
    {
        nom: "Complexe sportif de la MAIF, Niort",
        adresse: "11 Boulevard Salvador Allende, 79000 Niort"
    },
    {
        nom: "Salle Omnisports de La Crèche",
        adresse: "Rue de Barilleau, 79260 La Crèche"
    },
    {
        nom: "Espace Sportif Tartalin, Aiffres",
        adresse: "rue de Tartalin, 79230 Aiffres"
    },
    {
        nom: "Salle des sports de Périgné",
        adresse: "route de Celles, 79170 Périgné"
    },
    {
        nom: "Salle omnisport, Beauvoir sur Niort",
        adresse: "route de Chizé, 79360 Beauvoir sur Niort"
    },
    {
        nom: "Salle omnisport, Saint Maxire",
        adresse: "8 Rue des sports, 79410 Saint Maxire"
    },
    {
        nom: "Salle omnisport, Vasles",
        adresse: "7 Rue de la Cité, 79340 Vasles"
    },
    {
        nom: "Complexe sportif Henri Barbusse",
        adresse: "18 Rue Gustave Eiffel, 79000 Niort"
    }
];

function createGoogleMapsLink(salle) {
    const query = encodeURIComponent(`${salle.nom} ${salle.adresse}`);
    return `https://www.google.com/maps/search/?api=1&query=${query}`;
}

function createSalleCard(salle) {
    const mapsLink = createGoogleMapsLink(salle);
    return `
        <div class="salle-card">
            <div class="salle-nom">
                ${salle.nom}
            </div>
            <div class="salle-adresse">${salle.adresse}</div>
            <a href="${mapsLink}" target="_blank" class="maps-link">
            <i class="fas fa-map-marker-alt location-icon"></i>
                Voir sur Google Maps
            </a>
        </div>
    `;
}

function afficherSalles() {
    const container = document.getElementById('salles-container');
    container.innerHTML = salles.map(salle => createSalleCard(salle)).join('');
}

document.addEventListener('DOMContentLoaded', afficherSalles);

//Affichage feuille match//
const pouleSelect = document.getElementById('pouleSelect');
        const linkContainer = document.getElementById('linkContainer');

        pouleSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            if (!selectedValue) {
                linkContainer.innerHTML = '';
                return;
            }

            const fileName = selectedValue === 'coupe' ? 
                'fm_coupe_.pdf' : 
                `fm_poule_${selectedValue}.pdf`;

            const title = selectedValue === 'coupe' ? 
                'Feuille de match coupe' : 
                `Feuille de match poule ${selectedValue}`;

            linkContainer.innerHTML = `
                <a href="/assets/documents/${fileName}" 
                   class="fm-link" 
                   alt="Feuille de match" 
                   title="${title}"
                   style="text-decoration: none;">
                    <img src="/assets/icones/attestation-64.png" 
                         alt="icône document" 
                         class="mb-1" 
                         loading="lazy">
                    <span class="lecture">Télécharger la feuille</span>
                </a>
            `;
        });
        document.getElementById('poules').addEventListener('change', function() {
            document.getElementById('pouleSelect').value = this.value;
            document.getElementById('pouleSelect').dispatchEvent(new Event('change')); 
        });

// Script pour modifier dynamiquement l'attribut accept du champ de fichier
document.addEventListener('DOMContentLoaded', function() {
    const photoRadio = document.getElementById('type_photo'); // Changé 'type_image' en 'type_photo'
    const videoRadio = document.getElementById('type_video');
    const fileInput = document.getElementById('media_file');
    const fileHelp = document.getElementById('file_help');
    
    function updateFileInput() {
        if (photoRadio.checked) { // Changé 'imageRadio' en 'photoRadio'
            fileInput.setAttribute('accept', 'image/*');
            fileHelp.textContent = 'Formats acceptés: JPG, PNG, GIF (max 10MB)';
        } else {
            fileInput.setAttribute('accept', 'video/*');
            fileHelp.textContent = 'Formats acceptés: MP4, WebM, OGG (max 100MB)';
        }
    }
    
    photoRadio.addEventListener('change', updateFileInput); // Changé 'imageRadio' en 'photoRadio'
    videoRadio.addEventListener('change', updateFileInput);
    
    // Initialiser avec la valeur par défaut
    updateFileInput();
});

document.addEventListener("DOMContentLoaded", function () {
    const pouleSelect = document.getElementById('pouleSelect');

    // Déclencher manuellement l'événement change si une valeur est déjà sélectionnée
    if (pouleSelect.value) {
        pouleSelect.dispatchEvent(new Event('change'));
    }
});

