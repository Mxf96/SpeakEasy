<?php
require_once '../managers/profile-manager.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $filePath = null;

    // Gestion de l'upload de l'image de profil
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "/assets/pictures/userPictures/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
            $filePath = basename($_FILES["profile_pic"]["name"]);
        }
    }

    updateUserDetails($dbh, $userID, $name, $bio, $filePath);

    // Redirection vers la page de profil après la mise à jour
    header("Location: profile.php");
    exit;
}
?>
