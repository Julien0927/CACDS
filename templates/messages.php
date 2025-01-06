<!-- templates/messages.php -->
<?php

$messages = [];
$errors = [];

$sessionMessages = $_SESSION['messages'] ?? [];
$sessionErrors = $_SESSION['errors'] ?? [];

$allMessages = is_array($messages) ? array_merge($messages, $sessionMessages) : $sessionMessages;
$allErrors = is_array($errors) ? array_merge($errors, $sessionErrors) : $sessionErrors;

unset($_SESSION['messages'], $_SESSION['errors']); // Effacer les messages de la session
?>

<?php foreach ($allMessages as $message): ?>
    <?php if (is_array($message)): ?>
        <?php foreach ($message as $msg): ?>
            <div class="alert success connexion mx-auto"><?= htmlspecialchars($msg) ?></div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert success connexion mx-auto"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
<?php endforeach; ?>

<?php foreach ($allErrors as $error): ?>
    <?php if (is_array($error)): ?>
        <?php foreach ($error as $err): ?>
            <div class="alert error mx-auto"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert error mx-auto"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
<?php endforeach; ?>
