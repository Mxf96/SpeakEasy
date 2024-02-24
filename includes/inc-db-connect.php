<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Construction du DSN
$dsn = 'mysql:dbname=' . $_ENV['DB_NAME'] . ';host=' . $_ENV['DB_HOST'] . ';charset=' . $_ENV['DB_CHARSET'];

// Récupération des informations d'authentification depuis les variables d'environnement
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    // Création de l'objet PDO pour la connexion à la base de données
    $dbh = new PDO($dsn, $user, $password);
    // Configuration des attributs PDO pour gérer les erreurs
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gestion de l'erreur de connexion
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    exit;
}