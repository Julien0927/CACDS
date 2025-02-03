<div class="user-status">
    <?php
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        echo '<p>Vous êtes connecté en tant que ' . htmlspecialchars($_SESSION['user']['firstname']) . ' ' . htmlspecialchars($_SESSION['user']['name']) . '</p>';
    }
    ?>
</div>