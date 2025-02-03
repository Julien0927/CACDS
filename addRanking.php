<?php
ob_start();
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'templates/messages.php';
require_once 'lib/pdo.php';
require_once 'lib/tools.php';
require_once 'lib/security.php';
require_once 'App/Classements.php';

// Mapping des IDs de compétition
$competitionMapping = [
    'Championnat' => 1,
    'Coupe' => 2,
    'Tournoi' => 3
];

// Fonction de traitement des erreurs
function handleError($message) {
    $_SESSION['error'] = $message;
    header('Location: addRanking.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Vérification du CSRF token
        verifyCSRFToken();
        
        // Validation des données POST
        $competitionId = $competitionMapping[$_POST['results']] ?? null;
        $pouleId = $_POST['poulesResults'] ?? null;
        $dayNumber = $_POST['dayNumber'] ?? null;
        $name = $_POST['name'] ?? null;

        // Les coupes et les tournois n'ont pas besoin de poule ou de journée
        if (in_array($_POST['results'], ['Coupe', 'Tournoi'])) {
            $pouleId = null;
            $dayNumber = null;
        }

        if (!$competitionId && !$pouleId && !$dayNumber) {
            handleError("Tous les champs sont requis");
        }

        // Vérification du fichier
        if (!isset($_FILES['classement_pdf_url']) || $_FILES['classement_pdf_url']['error'] !== UPLOAD_ERR_OK) {
            handleError("Erreur lors du téléchargement du fichier");
        }

        $file = $_FILES['classement_pdf_url'];
        
        // Validation du type de fichier
        if ($file['type'] !== 'application/pdf') {
            handleError("Seuls les fichiers PDF sont acceptés");
        }

        // Validation de la taille du fichier (5MB max)
        if ($file['size'] > 5000000) {
            handleError("Le fichier est trop volumineux (maximum 5MB)");
        }

        // Préparation du chemin de destination
        $uploadDir = 'uploads/classements/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Génération d'un nom de fichier unique
        $fileName = uniqid('classement_') . '_' . basename($file['name']);
        $destPath = $uploadDir . $fileName;

        // Déplacement du fichier
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            handleError("Erreur lors du déplacement du fichier");
        }

        // Ajout du résultat dans la base de données
        $classements = new App\Classements\Classements($db, $competitionId, $pouleId);
        $classements->addClassement($destPath, $dayNumber, $name);

        $_SESSION['messages'] = ["Le classement a été ajouté avec succès"];
        header('Location: addRanking.php');
        exit();

    } catch (Exception $e) {
        handleError($e->getMessage());
    }
}

// Affichage des messages d'erreur/succès
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
ob_end_flush();
?>

<section class="ms-2">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <h3 class="h2Sports mt-3 text-center">Ajouter un classement</h3>
    <form method="POST" action="addRanking.php" enctype="multipart/form-data" >
        <!-- Choix de la compétition -->
        <div class="d-flex flex-row align-items-center justify-content-center flex-wrap">
            <div class="me-3 mt-4">
                <select name="results" id="results" class="form-select" required>
                    <option value="">Choix de la compétition</option>
                    <option value="Championnat">Championnat</option>
                    <option value="Coupe">Coupe</option>
                    <option value="Tournoi">Tournoi</option>
                </select>
            </div>
            
            <!-- Choix de la poule -->
            <div id="poules-results-container" style="display: none;" class="me-3 mb-2">
                <label for="poulesResults" class="form-label me-2">Choisir une poule</label>
                <select name="poulesResults" id="poulesResults" class="form-select">
                    <!-- Les options seront générées en JS -->
                </select>
            </div>

            <!-- Nom de la compétition -->
            <div id="competitionNameContainer" style="display: none;" class="me-3 mb-2">
                <label for="name" class="form-label me-2">Nom de la compétition :</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Entrez le nom de la compétition">
            </div>

            <!-- Numéro de journée -->
            <div id="dayNumber-container" style="display: none;" class="me-3 mb-2">
                <label for="dayNumber" class="form-label me-2">Numéro de la journée</label>
                <input type="number" name="dayNumber" id="dayNumber" class="form-control">
            </div>

            <!-- Upload de fichier -->
            <div class="mt-5">
                <input type="file" class="form-control" name="classement_pdf_url" id="classement_pdf_url" accept="application/pdf" required>
                <small class="form-text text-muted">Taille maximum : 5MB. Format accepté : PDF uniquement.</small>
            </div>
        </div>
        <!-- Bouton de soumission -->
        <div class="d-flex justify-content-center mt-2">
            <?php addCSRFTokenToForm() ?>
            <button type="submit" class="btn btn-original bold my-3">Enregistrer</button>
        </div>
    </form>
</section>

<?php require_once 'templates/footer.php'; ?>