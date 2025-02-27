
<?php
require_once 'lib/security.php';

$itemsPerPage = 5; // Nombre d'éléments par page
$totalItems = count($allPhotos);
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer la page courante depuis l'URL
$currentPage = isset($_GET['pagePhotos']) ? (int)$_GET['pagePhotos'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est valide

// Calculer l'index de début
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Extraire les éléments pour la page courante
$photosForCurrentPage = array_slice($allPhotos, $startIndex, $itemsPerPage);
?>

<section class="container-fluid">
    <h3 class="h2Sports">Gestion des photos et vidéos</h3>
    <hr>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <div class="table-responsive-sm mt-3" id="tablePhotos">
                        <table class="table table-striped table-responsive text-center nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Aperçu</th>
                                    <th scope="col">Titre</th>
                                    <th class="text-center" scope="col">Sélection</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($allPhotos)) { ?>
                                <?php foreach ($allPhotos as $photo): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($photo['date'])) ?></td>
                                        <td>
                                            <?php if ($photo['type'] === 'photo'): ?> 
                                                <span class="badge bg-photo">Photo</span>
                                            <?php else: ?>
                                                <span class="badge bg-video">Vidéo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($photo['type'] === 'photo'): ?> 
                                                <img src="<?= $photo['image'] ?>" alt="<?= $photo['title'] ?>" width="100" class="img-thumbnail">
                                            <?php else: ?>
                                                <video width="100" controls>
                                                    <source src="<?= $photo['video'] ?>" type="video/mp4">
                                                    Vidéo non supportée
                                                </video>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $photo['title'] ?></td>
                                        <td class="text-center">
                                            <input type="checkbox" name="photoBox[]" value="<?= $photo['id'] ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5">Aucun média trouvé.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <nav aria-label="Navigation des pages">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pageNews=<?= $currentPage - 1 ?>#tableNews"><</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?pageNews=<?= $i ?>#tableNews"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pageNews=<?= $currentPage + 1 ?>#tableNews">></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <div class="my-3">
                    <a href="addMedia.php" class="btn btn-card bold">Ajouter un média</a>
                    <?php addCSRFTokenToForm(); ?>
                    <button type="submit" name="deletePhoto" class="btn btn-original bold">Supprimer</button>
                </div>
            </div>
        </div>
    </form>
</section>