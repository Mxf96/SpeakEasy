<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dsn = 'mysql:dbname=speakeasy;host=127.0.0.1;charset=utf8mb4';
$user = 'speakeasy';
$password = 'la-rangedu@fond';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $serveurs = [];

    if (isset($_SESSION['user']) && isset($_SESSION['user']['id_utilisateur'])) {
        $userId = $_SESSION['user']['id_utilisateur'];
        $stmt = $dbh->prepare("SELECT serveurs.* FROM serveurs JOIN associer ON serveurs.id_serveur = associer.id_serveur WHERE associer.id_utilisateur = :userId");
        $stmt->execute(['userId' => $userId]);

        $serveurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    error_log('Connection failed: ' . $e->getMessage());
    echo 'Une erreur s\'est produite lors de la connexion à la base de données. Veuillez réessayer plus tard.';
}
?>
