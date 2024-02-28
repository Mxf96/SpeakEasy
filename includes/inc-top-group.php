<?php
require_once 'inc-db-connect.php';
require_once '../managers/profile-manager.php';
require_once '../managers/group-manager.php';
require_once '../managers/security-manager.php';

// Redirige vers la page de connexion si aucun utilisateur n'est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php'); 
    exit;
}

// Récupère l'ID du groupe depuis la requête GET
$groupID = isset($_GET['groupID']) ? $_GET['groupID'] : null;

// Récupère l'ID de l'utilisateur depuis la session
$userID = $_SESSION['user_id'];

// Utilise l'ID pour récupérer le nom de l'utilisateur
$userName = getUserName($dbh, $userID);

// Si un ID de groupe est fourni, récupère les informations du groupe
if ($groupID) {
    $groupInfo = getGroupInfo($dbh, $groupID);
    $groupName = $groupInfo ? $groupInfo['name'] : "Groupe inconnu";
} else {
    $groupName = "Groupe inconnu";
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/assets/css/styles.css">
        <link rel="stylesheet" href="/assets/css/groupPage.css">
        <link rel="icon" href="/assets/pictures/SpeakEasy.png" type="image/x-icon">
        <!-- Modifiez le titre ici pour inclure le nom du groupe -->
        <title><?= sanitize_input($groupName); ?> - SpeakEasy</title>
    </head>
    <body>
    <header class="navbar">
            <div class="logo">
                <img src="/assets/pictures/SpeakEasy.png" alt="Logo">
                <h1>SpeakEasy</h1>
            </div>
            <div class="navbar-right">
                <a href="/home/home.php">Home</a>
                <a href="/friends/friends.php">Amis</a>
                <a href="/groups/groups.php">Groupes</a>
                <a href="/profile/profile.php?userID=<?= $_SESSION['user_id']; ?>">Profile</a>
                <a href="/log/logout.php">Déconnexion</a>
            </div>
        </header>