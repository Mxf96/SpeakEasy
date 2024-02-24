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
