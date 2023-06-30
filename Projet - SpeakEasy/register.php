<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/stylesindex.css">
    <title>Document</title>
</head>

<body>
    <a class="return" href="index.php">Retour</a>
    <form action="/register-POST.php" method="post">
        <div class="container">
            <a href="index.php"><img class="logo" src="assets/images/SpeakEasy.png" alt="Logo SpeakEasy"></a>
            <input type="text" name="nom" placeholder="Nom"
                value="<?php echo isset($_SESSION['values']['nom']) ? $_SESSION['values']['nom'] : ''; ?>">
            <?php if (isset($_SESSION['errors']['nom']))
                echo '<span class="error">' . $_SESSION['errors']['nom'] . '</span>'; ?>
            <input type="text" name="prenom" placeholder="Prenom"
                value="<?php echo isset($_SESSION['values']['prenom']) ? $_SESSION['values']['prenom'] : ''; ?>">
            <?php if (isset($_SESSION['errors']['prenom']))
                echo '<span class="error">' . $_SESSION['errors']['prenom'] . '</span>'; ?>
            <input type="email" name="email" placeholder="Email"
                value="<?php echo isset($_SESSION['values']['email']) ? $_SESSION['values']['email'] : ''; ?>">
            <?php if (isset($_SESSION['errors']['email']))
                echo '<span class="error">' . $_SESSION['errors']['email'] . '</span>'; ?>
            <input type="password" name="mdp" placeholder="Mot de passe">
            <?php if (isset($_SESSION['errors']['mdp']))
                echo '<span class="error">' . $_SESSION['errors']['mdp'] . '</span>'; ?>
            <input type="password" name="confirm_mdp" placeholder="Confirmer votre Mdp">
            <?php if (isset($_SESSION['errors']['confirm_mdp']))
                echo '<span class="error">' . $_SESSION['errors']['confirm_mdp'] . '</span>'; ?>
            <input type="submit" name="submit" id="boutton" value="Valider">
        </div>
    </form>
    <footer class="footer">
        <div>
            <p>&copy; 2023 SpeakEasy. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>
