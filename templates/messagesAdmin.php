<div class="container mt-4">
    <h2 class="h2Sports text-center mb-4">Messages reçus</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= htmlspecialchars($message['created_at']) ?></td>
                            <td><?= htmlspecialchars($message['name']) ?></td>
                            <td><?= htmlspecialchars($message['firstname']) ?></td>
                            <td><?= htmlspecialchars($message['email']) ?></td>
                            <td><?= htmlspecialchars(mb_strlen($message['content']) > 100 ? 
                                mb_substr($message['content'], 0, 100) . '...' : 
                                $message['content']) ?></td>
                            <td class="center">
                                <button type="button" class="btn btn-second bold btn-sm mb-2 me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#messageModal<?= $message['id'] ?>">
                                    Lire
                                </button>
                                <!-- Formulaire de suppression existant -->
                                <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                    <?php addCSRFTokenToForm(); ?>
                                    <input type="hidden" name="message_id" value="<?= (int)$message['id'] ?>">
                                    <button type="submit" name="delete_message" class="btn btn-original bold btn-sm mb-2">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun message</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Modales pour chaque message -->
        <?php foreach ($messages as $message): ?>
            <div class="modal fade" id="messageModal<?= $message['id'] ?>" tabindex="-1" aria-labelledby="messageModalLabel<?= $message['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="messageModalLabel<?= $message['id'] ?>">
                                Message de <?= htmlspecialchars($message['firstname']) ?> <?= htmlspecialchars($message['name']) ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Date :</strong> <?= htmlspecialchars($message['created_at']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($message['email']) ?></p>
                            <p><strong>Message :</strong></p>
                            <p><?= nl2br(htmlspecialchars($message['content'])) ?></p>
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
