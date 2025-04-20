<?php
// Traitement de l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['contenu'])) {
    if (verifyCSRFToken()) {
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        if (!empty($titre) && !empty($contenu)) {
            if ($actualiteManager->addActualite($titre, $contenu)) {
                $_SESSION['success_message'] = "Actualité ajoutée avec succès.";
                header('Location: dashboardAdmin.php?tab=actualite');
                exit;
            } else {
                $errors[] = "Erreur lors de l’ajout de l’actualité.";
            }
        } else {
            $errors[] = "Tous les champs sont obligatoires.";
        }
    } else {
        $errors[] = "Erreur de sécurité (CSRF).";
    }
}

// Suppression d'une actualité
if (isset($_POST['delete_actualite'])) {
    if (verifyCSRFToken()) {
        $id = (int) $_POST['actualite_id'];
        if ($actualiteManager->deleteActualite($id)) {
            $_SESSION['success_message'] = "Actualité supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
    } else {
        $_SESSION['error'] = "Erreur de sécurité (CSRF).";
    }
    header('Location: dashboardAdmin.php?tab=actualite');
    exit;
}

// Récupération des actualités
$actualites = $actualiteManager->getAllActualites();
?>

<div class="container mt-5">
    <h2 class="h2Sports text-center mb-4">Actualités CACDS</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Date de publication</th>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($actualites)): ?>
                    <?php foreach ($actualites as $actu): ?>
                        <tr>
                            <td><?= ($actu['date_publication']) ?></td>
                            <td><?= ($actu['titre']) ?></td>
                            <td>
                                <?= (mb_strlen($actu['contenu']) > 100 
                                    ? mb_substr($actu['contenu'], 0, 100) . '...' 
                                    : $actu['contenu']) ?>
                            </td>
                            <td class="center">
                                <button type="button" class="btn btn-second bold btn-sm mb-2 me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#actualiteModal<?= $actu['id'] ?>">
                                    Lire
                                </button>
                                <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');">
                                    <?php addCSRFTokenToForm(); ?>
                                    <input type="hidden" name="actualite_id" value="<?= (int)$actu['id'] ?>">
                                    <button type="submit" name="delete_actualite" class="btn btn-original bold btn-sm mb-2">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucune actualité</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="container mt-4 mb-3">
            <h2 class="h2Sports text-center mb-4">Ajouter une actualité</h2>

            <form action="?tab=actualite" method="post">
                <div class="d-flex justify-content-center gap-3 flex-wrap">

                    <div class="mb-3 d-flex flex-column w-50">
                        <label for="titre">Titre :</label>
                        <input type="text" name="titre" id="titre" class="form-control" required>
                    </div>

                    <div class="mb-3 d-flex flex-column w-50">
                        <label for="contenu">Contenu :</label>
                        <textarea name="contenu" id="contenu" rows="5" class="form-control" required></textarea>
                    </div>

                </div>

                <?php addCSRFTokenToForm(); ?>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-original bold mt-3">Ajouter l'actualité</button>
                </div>
            </form>
        </div>


        <!-- Modales pour afficher les actualités complètes -->
        <?php foreach ($actualites as $actu): ?>
            <div class="modal fade" id="actualiteModal<?= $actu['id'] ?>" tabindex="-1" aria-labelledby="actualiteModalLabel<?= $actu['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="actualiteModalLabel<?= $actu['id'] ?>">
                                <?= htmlspecialchars($actu['titre']) ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Date de publication :</strong> <?= htmlspecialchars($actu['date_publication']) ?></p>
                            <p><strong>Contenu :</strong></p>
                            <p><?= nl2br(htmlspecialchars($actu['contenu'])) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-original bold" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
