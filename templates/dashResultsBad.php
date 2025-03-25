<?php
$itemsPerPage = 5; // Nombre d'éléments par page
$totalItems = count($allResults);
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer la page courante depuis l'URL
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est valide

// Calculer l'index de début
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Extraire les éléments pour la page courante
$resultsForCurrentPage = array_slice($allResults, $startIndex, $itemsPerPage);
?>
<section class="container-fluid">
<h3 class="h2Sports">Gestion des résultats</h3>
<hr>
<form method="POST" enctype="multipart/form-data">
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="table-responsive-sm mt-3" id="tableResults">
                    <table class="table table-striped table-responsive text-center nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th>Compétition</th>
                                <th>Poule</th>
                                <th>Journée/Nom</th>
                                <th>Résultat</th>
                                <th>Sélectionner</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($resultsForCurrentPage)) { ?>
                                <?php foreach ($resultsForCurrentPage as $result) { ?>
                                    <tr>
                                        <td><?= ($result["competition_type"]) ?></td>
                                        <td><?= $result["competition_type"] === 'Championnat' ? ($result["poule_id"]) : '-' ?></td>
                                        <td>
                                            <?php if ($result["competition_type"] === 'Championnat'): ?>
                                                <?= ($result["day_number"]) ?>
                                            <?php else: ?>
                                                <?= ($result["name"]) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= ($result["result_pdf_url"]) ?>" title="<?= ($result["result_pdf_url"]) ?>" target="_blank">
                                                <img src="/assets/icones/pdf-250.png" alt="pdf" class="imgNew">
                                            </a>
                                        </td>
                                        <td><input type="checkbox" name="resultBox[]" value="<?= $result['id'] ?>"></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">Aucun résultat trouvé.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                        <nav aria-label="Navigation des pages">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>#tableResults"><</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>#tableResults"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>#tableResults">></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex justify-content-end">
                <div class="my-3">
                    <a href="addScoresBad.php" class="btn btn-card bold">Ajouter un résultat</button></a>
                    <?php addCSRFTokenToForm() ?>
                    <button type="submit" class="btn btn-original bold" name="deleteResult">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</form>
</section>