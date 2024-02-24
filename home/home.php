<?php
require_once '../managers/profile-manager.php'; 
require_once '../managers/security-manager.php';

// Redirige vers la page de connexion si aucun utilisateur n'est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php'); 
    exit;
}

// Récupère l'ID de l'utilisateur depuis la session
$userID = $_SESSION['user_id'];
// Utilise l'ID pour récupérer le nom de l'utilisateur
$userName = getUserName($dbh, $userID);

require '../includes/inc-top-home.php';
?>
<main>
    <p>Bienvenue, <?= sanitize_input($userName); ?>!</p>
</main>

<?php 
require '../includes/inc-bottom.php';
?>