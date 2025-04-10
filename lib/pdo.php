<?php
require_once __DIR__ . '/../vendor/autoload.php';

$envFile = $_SERVER['SERVER_NAME'] === 'localhost' 
    ? __DIR__ . '/../.env'  
    : '/home/julienvarachas/www/.env';  

if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname($envFile));
    $dotenv->load();
} else {
    // Configuration manuelle pour production
    $_ENV['PROD_DB_HOST'] = 'mysql-cacds.alwaysdata.net';
    $_ENV['PROD_DB_NAME'] = 'julienvarachas_cacds1';
    $_ENV['PROD_DB_USER'] = '327887';
    $_ENV['PROD_DB_PASS'] = 'T0mEmm@1114';
    $_ENV['PROD_DB_PORT'] = '3306';
    $_ENV['PROD_DB_CHARSET'] = 'utf8';
}

$env = ($_SERVER['SERVER_NAME'] === 'localhost') ? 'LOCAL' : 'PROD';

try {
    $config = [
        'host' => $_ENV[$env . '_DB_HOST'] ?? '',
        'name' => $_ENV[$env . '_DB_NAME'] ?? '',
        'user' => $_ENV[$env . '_DB_USER'] ?? '',
        'pass' => $_ENV[$env . '_DB_PASS'] ?? '',
        'port' => $_ENV[$env . '_DB_PORT'] ?? 3306,
        'charset' => $_ENV[$env . '_DB_CHARSET'] ?? 'utf8mb4'
    ];

    $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']};port={$config['port']}";
    $db = new PDO($dsn, $config['user'], $config['pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    error_log("Erreur de connexion : " . $e->getMessage());
    die("Une erreur s'est produite lors de la connexion à la base de données.");
}
