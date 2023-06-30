<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../../includes/inc-db-connect.php';
include '../managers/user-manager.php';

// Fonction pour vérifier si le token de réinitialisation est valide
function getUserByResetToken($dbh, $token)
{
    $stmt = $dbh->prepare("SELECT * FROM users WHERE reset_token = :token");
    $stmt->bindValue(':token', $token);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour vérifier si le token de réinitialisation a expiré
function isResetTokenExpired($createdAt)
{
    $expirationTime = 3600; // Temps d'expiration en secondes (1 heure dans cet exemple)
    $resetTime = strtotime($createdAt);
    return (time() - $resetTime) > $expirationTime;
}

// Fonction pour mettre à jour le mot de passe dans la base de données
function updatePassword($dbh, $userId, $hashedPassword)
{
    $stmt = $dbh->prepare("UPDATE users SET password = :password WHERE id = :userId");
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
}

// Fonction pour supprimer le token de réinitialisation de la base de données
function deleteResetToken($dbh, $userId)
{
    $stmt = $dbh->prepare("UPDATE users SET reset_token = NULL, reset_token_created_at = NULL WHERE id = :userId");
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_GET['token'];

    // Vérifier si le token de réinitialisation est valide
    $user = getUserByResetToken($dbh, $token);

    if (!$user || isResetTokenExpired($user['reset_token_created_at'])) {
        // Le token est invalide ou a expiré
        header("Location: reset-token-invalid.php");
        exit;
    }

    $password = $_POST['password'];

    // Hacher et mettre à jour le mot de passe dans la base de données
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    updatePassword($dbh, $user['id'], $hashedPassword);

    // Supprimer le token de réinitialisation de la base de données
    deleteResetToken($dbh, $user['id']);

    // Rediriger vers une page de confirmation
    header("Location: password-reset-confirmation.php");
    exit;
}

require '../../includes/inc-top.php';
?>

<!-- Affichage du formulaire de réinitialisation du mot de passe -->
<div class="center">
    <form class="container center" method="post" action="">
        <form method="post" action="">
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Réinitialiser le mot de passe</button>
        </form>
</div>

<?php require '../../includes/inc-bottom.php'; ?>