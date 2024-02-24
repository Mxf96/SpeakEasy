<?php
require 'inc-db-connect.php';
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

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/assets/css/styles.css">
        <link rel="icon" href="/assets/pictures/SpeakEasy.png" type="image/x-icon">
        <title>Profil de <?= sanitize_input($userName); ?></title>
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
                <a href="/profile/profile.php">Profile</a>
                <a href="/log/logout.php">Déconnexion</a>
            </div>
        </header>
        