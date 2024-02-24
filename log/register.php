<?php 
$title = "S'inscrire";
require '../includes/inc-top-form.php'; 
?>
    <body>
        <div class="form-container">
            <a href="/log/login.php" class="back-link">Retour</a>
            <h2>Inscription</h2>
            <?php if (isset($_SESSION['error_message'])) : ?>
                <div class="alert error">
                    <?= $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            <form action="/log/register-POST.php" method="post">
                <div>
                    <label for="name">Pseudo d'utilisateur :</label>
                    <input type="text" id="name" name="name">
                </div>
                <div>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email">
                </div>
                <div>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <div>
                    <button type="submit">S'inscrire</button>
                </div>
            </form>
        </div>
    </body>        
</html>