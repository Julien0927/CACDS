console.log('Script initialisé');
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM chargé');
            
            // Gestion du collapse
            const toggleIcon = document.getElementById('toggleCollapselastSeason');
            const collapseContent = document.getElementById('collapseContentlastSeason');
            
            console.log('Toggle icon:', toggleIcon);
            console.log('Collapse content:', collapseContent);
            
            if (toggleIcon && collapseContent) {
                toggleIcon.addEventListener('click', function() {
                    console.log('Toggle cliqué');
                    collapseContent.classList.toggle('collapse');
                    this.style.transform = collapseContent.classList.contains('collapse') 
                        ? 'rotate(0deg)' 
                        : 'rotate(180deg)';
                });
            }

            // Gestion de la sélection des saisons
            const seasonSelect = document.getElementById('seasonSelect');
            const linkSeasonContainer = document.getElementById('linkSeasonContainer');

            console.log('Season select:', seasonSelect);
            console.log('Link container:', linkSeasonContainer);

            if (seasonSelect && linkSeasonContainer) {
                seasonSelect.addEventListener('change', function() {
                    console.log('Changement de saison');
                    const selectedValue = this.value;
                    console.log('Valeur sélectionnée:', selectedValue);
                    
                    if (!selectedValue) {
                        linkSeasonContainer.innerHTML = '';
                        return;
                    }
                    
                    const yearStart = 2000 + parseInt(selectedValue);
                    const yearEnd = yearStart + 1;
                    const fullYears = `${yearStart}-${yearEnd}`;
                    
                    console.log('Années:', fullYears);
                    
                    const poules = ['1', '2', '3'];
                    
                    const linksHTML = poules.map(poule => `
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
                    `).join('');
                    
                    console.log('HTML généré:', linksHTML);
                    linkSeasonContainer.innerHTML = linksHTML;
                });
            }
        }); 