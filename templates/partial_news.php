<!-- 
<div class="d-flex justify-content-evenly col-12 col-md-4 mb-5">
    <div class="card" style="width: 20rem;">
        <img src="<?= $new["image"] ?>" class="card-img-top imgBlog" alt="image new">
        <div class="card-body">
            <p class="card-text-date"><?= $new["date"] ?></p>
            <h5 class="card-title"><?= $new["title"] ?></h5>
            <p class="card-text-blog">
                <?= mb_strlen($new["content"]) > 100 ? mb_substr($new["content"], 0, 100) . '...' :$new["content"] ?></p>
            <button type="button" class="btn btn-card btn-fixed " data-bs-toggle="modal" data-bs-target="#staticBackdrop-<?= $new["id"] ?>">
                Lire plus...
            </button>
        </div>
    </div>
</div>-->
<!-- Modal -->
<!--<div class="modal fade" id="staticBackdrop-<?=($new["id"])?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel-<?=($new["id"])?>"><?=($new["title"])?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="ms-3"><?=($new["date"])?></p>
            <div class="modal-body" style="text-align: justify;">
                <img src="<?=($new['image'])?>" class="imgModal" alt="Image new">
                <?php
                $fullNew = $news->getNewById($new['id']);
                echo $fullNew['content']; // Affiche le contenu complet
                ?>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-5">
                <button type="button" class="btn btn1 px-5 mx-auto" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
 -->
 <div class="d-flex justify-content-evenly col-12 col-md-4 mb-5">
    <div class="card" style="width: 20rem;">
        <img src="<?= $new["image"] ?>" class="card-img-top imgBlog" alt="image new">
        <div class="card-body">
            <p class="card-text-date"><?= date('d/m/Y', strtotime($new["date"])) ?></p>
            <h5 class="card-title"><?= $new["title"] ?></h5>
            <p class="card-text-blog">
                <?= mb_strlen($new["content"]) > 100 ? mb_substr($new["content"], 0, 100) . '...' : $new["content"] ?>
            </p>
            <button type="button" class="btn btn-card btn-fixed" data-bs-toggle="modal" data-bs-target="#staticBackdrop-<?= $new["id"] ?>">
                Lire plus...
            </button>
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
                <img src="<?= $new['image'] ?>" class="imgModal" alt="Image new">
                <?php
                $fullNew = $news->getNewById($new['id']);
                echo $fullNew['content']; // Affiche le contenu complet
                ?>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-5">
                <button type="button" class="btn btn1 px-5 mx-auto" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>