
 <div class="col-12 col-md-4 mb-4">
    <div class="card h-100">
        <div class="row g-0">
            <?php if (!empty($new["image"])): ?>
                <div class="col-12 col-md-4">
                    <?php if (!empty($new['image'])): ?>
                        <?php
                            $filePath = htmlspecialchars($new['image']);
                            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        ?>
                        <?php if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                            <img src="<?= $filePath ?>" alt="Image" class="img-fluid rounded rounded-md-start">
                        <?php elseif ($extension === 'pdf'): ?>
                            <a href="<?= $filePath ?>" target="_blank" >
                                <img src="/assets/icones/pdf-250.png" alt="document" class="img-fluid mx-auto mt-4" >
                            </a>
                        <?php else: ?>
                        <?php endif; ?>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-md-8">
            <?php else: ?>
                <div class="col-12">
            <?php endif; ?>
                <div class="card-body d-flex flex-column h-100">
                    <h5 class="h5Sports card-title"><?= ($new["title"]) ?></h5>
                    <p class="card-text flex-grow-1 lecture">
                        <?= mb_strlen($new["content"]) > 100 ? mb_substr($new["content"], 0, 100) . '...' : ($new["content"]) ?>
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
                <h1 class="h5Sports modal-title fs-5" id="staticBackdropLabel-<?= $new["id"] ?>"><?= $new["title"] ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="ms-3"><?= date('d/m/Y', strtotime($new["date"])) ?></p>
            <div class="lecture modal-body" style="text-align: justify;">
                
            <!-- <?php if (!empty($new["image"])): ?>
                <img src="<?= htmlspecialchars($new['image']) ?>" class="imgModal mb-3" alt="Image new">
            <?php endif; ?> -->
            <?php if (!empty($new['image'])): ?>
                                    <?php
                                        $filePath = htmlspecialchars($new['image']);
                                        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                    ?>
                                    <?php if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                        <img src="<?= $filePath ?>" alt="Image" class="imgModal mb-3" alt="Image new">
                                    <?php elseif ($extension === 'pdf'): ?>
                                        <a href="<?= $filePath ?>" target="_blank" >
                                            <img src="/assets/icones/pdf-250.png" alt="document" class="imgModal mb-3" >
                                        </a>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                <?php else: ?>
                                    
                                <?php endif; ?>
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