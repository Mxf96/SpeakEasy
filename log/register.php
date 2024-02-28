<?php 
require_once '../includes/inc-db-connect.php';
$title = "S'inscrire";
require_once '../includes/inc-top-form.php'; 

// Clear form data if there's no error message
if (!isset($_SESSION['error_message'])) {
    unset($_SESSION['form_data']);
}
?>
<body>
    <div class="form-container">
        <a href="/log/login.php" class="back-link">Retour</a>
        <h2>Inscription</h2>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert error">
                <?= $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <form action="/log/register-POST.php" method="post">
            <div>
                <label for="name">Pseudo d'utilisateur :</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? '') ?>">
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
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
