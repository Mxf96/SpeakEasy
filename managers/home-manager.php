<?php
require_once '../includes/inc-db-connect.php';

function getRecentNotifications($userID) {
    global $dbh;
    $sql = "SELECT * FROM notifications WHERE userID = :userID ORDER BY created_at DESC LIMIT 10;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':userID' => $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getGroupSuggestions($userID) {
    global $dbh;
    // Cette requête est un exemple basique. Vous devriez l'ajuster pour recommander des groupes basés sur des critères spécifiques.
    $sql = "SELECT g.* FROM `groups` g LEFT JOIN groupmemberships gm ON g.groupID = gm.groupID AND gm.userID = :userID WHERE gm.userID IS NULL LIMIT 10";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':userID' => $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
