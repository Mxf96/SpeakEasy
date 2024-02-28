<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['form_data'] = $_POST; // Keep form data

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

    // Verification of the email's uniqueness
    $stmt = $dbh->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = "L'email est déjà utilisé par un autre compte.";
        header('Location: register.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $signUpDate = date("Y-m-d"); // Date format for MySQL

    // Random profile picture selection
    $availableImages = ['1.jpg', '2.jpg', '3.jpg', '4.jpg'];
    $randomImage = $availableImages[array_rand($availableImages)];
    $profilePhoto = "/assets/pictures/userPictures/" . $randomImage;

    try {
        // User insertion with randomly selected default profile picture
        $stmt = $dbh->prepare("INSERT INTO users (name, email, password, signUpDate, profile_photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $signUpDate, $profilePhoto]);

        // Get the ID of the newly created user
        $newUserID = $dbh->lastInsertId();

        // Automatically add the user to their own friends list
        $stmt = $dbh->prepare("INSERT INTO userfriends (userID, friendUserID, status) VALUES (:userID, :friendID, 'accepted')");
        $stmt->execute([':userID' => $newUserID, ':friendID' => $newUserID]);

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
