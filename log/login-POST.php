<?php
require '../includes/inc-db-connect.php';
require '../managers/security-manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);

    // Vérification si les champs sont remplis
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
        header('Location: ../login.php');
        exit;
    }

    // Recherche de l'utilisateur par email
    $stmt = $dbh->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si un utilisateur est trouvé et que le mot de passe correspond
    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        // Stockage de données utilisateur dans la session, ajustez selon vos besoins
        $_SESSION['user_id'] = $user['userID'];
        $_SESSION['user_name'] = $user['name'];
        // Redirection vers la page d'accueil ou le tableau de bord de l'utilisateur
        header('Location: ../home/home.php');
        exit;
    } else {
        // Échec de la connexion
        $_SESSION['error_message'] = "Identifiants incorrects.";
        header('Location: ../login.php');
        exit;
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers le formulaire de connexion
    header('Location: ../login.php');
    exit;
}
?>
