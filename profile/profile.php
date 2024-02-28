<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';
require_once '../managers/profile-manager.php';

// Redirige vers la page de connexion si aucun utilisateur n'est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php');
    exit;
}

// Vérifie si un userID est spécifié dans l'URL, sinon utilise l'ID de l'utilisateur connecté
$userID = isset($_GET['userID']) && intval($_GET['userID']) > 0 ? intval($_GET['userID']) : $_SESSION['user_id'];

// Utilise l'ID pour récupérer les informations de l'utilisateur
$userInfo = getUserInfo($dbh, $userID);
$userName = getUserName($dbh, $userID);
$userDescription = $userInfo['description'] ?? 'No description set.';
$profilePhoto = $userInfo['profile_photo'] ?? '/assets/pictures/default-profile.png';

// Appeler la fonction pour vérifier si l'utilisateur actuel est le propriétaire du profil
$isProfileOwner = isProfileOwner($dbh, $userID);

$alreadyFriends = isAlreadyFriend($dbh, $_SESSION['user_id'], $userID);

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic']) && $isProfileOwner) {
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/pictures/userPictures/";
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        updateUserProfileImage($dbh, $userID, basename($_FILES["profile_pic"]["name"]));
        header("Location: profile.php?userID=$userID"); // Refresh to show the new image
    }
}

require '../includes/inc-top-profile.php';
?>

<div class="profile-container">
    <h1><?= htmlspecialchars($userName); ?></h1>
    <?php if ($isProfileOwner) : ?>
        <!-- Si le propriétaire du profil est connecté, il peut cliquer sur son image pour la changer -->
        <img src="<?= sanitize_input($profilePhoto); ?>" alt="Profile Picture" class="profile-picture" onclick="document.getElementById('profile_pic').click();" style="cursor: pointer;">
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_pic" id="profile_pic" hidden onchange="this.form.submit();">
        </form>
    <?php else : ?>
        <!-- Afficher l'image sans possibilité de modification -->
        <img src="<?= sanitize_input($profilePhoto); ?>" alt="Profile Picture" class="profile-picture">
    <?php endif; ?>
    <!-- Le reste du code reste inchangé -->
    <div id="settingsForm" style="display:none;">
        <form action="updateProfile.php" method="post" enctype="multipart/form-data" class="form-container">
            <input type="text" class="input" name="name" placeholder="Entrez un nouveau nom..." value="<?= sanitize_input($user['name'] ?? ''); ?>">
            <input type="text" name="bio" class="input" placeholder="Mettez à jour votre bio ici..."><?= sanitize_input($user['description'] ?? ''); ?></input>
            <button type="submit" class="button">Mettre à jour le profil</button>
        </form>
    </div>
    <?php if (!$alreadyFriends && !$isProfileOwner) : ?>
        <button id="addFriendButton" class="addbutton" data-userid="<?= htmlspecialchars($userID); ?>">Ajouter comme ami</button>
    <?php endif; ?>
    <div class="bio-container">
        <h3>Ma bio</h3>
        <div class="bio-content">
            <p><?= sanitize_input($userDescription ?? 'No bio set.'); ?></p>
            <?php if ($isProfileOwner) : ?>
                <i class="fas fa-cog settings-icon" id="settingsIcon"></i>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="../scripts/addFriendProfile.js"></script>
<script src="../scripts/setting.js"></script>
<?php require '../includes/inc-bottom.php'; ?>