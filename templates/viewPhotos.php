<section class="container-fluid" id="gallery">
        <h2 class="h2Sports">Galerie Photos</h2>
        <hr>
        <p>Découvrez les meilleurs moments du club en images.</p>
        <div class="row d-flex justify-content-center">
            <?php foreach ($photoData as $photos): ?>
                <div class="col-6 col-md-4 mb-4 d-flex justify-content-center">
                    <a href="<?= $photos['image'] ?>" data-lightbox="photos" data-title="<?= $photos['title'] ?>">
                        <img src="<?= $photos['image'] ?>" class="img-fluid imgGallery" title="<?=$photos['title']?>" alt="photo">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
