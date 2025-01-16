<h3 class="h2Sports ms-2">Ajouter une photo</h3>
<div class="row">
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allPhotos)) { ?>
                            <?php foreach ($allPhotos as $photo) { ?>
                                <tr>
                                    <td><?= $photo["title"]?></td>
                                    <td><img src="<?=($photo["image"]) ?>" class="imgNew"></td>
                                    <td><input type="checkbox" name="photoBox[]" value="<?= $photo['id'] ?>"></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5">Aucun résultat trouvé.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <div class="d-flex justify-content-end">
            <div class=" my-3">
                <a href="addPhotos.php" class="btn btn-card">Ajouter une photo</button></a>
                <?php addCSRFTokenToForm() ?>
                <button type="submit" class="btn btn-original" name="deletePhoto">Supprimer</button>
            </div>
        </div>
    </div>
</form>