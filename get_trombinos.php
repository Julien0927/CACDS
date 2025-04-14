<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'lib/pdo.php';
require_once 'App/Trombinoscope.php';

use App\Trombinoscope\Trombinoscope;

header('Content-Type: application/json');

try {
    if (!isset($_GET['poule']) || empty($_GET['poule'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Paramètre "poule" manquant']);
        exit;
    }

    $poule = (int)$_GET['poule'];

    $trombi = new Trombinoscope($db);
    $documents = $trombi->getAllTrombinos($poule);

    echo json_encode(['success' => true, 'data' => $documents]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
