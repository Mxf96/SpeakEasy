<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpeakEasy</title>
    <link rel="stylesheet" href="assets/css/stylesmenu.css">
    <script src="/logoutUser.js"></script>
</head>

<body>
    <div class="center">
        <img src='/assets/images/SpeakEasy.png' class="logo" alt='Logo SpeakEasy'/>
    </div>
    <a class="logout" href="/logout.php">Déconnexion</a>
    <h2>Bonjour, <?= $_SESSION['user']['prenom_utilisateur'] ?> <?= $_SESSION['user']['nom_utilisateur'] ?></h2>
    <div class="container">
        <div class="button-wrapper">
            <a class="button" href="/messerveurs.php">Mes Serveurs</a>
            <a class="button" href="/mesamis.php">Mes Ami(e)s</a>
        </div>
    </div>
    <footer>
        <div class="footer">
            <p>&copy; 2023 SpeakEasy. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>