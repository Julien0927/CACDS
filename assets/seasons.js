// Gestion du collapse
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du toggle collapse
    const toggleIcon = document.getElementById('toggleCollapselastSeason');
    const collapseContent = document.getElementById('collapseContentlastSeason');
    
    toggleIcon.addEventListener('click', function() {
        // Toggle de la classe collapse
        collapseContent.classList.toggle('collapse');
        
        // Rotation de l'icône
        this.style.transform = collapseContent.classList.contains('collapse') 
            ? 'rotate(0deg)' 
            : 'rotate(180deg)';
    });

    const poulesParSaison = {
        '23': 2,
        '22': 2,
        '21': 2,
        '20': 2,
        '19': 2,
        '18': 2,
        '17': 3,
        '16': 3,
        '15': 1
    };
    // Gestion de la sélection des saisons
    const seasonSelect = document.getElementById('seasonSelect');
    const linkSeasonContainer = document.getElementById('linkSeasonContainer');

    if (!seasonSelect || !linkSeasonContainer) {
        console.error('Éléments non trouvés');
        return;
    }

    seasonSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        
        if (!selectedValue) {
            linkSeasonContainer.innerHTML = '';
            return;
        }

        const nbPoules = poulesParSaison[selectedValue] || 3;
        
        const yearStart = 2000 + parseInt(selectedValue);
        const yearEnd = yearStart + 1;
        const fullYears = `${yearStart}-${yearEnd}`;
        
        const linksHTML = Array.from({ length: nbPoules }, (_, index) => {
            const poule = index + 1;
            return  `
            <div class="poule-links mb-3">
                <h5 class="text-center">Poule ${poule}</h5>
                <a href="/assets/documents/resultats_poule_${poule}_${fullYears}.pdf"
                   class="fm-link d-block mb-2"
                   alt="Résultats"
                   title="Résultats poule ${poule} ${fullYears}"
                   style="text-decoration: none;">
                    <img src="/assets/icones/attestation-64.png"
                         alt="icône document"
                         class="mb-1"
                         loading="lazy">
                    <span class="lecture">Résultats</span>
                </a>
                <a href="/assets/documents/classement_poule_${poule}_${fullYears}.pdf"
                   class="fm-link d-block"
                   alt="Classement"
                   title="Classement poule ${poule} ${fullYears}"
                   style="text-decoration: none;">
                    <img src="/assets/icones/attestation-64.png"
                         alt="icône document"
                         class="mb-1"
                         loading="lazy">
                    <span class="lecture">Classement</span>
                </a>
            </div>
            `;
    }).join('');
        
        linkSeasonContainer.innerHTML = linksHTML;
    });
});