<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'App/Users.php'; // Assurez-vous que la classe Users est bien incluse
require_once 'lib/security.php';

// Initialisation des variables
$message = "";
$errors = [];

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $data = [
        'name' => $_POST['name'],
        'firstname' => $_POST['firstname'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'role' => 'user', 
        'sport_id' => null, 
        'poule_id' => null 
    ];

    // Si l'utilisateur n'est pas un super_admin, on définit son sport_id
    if ($data['role'] !== 'super_admin') {
        $data['sport_id'] = $_POST['sports'];
        $data['poule_id'] = isset($_POST['poules']) ? $_POST['poules'] : null;
    }

    // Créer une instance de la classe Users
    $users = new App\Users\Users($db); // $db est la connexion PDO

   
        // Vérification des erreurs avant d'ajouter l'utilisateur
    if (empty($data['name']) || empty($data['firstname']) || empty($data['email']) || empty($data['password'])) {
        $errors[] = "Tous les champs sont requis.";
    }

     // Vérification de la longueur du mot de passe
     if (strlen($data['password']) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    // Si aucun problème, procéder à la création de l'utilisateur
    if (empty($erreurs)) {
        if ($users->createUser($data)) {
            $message = "Votre inscription a été réussie.";
        } else {
            $errors[] = "Une erreur est survenue.";
        }
    }
}

?>

<!-- Affichage des messages -->
<?php if ($message): ?>
    <div class="alert success mx-auto" style="width: 50%;"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (!empty($erreurs)): ?>
    <div class="alert error mx-auto" style="width: 50%;">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h1 class="center mt-3">Inscription</h1>
<form class="ms-2" method="POST" action="register.php">
    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" name="name" class="form-control inputRegister" id="name" placeholder="Nom" required>
    </div>
    <div class="mb-3">
        <label for="firstname" class="form-label">Prénom</label>
        <input type="text" name="firstname" class="form-control inputRegister" id="firstname" placeholder="Prénom" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control inputRegister" id="email" placeholder="Email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control inputRegister" id="password" placeholder="8 caractères minimum" minlength="8" required>
    </div>
    <div class="mb-3">
        <label for="sports" class="form-label me-2">Choisir un sport</label>
        <select class="font-lato" name="sports" id="sports" style="border-radius: 5px;">
            <option class="font-lato" value="1">Tennis de table</option>
            <option class="font-lato" value="2">Badminton</option>
            <option class="font-lato" value="4">Volley-Ball</option>
            <option class="font-lato" value="3">Pétanque</option>
        </select>
    </div>
    <div class="mb-3">
        <div id="poules-container" style="display: none;">
            <label for="poules" class="form-label me-2">Choisir une poule</label>
            <select name="poules" id="poules" style="border-radius: 5px;">
                <!-- Options générées dynamiquement -->
            </select>
        </div>
    </div>
    <?php addCSRFTokenToForm() ?>
    <button class="btn btn-original bold" type="submit">Soumettre</button>
</form>

<?php
require_once 'templates/footer.php';
?>