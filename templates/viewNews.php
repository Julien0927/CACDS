    <!-- Section Actualités -->
    <section class="container-fluid">
        <h2 class="h2Sports">Actualités</h2>
        <hr>
        <p class="lecture">Retrouvez ici l'actualité de la section Badminton.</p>
        <div class="row d-flex flex-wrap">
            <?php if (!empty($newsPageActuelle)): ?>
                <?php foreach ($newsPageActuelle as $new): ?>
                    <?php include 'templates/partial_news.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #EC930F">Aucune actualité disponible pour le moment.</p>
            <?php endif; ?>
        </div>        
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Navigation des articles">
                <ul class="pagination justify-content-center">
                    <?php if ($pageActuelle > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pageNews=<?= $pageActuelle - 1 ?>" aria-label="Précédent">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $pageActuelle == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?pageNews=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($pageActuelle < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pageNews=<?= $pageActuelle + 1 ?>" aria-label="Suivant">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </section>

