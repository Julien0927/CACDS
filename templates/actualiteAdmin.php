<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['contenu'])) {
    if (verifyCSRFToken()) {
        $titre = trim($_POST['titre']);
        $contenu = trim($_POST['contenu']);
        $documentPath = null;

        if (!empty($titre) && !empty($contenu)) {
            // Gérer l'upload
            if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                $documentPath = $actualiteManager->uploadDocument($_FILES['document']);
            }

            if ($actualiteManager->addActualite($titre, $contenu, $documentPath)) {
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
                    <th>Document</th>
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
                            <td class="text-center">
                                <?php if (!empty($actu['document_path'])): ?>
                                    <a href="<?= htmlspecialchars($actu['document_path']) ?>" class="d-flex justify-content-center mt-2" target="_blank" style="text-decoration: none; color: black">
                                        Voir le document
                                    </a>
                                    <?php else: ?>
                                        —
                                <?php endif; ?>
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
       
            <form action="?tab=actualite" method="post" enctype="multipart/form-data">
                <div class="d-flex justify-content-center gap-3 flex-wrap">

                    <div class="mb-3 d-flex flex-column w-50">
                        <label for="titre">Titre :</label>
                        <input type="text" name="titre" id="titre" class="form-control" required>
                    </div>

                    <div class="mb-3 d-flex flex-column w-50">
                        <label for="contenu">Contenu :</label>
                        <textarea name="contenu" id="contenu" rows="5" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3 d-flex flex-column w-50">
                        <label for="document">Document (PDF/image) :</label>
                        <input type="file" name="document" id="document" class="form-control">
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
                            <h5 class="h5Sports modal-title" id="actualiteModalLabel<?= $actu['id'] ?>">
                                <?= ($actu['titre']) ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <p class="lecture"><?=($actu['date_publication']) ?></p>
                            <?php if (!empty($actu['document_path'])): ?>
                                <?php 
                                    $extension = strtolower(pathinfo($actu['document_path'], PATHINFO_EXTENSION)); 
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                ?>
                                <?php if ($isImage): ?>
                                    <div class="d-flex justify-content-center mb-3">
                                        <img src="<?= ($actu['document_path']) ?>" 
                                        alt="Image associée" 
                                        class="img-fluid rounded shadow mb-3" 
                                        style="max-height: 500px; object-fit: contain;">
                                    </div>
                                <?php else: ?>
                                    <a href="<?= ($actu['document_path']) ?>" target="_blank" class="d-block text-center mt-3">
                                        <img src="/assets/icones/pdf-250.png" alt="PDF" class="img-fluid mb-2" style="width: 50px; height: auto;">
                                        
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <p class="lecture"><?= nl2br(($actu['contenu'])) ?></p>
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
