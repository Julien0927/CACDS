<h3 class="h2Sports ms-2">Mise à jour des classements</h3>
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="table-responsive-sm">
                <table class="table table-striped table-responsive text-center nowrap">
                    <thead>
                        <tr>
                            <th>Compétition</th>
                            <th>Poule</th>
                            <th>Journée/Nom</th>
                            <th>Classement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allClassements)) { ?>
                            <?php foreach ($allClassements as $classement) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($classement["competition_type"]) ?></td>
                                    <td><?= $classement["competition_type"] === 'Championnat' ? htmlspecialchars($classement["poule_id"]) : '-' ?></td>
                                    <td>
                                        <?php if ($result["competition_type"] === 'Championnat'): ?>
                                            <?= htmlspecialchars($classement["day_number"]) ?>
                                        <?php else: ?>
                                            <?= trim($classement["name"]) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= htmlspecialchars($classement["classement_pdf_url"]) ?>" title="<?= ($classement["classement_pdf_url"]) ?>" target="_blank">
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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-end">
            <div class=" my-3">
                <a href="addRanking.php" class="btn btn-card">Ajouter un classement</button></a>
                <?php addCSRFTokenToForm() ?>
                <button type="submit" class="btn btn-original" name="deleteClassement">Supprimer</button>
            </div>
        </div>
    </div>
</div>
</form>

