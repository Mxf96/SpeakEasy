<?php
require '../includes/inc-db-connect.php';

function getUserName($dbh, $userID) {
    try {
        $stmt = $dbh->prepare("SELECT name FROM Users WHERE userID = ?");
        $stmt->execute([$userID]);
        $user = $stmt->fetch();
        return $user ? $user['name'] : null;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du nom de l'utilisateur : " . $e->getMessage());
        return null;
    }
}

function getUserInfo($dbh, $userID) {
    $query = $dbh->prepare("SELECT * FROM users WHERE userID = :userID");
    $query->execute([':userID' => $userID]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserProfileImage($dbh, $userID) {
    $query = $dbh->prepare("SELECT filePath FROM images WHERE userID = :userID LIMIT 1");
    $query->execute([':userID' => $userID]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function updateUserProfileImage($dbh, $userID, $filePath) {
    $query = $dbh->prepare("INSERT INTO images (filePath, userID) VALUES (:filePath, :userID) ON DUPLICATE KEY UPDATE filePath = :filePath");
    $query->execute([':filePath' => $filePath, ':userID' => $userID]);
}