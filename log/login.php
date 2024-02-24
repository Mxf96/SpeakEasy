<?php 
$title = "Connexion";
require '../includes/inc-top-form.php';

?>
    <body>
        <div class="form-container">
            <a href="/index.php" class="back-link">Retour</a>
            <h2>Connexion</h2>
            <?php if (isset($_SESSION['success_message'])) : ?>
                <div class="alert success">
                    <?= $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            <form action="/log/login-POST.php" method="post">
                <div>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email">
                </div>
                <div>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <button type="submit">Se connecter</button>
                </div>
                <a href="/log/register.php">Tu n'as pas de compte, inscris-toi</a>
            </form>
        </div>
    </body>
</html>