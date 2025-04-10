
<section class="container-fluid" id="gallery">
    <h2 class="h2Sports mt-3">Photos et Vidéos</h2>
    <hr>
    <p class="lecture">Découvrez les meilleurs moments du club en images et en vidéos.</p>
    
    <!-- Boutons de filtre -->
    <div class="filter-buttons mb-4 text-center">
        <button class="btn btn-original m-2 filter-btn active" data-filter="all">Tous</button>
        <button class="btn btn-original m-2 filter-btn" data-filter="photo">Photos</button> 
        <button class="btn btn-original m-2 filter-btn" data-filter="video">Vidéos</button>
    </div>
    
    <div class="row d-flex justify-content-center">
        <?php foreach ($photoData as $media): ?>
            <div class="col-6 col-md-4 mb-4 d-flex justify-content-center media-item <?= $media['type'] ?>" data-type="<?= $media['type'] ?>">
                <?php if ($media['type'] === 'photo'): ?> 
                    <a href="<?= $media['image'] ?>" data-lightbox="photos" data-title="<?= $media['title'] ?>">
                        <img src="<?= $media['image'] ?>" class="img-fluid imgGallery" title="<?= $media['title'] ?>" alt="<?= $media['title'] ?>">
                    </a>
                    <div class="media-info d-flex flex-column text-center mt-2">
                        <h5><?= htmlspecialchars($media['title']) ?></h5>
                        <p class="text-muted"><?= date('d/m/Y', strtotime($media['date'])) ?></p> <!-- Date formatée -->
                    </div>
                <?php else: ?>
                    <div class="video-container">
                        <video controls class="img-fluid">
                            <source src="<?= $media['video'] ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                        <div class="video-title"><?= $media['title'] ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

