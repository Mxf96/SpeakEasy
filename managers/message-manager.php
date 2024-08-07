<?php
require_once '../includes/inc-db-connect.php'; 

function insertMessage($dbh, $fromUserID, $toUserID, $message, $file) {
    $filePath = null;
    // Check if a file has been uploaded
    if (!empty($file) && $file['error'] == UPLOAD_ERR_OK) {
        $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . "/assets/pictures/messageFilePath/"; // Correct path to the directory where files are stored
        $fileName = time() . '-' . basename($file['name']);
        $filePath = $targetDirectory . $fileName;

        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Store the relative path in the database
            $filePath = "/assets/pictures/messageFilePath/" . $fileName;
        } else {
            // If moving the file failed, set $filePath to null
            $filePath = null;
        }
    }

    // Prepare and execute the SQL query to insert the message along with the file path
    $sql = "INSERT INTO messages (fromUserID, toUserID, content, dateTime, filePath) VALUES (:fromUserID, :toUserID, :content, NOW(), :filePath)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':fromUserID' => $fromUserID,
        ':toUserID' => $toUserID,
        ':content' => $message,
        ':filePath' => $filePath
    ]);
}

function getMessagesBetweenUsers($dbh, $userID1, $userID2) {
    // Mettre à jour le statut des messages à 'read' seulement pour les messages reçus de $userID2
    $updateSql = "UPDATE messages SET status = 'read' WHERE toUserID = :userID1 AND fromUserID = :userID2 AND status = 'unread'";
    $updateStmt = $dbh->prepare($updateSql);
    $updateStmt->execute([
        ':userID1' => $userID1,
        ':userID2' => $userID2
    ]);

    // Sélectionner les messages entre les deux utilisateurs
    $selectSql = "SELECT * FROM messages WHERE (fromUserID = :userID1 AND toUserID = :userID2) OR (fromUserID = :userID2 AND toUserID = :userID1) ORDER BY dateTime ASC";
    $selectStmt = $dbh->prepare($selectSql);
    $selectStmt->execute([
        ':userID1' => $userID1,
        ':userID2' => $userID2
    ]);
    return $selectStmt->fetchAll(PDO::FETCH_ASSOC);
}


function getUnreadMessages($userID) {
    global $dbh;
    $sql = "SELECT m.*, u.name as userName FROM messages m JOIN users u ON m.fromUserID = u.userID WHERE m.toUserID = :userID AND m.status = 'unread' ORDER BY m.dateTime DESC LIMIT 10";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':userID' => $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countUnreadMessages($dbh, $userID)
{
    $sql = "SELECT COUNT(*) as count FROM messages WHERE toUserID = :userID AND status = 'unread'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':userID' => $userID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['count'] : 0;
}

function getUnreadMessagesByUser($dbh, $toUserID) {
    $sql = "SELECT fromUserID, COUNT(*) as count, u.name 
            FROM messages m
            JOIN users u ON m.fromUserID = u.userID
            WHERE toUserID = :toUserID AND m.status = 'unread'
            GROUP BY fromUserID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':toUserID' => $toUserID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLastConversations($dbh, $userID, $limit = 5) {
    $sql = "SELECT m.*, u.userID AS otherUserID, u.name AS userName, 
            (SELECT content FROM messages WHERE (fromUserID = m.fromUserID AND toUserID = m.toUserID) OR (fromUserID = m.toUserID AND toUserID = m.fromUserID) ORDER BY dateTime DESC LIMIT 1) AS lastMessage,
            (SELECT dateTime FROM messages WHERE (fromUserID = m.fromUserID AND toUserID = m.toUserID) OR (fromUserID = m.toUserID AND toUserID = m.fromUserID) ORDER BY dateTime DESC LIMIT 1) AS lastMessageTime
            FROM messages m
            JOIN users u ON u.userID = CASE WHEN m.fromUserID = :userID THEN m.toUserID ELSE m.fromUserID END
            WHERE m.fromUserID = :userID OR m.toUserID = :userID
            GROUP BY CASE WHEN m.fromUserID > m.toUserID THEN CONCAT(m.fromUserID, '-', m.toUserID) ELSE CONCAT(m.toUserID, '-', m.fromUserID) END
            ORDER BY lastMessageTime DESC
            LIMIT :limit";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Changement ici pour s'assurer que la limite est correctement appliquée
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

