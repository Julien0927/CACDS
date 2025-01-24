<?php

$itemsPerPage = 6; // Nombre d'éléments par page
$totalItems = count($allNews);
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer la page courante depuis l'URL
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est valide

// Calculer l'index de début
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Extraire les éléments pour la page courante
$newsForCurrentPage = array_slice($allNews, $startIndex, $itemsPerPage);
?>

<h3 class="h2Sports ms-2">Gestion des articles</h3>
<hr>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm" id="tableNews">
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
                        <?php foreach ($newsForCurrentPage as $new) { ?>
                        <tr class="allNews text-start">
                            <td><?=($new["date"])?></td>
                            <td><?=($new["title"])?></td>
                            <td class="content d-none d-md-table-cell"><?=mb_strlen($new["content"]) > 150 ? mb_substr($new["content"], 0, 150) . '...' : $new["content"] ?></td>
                            <td class="text-center"><img src="<?=($new["image"])?>" class="imgNew"></td>
                            <td><input type="checkbox" name="newBox[]" value="<?= $new['id'] ?>"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <nav aria-label="Navigation des pages">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>#tableNews"><</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>#tableNews"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>#tableNews">></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-end">
            <div class=" my-3">
                <a href="addNews.php" class="btn btn-card">Ajouter un article</button></a>
                <?php addCSRFTokenToForm() ?>
                <button type="submit" class="btn btn-second" name="updateNew">Modifier</button>
                <button type="submit" class="btn btn-original" name="deleteNew">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>
