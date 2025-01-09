<h3 class="h2Sports ms-2">Gestion des articles</h3>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Titre</th>
                            <th class="d-none d-md-table-cell">Contenu</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allNews as $new) { ?>
                        <tr class="allNews text-start">
                            <td><?=($new["date"])?></td>
                            <td><?=($new["title"])?></td>
                            <td class="content d-none d-md-table-cell"><?=($new["content"])?></td>
                            <td class="text-center"><img src="<?=($new["image"])?>" class="imgNew"></td>
                            <td><input type="checkbox" name="newBox[]" value="<?= $new['id'] ?>"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-end">
            <div class=" my-3">
                <a href="addNews.php" class="btn btn-card">Ajouter un article</button></a>
                <button type="submit" class="btn btn-second " name="deleteNew">Modifier</button>
                <button type="submit" class="btn btn-original" name="deleteNew">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>
