<?php
require '../includes/inc-db-connect.php';
require '../managers/security-manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $_SESSION['error_message'] = "Tous les champs doivent être remplis.";
        header('Location: register.php');
        exit;
    }

    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header('Location: register.php');
        exit;
    }

    // Vérification de l'unicité de l'email
    $stmt = $dbh->prepare("SELECT email FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = "L'email est déjà utilisé par un autre compte.";
        header('Location: register.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $signUpDate = date("Y-m-d"); // Format de date pour MySQL

    try {
        // Inclusion de la date d'inscription dans la requête
        $stmt = $dbh->prepare("INSERT INTO Users (name, email, password, signUpDate) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $signUpDate]);

        $_SESSION['success_message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
        header('Location: ../log/login.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur lors de l'inscription, veuillez réessayer.";
        header('Location: register.php');
        exit;
    }
} else {
    header('Location: register.php');
    exit;
}
?>