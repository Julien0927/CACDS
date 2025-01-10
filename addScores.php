<?php
ob_start(); // Démarre le tampon de sortie
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/tools.php';
require_once 'lib/security.php';
require_once 'App/Results.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mapping des IDs de compétition
$competitionMapping = [
    'Champ' => 1,
    'Cup' => 2,
    'Tourn' => 3
];

// Fonction de traitement des erreurs
function handleError($message) {
    $_SESSION['error'] = $message;
    header('Location: addScores.php');
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

        if (!$competitionId || !$pouleId || !$dayNumber) {
            handleError("Tous les champs sont requis");
        }

        // Vérification du fichier
        if (!isset($_FILES['result_pdf_url']) || $_FILES['result_pdf_url']['error'] !== UPLOAD_ERR_OK) {
            handleError("Erreur lors du téléchargement du fichier");
        }

        $file = $_FILES['result_pdf_url'];
        
        // Validation du type de fichier
        if ($file['type'] !== 'application/pdf') {
            handleError("Seuls les fichiers PDF sont acceptés");
        }

        // Validation de la taille du fichier (5MB max)
        if ($file['size'] > 5000000) {
            handleError("Le fichier est trop volumineux (maximum 5MB)");
        }

        // Préparation du chemin de destination
        $uploadDir = 'uploads/results/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Génération d'un nom de fichier unique
        $fileName = uniqid('result_') . '_' . basename($file['name']);
        $destPath = $uploadDir . $fileName;

        // Déplacement du fichier
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            handleError("Erreur lors du déplacement du fichier");
        }

        // Ajout du résultat dans la base de données
        $results = new App\Results\Results($db, $competitionId, $pouleId);
        $results->addResult($dayNumber, $destPath);
        $_SESSION['success'] = "Le résultat a été ajouté avec succès";
        header('Location: dashboardBad.php'); 
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

    <h3>Ajouter un résultat</h3>
    <form method="POST" action="addScores.php" enctype="multipart/form-data" class="d-flex flex-row align-items-center justify-content-start flex-wrap">
        <!-- Choix de la compétition -->
        <div class="me-3 mt-4">
            <select name="results" id="results" class="form-select" required>
                <option value="">Choix de la compétition</option>
                <?php 
                try {
                    $results = new App\Results\Results($db);
                    $competitions = $results->getCompetitions();
                    foreach($competitions as $competition) {
                        $displayText = $competition['type'] ;
                        echo '<option value="' . htmlspecialchars($competition['id']) . '">' 
                        . htmlspecialchars($displayText) . '</option>';
                    }
                } catch (Exception $e) {
                    echo '<option value="">Erreur de chargement</option>';
                }
                ?>
            </select>
        </div>
        
        <!-- Choix de la poule -->
        <div id="poules-results-container" style="display: none;" class="me-3 mb-2">
            <label for="poulesResults" class="form-label me-2">Choisir une poule</label>
            <select name="poulesResults" id="poulesResults" class="form-select" required>
                <!-- Les options seront générées en JS -->
            </select>
        </div>
        
        <!-- Numéro de journée -->
        <div id="dayNumber-container" style="display: none;" class="me-3 mb-2">
            <label for="dayNumber" class="form-label me-2">Numéro de la journée</label>
            <input type="number" name="dayNumber" id="dayNumber" min="1" class="form-control" required>
        </div>

        <!-- Upload de fichier -->
        <div class="mt-4">
            <input type="file" class="form-control" name="result_pdf_url" id="result_pdf_url" accept="application/pdf" required>
            <small class="form-text text-muted">Taille maximum : 5MB. Format accepté : PDF uniquement.</small>
        </div>

        <!-- Bouton de soumission -->
        <div class="d-flex justify-content-center ms-3 mt-4">
            <?php addCSRFTokenToForm() ?>
            <button type="submit" class="btn btn-secondary justify-content-center my-3">Enregistrer</button>
        </div>
    </form>
</section>

<?php require_once 'templates/footer.php'; ?>