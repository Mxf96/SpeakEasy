<?php
require 'inc-db-connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/assets/css/styles.css">
        <link rel="stylesheet" href="/assets/css/messages.css">
        <link rel="icon" href="/assets/pictures/SpeakEasy.png" type="image/x-icon">
        <title>SpeakEasy</title>
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
                <a href="/log/logout.php">Déconnexion</a>
            </div>
        </header>