<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';

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
    $stmt = $dbh->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = "L'email est déjà utilisé par un autre compte.";
        header('Location: register.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $signUpDate = date("Y-m-d"); // Format de date pour MySQL

    // Choix aléatoire de l'image de profil par défaut
    $directoryPath = '../assets/pictures/userPictures';
    $imageFiles = glob($directoryPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if ($imageFiles) {
        shuffle($imageFiles); // Mélanger le tableau des images
        $randomImageFilePath = $imageFiles[0]; // Prendre la première image après le mélange
        $profilePhoto = "/assets/pictures/userPictures/" . basename($randomImageFilePath);
    } else {
        error_log('No default user pictures found in ' . $directoryPath);
    }

    try {
        // Insertion de l'utilisateur avec la photo de profil par défaut
        $stmt = $dbh->prepare("INSERT INTO users (name, email, password, signUpDate, profile_photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $signUpDate, $profilePhoto]);

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