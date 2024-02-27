<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';
require_once '../managers/profile-manager.php';

// Redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php');
    exit;
}

// Sécurise l'entrée et détermine l'ID utilisateur
$userID = filter_input(INPUT_GET, 'userID', FILTER_VALIDATE_INT) ?: $_SESSION['user_id'];

// Récupère les informations utilisateur
$userInfo = getUserInfo($dbh, $userID);
$userName = htmlspecialchars($userInfo['name'] ?? 'Utilisateur inconnu');
$userDescription = htmlspecialchars($userInfo['description'] ?? 'Aucune description.');
$profilePhoto = htmlspecialchars($userInfo['profile_photo'] ?? '/assets/pictures/default-profile.png');

// Vérification du propriétaire du profil
$isProfileOwner = ($userID === $_SESSION['user_id']);
$alreadyFriends = isAlreadyFriend($dbh, $_SESSION['user_id'], $userID);

// Traitement de l'ajout d'ami
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addFriend']) && !$alreadyFriends) {
    $message = addFriendProfile($dbh, $_SESSION['user_id'], $userID);
    // Utiliser POST-Redirect-GET pour éviter le rechargement du formulaire
    header("Location: profile.php?userID=$userID&requestSent=1");
    exit;
}

// Si l'utilisateur vient après une redirection avec une demande d'ami
if (isset($_GET['requestSent'])) {
    $message = 'Votre demande d\'ami a été envoyée.';
}

// Gère le téléchargement de la photo de profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic']) && $isProfileOwner) {
    $target_dir = "../assets/pictures/userPictures/";
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    $uploadPath = "/assets/pictures/userPictures/" . basename($_FILES["profile_pic"]["name"]);

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        updateUserProfileImage($dbh, $userID, $uploadPath);
        header("Location: profile.php?userID=$userID");
        exit;
    }
    // Gestion des erreurs de téléchargement omise pour la concision
}

require '../includes/inc-top-profile.php';
?>

<div class="profile-container">
    <h1><?= $userName; ?></h1>
    <?php if ($isProfileOwner): ?>
        <img src="<?= $profilePhoto; ?>" alt="Profile Picture" class="profile-picture" onclick="document.getElementById('profile_pic').click();" style="cursor: pointer;">
        <form action="profile.php?userID=<?= htmlspecialchars($userID); ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_pic" id="profile_pic" hidden onchange="this.form.submit();">
        </form>
    <?php else: ?>
        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <img src="<?= $profilePhoto; ?>" alt="Profile Picture" class="profile-picture">
        <?php if (!$alreadyFriends): ?>
            <form action="profile.php?userID=<?= htmlspecialchars($userID); ?>" method="post">
                <input type="hidden" name="addFriend" value="true">
                <button class="addbutton" type="submit" class="btn btn-primary">Ajouter comme ami</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
    <div class="bio-container">
        <h3>Ma bio</h3>
        <p><?= $userDescription; ?></p>
    </div>
</div>

<script src="/scripts/addFriendProfile.js"></script>
<script src="/scripts/setting.js"></script>
<?php require '../includes/inc-bottom.php'; ?>
