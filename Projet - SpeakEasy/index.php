<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/stylesindex.css">
    <title>Login - SpeakEasy</title>
</head>

<body>
    <a class="register" id="register" href="register.php">S'inscrire</a>
    <form action="login-POST.php" method="post">
        <div class="container">
            <a href="index.php"><img class="logo" src="assets/images/SpeakEasy.png" alt="Logo SpeakEasy"></a>
            <?php
            if (isset($_SESSION['errors']) && isset($_SESSION['errors']['email'])) {
                echo '<p class="error-message">' . $_SESSION['errors']['email'] . '</p>';
                unset($_SESSION['errors']['email']);
            }
            ?>
            <input type="email" name="email" placeholder="Email">
            <?php
            if (isset($_SESSION['errors']) && isset($_SESSION['errors']['password'])) {
                echo '<p class="error-message">' . $_SESSION['errors']['password'] . '</p>';
                unset($_SESSION['errors']['password']);
            }
            ?>
            <input type="password" name="password" placeholder="Mot de passe">
            <a class="mdpo" id="mdpo" href="/forgot-password.php">Mot de passe oublié</a>
            <input type="submit" name="submit" id="boutton" value="Se Connecter">
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
    </form>
    <footer class="footer">
        <div>
            <p>&copy; 2023 SpeakEasy. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="assets/js/mainindex.js"></script>
</body>

</html>
