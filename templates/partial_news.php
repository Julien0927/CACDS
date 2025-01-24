
 <div class="col-12 col-md-4 mb-4">
    <div class="card h-100">
        <div class="row g-0">
            <div class="col-12 col-md-4">
                <img src="<?= $new["image"] ?>" 
                     class="img-fluid rounded-top rounded-md-start" 
                     alt="image new"
                     style="object-fit: cover; height: 200px;">
            </div>
            <div class="col-12 col-md-8">
                <div class="card-body d-flex flex-column h-100">
                    <h5 class=" h5Sports card-title"><?= $new["title"] ?></h5>
                    <p class="card-text flex-grow-1">
                        <?= mb_strlen($new["content"]) > 100 ? mb_substr($new["content"], 0, 100) . '...' : $new["content"] ?>
                    </p>
                    <div class="mt-auto">
                        <p class="card-text">
                            <small class="text-body-secondary"><?= date('d/m/Y', strtotime($new["date"])) ?></small>
                        </p>
                        <button type="button" 
                                class="btn btn-card w-100 w-md-auto" 
                                data-bs-toggle="modal" 
                                data-bs-target="#staticBackdrop-<?= $new["id"] ?>">
                            Lire plus...
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop-<?= $new["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel-<?= $new["id"] ?>"><?= $new["title"] ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="ms-3"><?= date('d/m/Y', strtotime($new["date"])) ?></p>
            <div class="modal-body" style="text-align: justify;">
                <img src="<?= $new['image'] ?>" class="imgModal mb-3" alt="Image new">
                <?php
                $fullNew = $news->getNewById($new['id']);
                echo $fullNew['content']; // Affiche le contenu complet
                ?>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-5">
                <button type="button" class="btn btn-original px-5 mx-auto" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>