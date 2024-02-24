<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';
require_once '../managers/profile-manager.php';
require '../includes/inc-top-profile.php';

// Initialize $image as an empty array to ensure it's always defined.
$image = [];

$userID = $_SESSION['user_id'] ?? null; // Ensure this line exists to define $userID.

// Assuming getUserInfo and getUserProfileImage are correctly fetching data.
$user = getUserInfo($dbh, $userID);
$imageData = getUserProfileImage($dbh, $userID);
if ($imageData) {
    $image = $imageData;
}

?>
<h1>Profil de <?= is_array($user) && !empty($user['name']) ? htmlspecialchars($user['name']) : 'Utilisateur' ?></h1>
<?php if (!empty($image['filePath'])) : ?>
    <img src="<?= htmlspecialchars($image['filePath']) ?>" alt="Photo de profil">
<?php endif; ?>
<form action="profile.php" method="post" enctype="multipart/form-data">
    Sélectionnez une image pour votre profil :
    <input type="file" name="profile_pic" id="profile_pic">
    <button type="submit">Télécharger</button>
</form>

<?php require '../includes/inc-bottom.php'; ?>