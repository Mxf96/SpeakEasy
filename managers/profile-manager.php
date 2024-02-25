<?php
require '../includes/inc-db-connect.php';

function getUserName($dbh, $userID) {
    try {
        $stmt = $dbh->prepare("SELECT name FROM users WHERE userID = ?");
        $stmt->execute([$userID]);
        $user = $stmt->fetch();
        return $user ? $user['name'] : null;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du nom de l'utilisateur : " . $e->getMessage());
        return null;
    }
}

function getUserInfo($dbh, $userID) {
    $stmt = $dbh->prepare("SELECT userID, `name`, email, `description`, profile_photo FROM users WHERE userID = :userID");
    $stmt->execute([':userID' => $userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserDescription($dbh, $userID) {
    try {
        $stmt = $dbh->prepare("SELECT description FROM users WHERE userID = :userID");
        $stmt->execute([':userID' => $userID]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['description'] : null;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de la description de l'utilisateur : " . $e->getMessage());
        return null;
    }
}

function updateUserProfileImage($dbh, $userID, $filePath) {
    $stmt = $dbh->prepare("UPDATE users SET profile_photo = :filePath WHERE userID = :userID");
    $stmt->execute([':filePath' => '/assets/pictures/userPictures/' . $filePath, ':userID' => $userID]);
}

function updateUserDetails($dbh, $userID, $name, $bio) {
    // Récupère les informations actuelles de l'utilisateur pour vérifier si des valeurs sont fournies
    $currentUserInfo = getUserInfo($dbh, $userID);
    
    // Si aucun nouveau nom n'est fourni, utiliser l'ancien
    if (empty($name)) {
        $name = $currentUserInfo['name'];
    }
    
    // Si aucune nouvelle bio n'est fournie, utiliser l'ancienne
    if (empty($bio)) {
        $bio = $currentUserInfo['description'];
    }

    // Mise à jour du nom et de la bio
    $stmt = $dbh->prepare("UPDATE users SET name = :name, description = :bio WHERE userID = :userID");
    $stmt->execute([':name' => $name, ':bio' => $bio, ':userID' => $userID]);
}
?>
