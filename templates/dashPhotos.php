
<?php
require_once 'lib/security.php';

$itemsPerPage = 5; // Nombre d'éléments par page
$totalItems = count($allPhotos);
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer la page courante depuis l'URL
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est valide

// Calculer l'index de début
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Extraire les éléments pour la page courante
$photosForCurrentPage = array_slice($allPhotos, $startIndex, $itemsPerPage);
?>

<h3 class="h2Sports ms-2">Gestion des photos</h3>
<hr>
<div class="row">
    <form method="POST" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <div class="table-responsive-sm" id="tablePhotos">
                        <table class="table table-striped table-responsive text-center nowrap">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Photo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($photosForCurrentPage)) : ?>
                                    <?php foreach ($photosForCurrentPage as $photo) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($photo["title"]) ?></td>
                                            <td><img src="<?= htmlspecialchars($photo["image"]) ?>" class="imgNew" alt="<?= htmlspecialchars($photo["title"]) ?>"></td>
                                            <td><input type="checkbox" name="photoBox[]" value="<?= (int)$photo['id'] ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3">Aucune photo disponible.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <nav aria-label="Navigation des pages">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>#tablePhotos"><</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>#tablePhotos"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>#tablePhotos">></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <div class="my-3">
                    <a href="addPhotos.php" class="btn btn-card">Ajouter une photo</a>
                    <?php addCSRFTokenToForm() ?>
                    <button type="submit" class="btn btn-original" name="deletePhoto">Supprimer</button>
                </div>
            </div>
        </div>
    </form>
</div>