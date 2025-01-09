<?php
require_once 'header.php';
require_once 'templates/nav.php';
require_once 'lib/pdo.php';
require_once 'lib/tools.php';
require_once 'lib/security.php';

require_once 'App/Results.php';

// Récupérer les données du formulaire
$competitionId = $_POST['results'] ?? null; // Récupérer l'ID de la compétition depuis POST
$pouleId = $_POST['poulesResults'] ?? null;
$dayNumber = $_POST['dayNumber'] ?? null;

// Vérifier si un fichier PDF a été téléchargé
if (isset($_FILES['result_pdf_url']) && $_FILES['result_pdf_url']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['result_pdf_url']['tmp_name'];
    $fileName = $_FILES['result_pdf_url']['name'];
    $fileType = $_FILES['result_pdf_url']['type'];

    // Définir le répertoire de destination pour le fichier
    $uploadDir = 'uploads/results/';
    // Créer le dossier si nécessaire
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destPath = $uploadDir . basename($fileName);

    // Déplacer le fichier dans le répertoire de destination
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Créer une instance de la classe Results pour ajouter le résultat
        $results = new App\Results\Results($db, $competitionId, $pouleId);
        $results->addResult($dayNumber, $destPath);

        echo "Le résultat a été ajouté avec succès.";
    } else {
        echo "Erreur lors du téléchargement du fichier.";
    }
} else {
    echo "Veuillez sélectionner un fichier valide." ;
}


?>

<section class="ms-2">
    <h3>Ajouter un résultat</h3>
    <form method="POST" action="addScores.php" enctype="multipart/form-data" class="d-flex flex-row align-items-center justify-content-start flex-wrap">
        <!-- Choix de la compétition -->
        <div class="me-3 mt-4">
           <select name="results" id="results" class="form-select">
                <option value="">Choix de la compétition</option>
                <option value="Champ">Championnat</option>
                <option value="Cup">Coupe</option>
                <option value="Tourn">Tournoi</option>
            </select>
        </div>

        <!-- Choix de la poule et numéro de journée -->
        <div id="poules-results-container" style="display: none;" class="me-3 mb-2">
            <label for="poulesResults" class="form-label me-2">Choisir une poule</label>
            <select name="poulesResults" id="poulesResults" class="form-select" style="border-radius: 5px;">
                <!-- Les options seront générées en JS -->
            </select>
        </div>
        
        <div id="dayNumber-container" style="display: none;" class="me-3 mb-2">
            <label for="dayNumber" class="form-label me-2">Numéro de la journée</label>
            <input type="number" name="dayNumber" id="dayNumber" min="1" class="form-control" required>
        </div>
        <div class="mt-4">
            <input type="file" class="form-control" name="result_pdf_url" id="result_pdf_url">
        </div>
        <!-- Bouton de soumission -->
        <div class="d-flex justify-content-center ms-3 mt-4">
            <?php addCSRFTokenToForm() ?>
            <button type="submit" class="btn btn-secondary justify-content-center my-3" value="">Enregistrer</button>
        </div>
    </form>
</section>

<?php

require_once 'templates/footer.php';
