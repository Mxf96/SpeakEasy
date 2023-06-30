<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'includes/inc-db-connect.php';
require 'admin/managers/utilisateur-manager.php';

// Fonction pour générer un token de réinitialisation aléatoire
function generateResetToken()
{
    $length = 32;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $token;
}

// Fonction pour stocker le token de réinitialisation en relation avec l'utilisateur dans la base de données
function storeResetToken($dbh, $userId, $token)
{
    $stmt = $dbh->prepare("UPDATE utilisateur SET reset_token = :token, reset_token_created_at = NOW() WHERE id_utilisateur = :userId");
    $stmt->bindValue(':token', $token);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
}

// Fonction pour envoyer l'e-mail de réinitialisation avec le lien contenant le token
function sendResetEmail($email, $resetLink)
{
    $subject = "Réinitialisation du mot de passe";
    $message = "Bonjour,\n\nVeuillez cliquer sur le lien suivant pour réinitialiser votre mot de passe :\n$resetLink";
    $headers = "From: votre_email@example.com\r\n" .
        "Reply-To: votre_email@example.com\r\n" .
        "X-Mailer: PHP/" . phpversion();

    // Envoyer l'e-mail
    mail($email, $subject, $message, $headers);
}

function getUserByEmail($dbh, $email)
{
    $stmt = $dbh->prepare("SELECT * FROM utilisateur WHERE email_utilisateur = :email");
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Vérifier si l'e-mail existe dans la base de données
    $user = getUserByEmail($dbh, $email);

    if ($user) {
        // Générer un token de réinitialisation et stocker en relation avec l'utilisateur dans la base de données
        $token = generateResetToken();
        storeResetToken($dbh, $user['id_utilisateur'], $token);

        // Envoyer l'e-mail de réinitialisation avec le lien contenant le token
        $resetLink = "http://localhost/SpeakEasy2/reset-password.php?token=$token";
        sendResetEmail($email, $resetLink);

        // Rediriger vers une page de confirmation
        header("Location: reset-request-confirmation.php");
        exit;
    } else {
        // L'e-mail n'existe pas dans la base de données
        $error = "Cet e-mail n'est pas enregistré.";
    }
}

require 'includes/inc-top.php';
?>

<!-- Affichage du formulaire de demande de réinitialisation du mot de passe -->
<div class="center">
    <form class="container center" method="post" action="">
        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Réinitialiser le mot de passe</button>
        <?php if (isset($error)) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>
</div>
<?php require 'includes/inc-bottom.php'; ?>
