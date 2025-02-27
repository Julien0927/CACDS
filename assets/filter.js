//*Filtre Media*//
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const mediaItems = document.querySelectorAll('.media-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Réinitialiser l'état des boutons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');
            console.log("Filtrage par:", filterValue);

            mediaItems.forEach(item => {
                const hasVideo = item.querySelector('video') !== null;
                const hasImage = item.querySelector('img.imgGallery') !== null;

                if (filterValue === 'all' || 
                    (filterValue === 'video' && hasVideo) || 
                    (filterValue === 'photo' && hasImage)) {
                    
                    // Afficher l'élément de manière fluide
                    item.classList.remove('hidden'); 
                } else {
                    // Appliquer la classe hidden pour masquer l'élément
                    item.classList.add('hidden');
                }
            });
        });
    });
});
