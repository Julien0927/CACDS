<h3 class="h2Sports ms-2">Gestion des résultats</h3>
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
                                <th>Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($allResults)) { ?>
                                <?php foreach ($allResults as $result) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($result["competition_type"]) ?></td>
                                        <td><?= $result["competition_type"] === 'Championnat' ? htmlspecialchars($result["poule_id"]) : '-' ?></td>
                                        <td>
                                            <?php if ($result["competition_type"] === 'Championnat'): ?>
                                                <?= htmlspecialchars($result["day_number"]) ?>
                                            <?php else: ?>
                                                <?= ($result["name"]) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= htmlspecialchars($result["result_pdf_url"]) ?>" title="<?= ($result["result_pdf_url"]) ?>" target="_blank">
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex justify-content-end">
                <div class="my-3">
                    <a href="addScores.php" class="btn btn-card">Ajouter un résultat</button></a>
                    <?php addCSRFTokenToForm() ?>
                    <button type="submit" class="btn btn-original" name="deleteResult">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</form>