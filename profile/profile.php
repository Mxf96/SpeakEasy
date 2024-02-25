<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';
require_once '../managers/profile-manager.php';

// Redirige vers la page de connexion si aucun utilisateur n'est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php');
    exit;
}

// Récupère l'ID de l'utilisateur depuis la session
$userID = $_SESSION['user_id'];
// Utilise l'ID pour récupérer le nom de l'utilisateur
$userName = getUserName($dbh, $userID);
$userDescription = getUserDescription($dbh, $userID);
$user = getUserInfo($dbh, $userID);
$profilePhoto = $user['profile_photo'] ?? '/assets/pictures/default-profile.png';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/pictures/userPictures/";
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        updateUserProfileImage($dbh, $userID, basename($_FILES["profile_pic"]["name"]));
        header("Location: profile.php"); // Refresh to show the new image
    }
}

require '../includes/inc-top-profile.php';
?>

<div class="profile-container">
    <h1><?= sanitize_input($userName ?? 'Unknown'); ?></h1>
    <img src="<?= sanitize_input($profilePhoto); ?>" alt="Profile Picture" class="profile-picture" onclick="document.getElementById('profile_pic').click();">
    <form action="profile.php" method="post" enctype="multipart/form-data">
        <input type="file" name="profile_pic" id="profile_pic" hidden onchange="this.form.submit();">
    </form>
    <!-- Hidden form for updating profile, initially hidden -->
    <div id="settingsForm" style="display:none;">
        <form action="updateProfile.php" method="post" enctype="multipart/form-data" class="form-container">
            <input type="text" class="input" name="name" placeholder="Entrez un nouveau nom..." value="<?= sanitize_input($user['name'] ?? ''); ?>">
            <input type="text" name="bio" class="input" placeholder="Mettez à jour votre bio ici..."><?= sanitize_input($user['description'] ?? ''); ?></input>
            <button type="submit" class="button">Mettre à jour le profil</button>
        </form>
    </div>
    <!-- Remplacer $user['description'] par $userDescription -->
    <div class="bio-container">
        <h3>Ma bio</h3>
        <div class="bio-content">
            <p><?= sanitize_input($userDescription ?? 'No bio set.'); ?></p>
            <i class="fas fa-cog settings-icon" id="settingsIcon"></i>
        </div>
    </div>
</div>




<script src="/scripts/setting.js"></script>
<?php require '../includes/inc-bottom.php'; ?>