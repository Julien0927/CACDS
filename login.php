<?php
require_once('lib/pdo.php');
require_once('App/Users.php');

use App\Users\Users;

session_start();
require_once('lib/security.php');

$messages = [];
$errors = [];

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

if (!empty($_POST)) {
    if (isset($_POST["email"], $_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        try {
            $user = new Users($db);

            $userInfo = $user->login($_POST["email"], $_POST["password"]);

            if ($userInfo) {
                // Stocker les informations utilisateur dans la session
                $_SESSION['user'] = $userInfo; // Par exemple : ['email' => '...', 'sport' => 'badminton']
                $_SESSION['success_message'] = "Bienvenue sur votre tableau de bord !";

                // Rediriger en fonction du sport ou rôle
                switch ($userInfo['sport_id']) {
                    case '2':
                        header("Location: dashboardBad.php");
                        break;
                    case '4':
                        header("Location: dashboardVolley.php");
                        break;
                    case '1':
                        header("Location: dashboardTdT.php");
                        break;
                    case '3':
                        header("Location: dashboardPetanque.php");
                        break;
                    default:
                        header("Location: index.php"); // Redirection par défaut
                        break;
                }
                exit;
            } else {
                $errors[] = "Vos identifiants ne sont pas valides";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

require_once('header.php');
?>
<h1 class="center mt-3">Connexion</h1>
<?php if ($messages): ?>
    <div class="alert success mx-auto" style="width: 50%;"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert error mx-auto" style="width: 50%;">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="">
    <form method="POST" action="login.php" class="ms-2">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control inputRegister" name="email" id="email" placeholder="Votre email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control inputRegister" name="password" id="password" placeholder="Votre mot de passe" required>
        </div>
        <div class="mb-3">
            <?php addCSRFTokenToForm() ?>
            <button type="submit" class="btn btn1 bold" value="Se connecter">Se connecter</button>
        </div>
    </form>
</div>

<?php
require_once('templates/footer.php');
?>
