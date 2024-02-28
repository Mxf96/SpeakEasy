<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
        $_SESSION['form_data'] = ['email' => $email]; // Conserver l'email pour repopulation
        header('Location: login.php');
        exit;
    }

    $stmt = $dbh->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['userID'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: ../home/home.php');
        exit;
    } else {
        $_SESSION['error_message'] = "Identifiants incorrects.";
        $_SESSION['form_data'] = ['email' => $email]; // Conserver l'email pour repopulation
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>