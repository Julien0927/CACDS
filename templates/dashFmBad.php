<?php

$itemsPerPage = 15; // Nombre d'éléments par page
$totalItems = count($allFm);
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer la page courante depuis l'URL
$currentPage = isset($_GET['pageRank']) ? (int)$_GET['pageRank'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est valide

// Calculer l'index de début
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Extraire les éléments pour la page courante
$fmForCurrentPage = array_slice($allFm, $startIndex, $itemsPerPage);
?>

<section class="container-fluid">
<h3 class="h2Sports">Mise à jour des feuilles de matchs</h3>
<hr>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm mt-3" id="tableFm">
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
                        <?php if (!empty($fmForCurrentPage)) { ?>
                            <?php foreach ($fmForCurrentPage as $fm) { ?>
                                <tr>
                                    <td><?=($fm["competition_type"]) ?></td>
                                    <td><?= ($fm["competition_type"]) === 'Championnat' ? ($fm["poule_id"]) : '-' ?></td>
                                    <td>
                                        <?php if (($fm["competition_type"]) === 'Championnat'): ?>
                                            <?= ($fm["day_number"]) ?>
                                        <?php else: ?>
                                            <?= trim(($fm["name"])) ?>
                             
                                            <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= ($fm["fm_pdf_url"]) ?>" title="<?= ($fm["fm_pdf_url"]) ?>" target="_blank">
                                            <img src="/assets/icones/pdf-250.png" alt="pdf" class="imgNew">
                                        </a>
                                    </td>
                                    <td><input type="checkbox" name="fmBox[]" value="<?= $fm['id'] ?>"></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5">Aucune feuille de match trouvée.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <nav aria-label="Navigation des pages">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pageRank=<?= $currentPage - 1 ?>#tableFm"><</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?pageRank=<?= $i ?>#tableFm"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pageRank=<?= $currentPage + 1 ?>#tableFm">></a>
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
                <a href="addPaperMatch.php" class="btn btn-card bold">Ajouter une feuille de match</button></a>
                <?php addCSRFTokenToForm() ?>
                <button type="submit" class="btn btn-original bold" name="deletePaperMatch">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>
</section>

