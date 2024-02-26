<?php
require '../includes/inc-db-connect.php'; 

function insertMessage($dbh, $fromUserID, $toUserID, $message) {
    $sql = "INSERT INTO messages (fromUserID, toUserID, content, dateTime) VALUES (:fromUserID, :toUserID, :content, NOW())";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':fromUserID' => $fromUserID,
        ':toUserID' => $toUserID,
        ':content' => $message
    ]);
}

function getMessagesBetweenUsers($dbh, $userID1, $userID2) {
    $sql = "SELECT * FROM messages WHERE (fromUserID = :userID1 AND toUserID = :userID2) OR (fromUserID = :userID2 AND toUserID = :userID1) ORDER BY dateTime ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':userID1' => $userID1,
        ':userID2' => $userID2
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}