<?php

$itemsPerPage = 15; // Nombre d'éléments par page
$totalItems = count($allClassements);
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer la page courante depuis l'URL
$currentPage = isset($_GET['pageRank']) ? (int)$_GET['pageRank'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est valide

// Calculer l'index de début
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Extraire les éléments pour la page courante
$classementsForCurrentPage = array_slice($allClassements, $startIndex, $itemsPerPage);
?>

<section class="container-fluid">
<h3 class="h2Sports">Mise à jour des classements</h3>
<hr>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm mt-3" id="tableClassements">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead class="table-dark">
                        <tr>
                            <th>Compétition</th>
                            <th>Poule</th>
                            <th>Journée/Nom</th>
                            <th>Classement</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classementsForCurrentPage)) { ?>
                            <?php foreach ($classementsForCurrentPage as $classement) { ?>
                                <tr>
                                    <td><?= ($classement["competition_type"]) ?></td>
                                    <td><?= $classement["competition_type"] === 'Championnat' ? ($classement["poule_id"]) : '-' ?></td>
                                    <td>
                                        <?php if ($classement["competition_type"] === 'Championnat'): ?>
                                            <?= ($classement["day_number"]) ?>
                                        <?php else: ?>
                                            <?= trim($classement["name"]) ?>
                             
                                            <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= ($classement["classement_pdf_url"]) ?>" title="<?= ($classement["classement_pdf_url"]) ?>" target="_blank">
                                            <img src="/assets/icones/pdf-250.png" alt="pdf" class="imgNew">
                                        </a>
                                    </td>
                                    <td><input type="checkbox" name="rankingBox[]" value="<?= $classement['id'] ?>"></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5">Aucun classement trouvé.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <nav aria-label="Navigation des pages">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pageRank=<?= $currentPage - 1 ?>#tableClassements"><</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?pageRank=<?= $i ?>#tableClassements"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pageRank=<?= $currentPage + 1 ?>#tableClassements">></a>
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
                <a href="addRanking.php" class="btn btn-card bold">Ajouter un classement</button></a>
                <?php addCSRFTokenToForm() ?>
                <button type="submit" class="btn btn-original bold" name="deleteClassement">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>
</section>

