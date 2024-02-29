<?php
require_once '../includes/inc-db-connect.php';

function getUserGroups($dbh, $userID) {
    $sql = "SELECT g.groupID, g.name, g.description 
            FROM `Groups` g
            JOIN GroupMemberships gm ON g.groupID = gm.groupID
            WHERE gm.userID = :userID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':userID' => $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createGroup($dbh, $groupName, $description, $userID) {
    try {
        // Insert the new group into the `groups` table
        $sql = "INSERT INTO `groups` (name, description) VALUES (:name, :description)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':name' => $groupName, ':description' => $description]);
        $groupID = $dbh->lastInsertId();

        // Automatically add the creator as a member of the group
        $sql = "INSERT INTO `groupmemberships` (userID, groupID, status) VALUES (:userID, :groupID, 'accepted')";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':userID' => $userID, ':groupID' => $groupID]);

    } catch (PDOException $e) {
        return "Error creating group: " . $e->getMessage();
    }
}

function joinGroup($dbh, $groupName, $userID) {
    try {
        // Find the group by name
        $sql = "SELECT groupID FROM `groups` WHERE name = :groupName LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':groupName' => $groupName]);
        $group = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($group) {
            $groupID = $group['groupID'];
            
            // Check if user is already a member
            $sql = "SELECT * FROM `groupmemberships` WHERE userID = :userID AND groupID = :groupID";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([':userID' => $userID, ':groupID' => $groupID]);
            if ($stmt->fetch()) {
                return "You are already a member of this group.";
            }

            // Add user to the group
            $sql = "INSERT INTO `groupmemberships` (userID, groupID, status) VALUES (:userID, :groupID, 'accepted')";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([':userID' => $userID, ':groupID' => $groupID]);

        } else {
            return "Group not found.";
        }
    } catch (PDOException $e) {
        return "Error joining group: " . $e->getMessage();
    }
}

function deleteGroup($dbh, $groupID, $userID) {
    try {
        // Delete the group from the `groups` table
        $sql = "DELETE FROM `groups` WHERE groupID = :groupID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':groupID' => $groupID]);

        // Optionally, remove all group memberships related to this group
        $sql = "DELETE FROM `groupmemberships` WHERE groupID = :groupID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':groupID' => $groupID]);

    } catch (PDOException $e) {
        return "Error removing group: " . $e->getMessage();
    }
}

function getGroupInfo($dbh, $groupID) {
    try {
        // Préparer la requête SQL pour récupérer les informations du groupe par son ID
        $sql = "SELECT groupID, name, description, creationDate FROM `groups` WHERE groupID = :groupID";
        $stmt = $dbh->prepare($sql);
        
        // Exécuter la requête en passant l'ID du groupe comme paramètre
        $stmt->execute([':groupID' => $groupID]);
        
        // Récupérer le résultat de la requête
        $groupInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Retourner les informations du groupe si disponibles, sinon retourner null
        return $groupInfo ? $groupInfo : null;
    } catch (PDOException $e) {
        // Gérer l'erreur éventuelle (log, message d'erreur, etc.)
        error_log("Erreur lors de la récupération des informations du groupe : " . $e->getMessage());
        return null;
    }
}

function getGroupMembers($dbh, $groupID) {
    $sql = "SELECT u.userID, u.name,  u.profile_photo
            FROM users u
            JOIN groupmemberships gm ON u.userID = gm.userID
            WHERE gm.groupID = :groupID AND gm.status = 'accepted'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':groupID' => $groupID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertGroupMessage($dbh, $fromUserID, $groupID, $channelID, $message) {
    // Ajoutez `channelID` dans la liste des colonnes et des valeurs de la requête SQL
    $sql = "INSERT INTO messages (fromUserID, groupID, channelID, content, dateTime) VALUES (:fromUserID, :groupID, :channelID, :content, NOW())";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':fromUserID' => $fromUserID,
        ':groupID' => $groupID,
        ':channelID' => $channelID, // Assurez-vous de passer `channelID` comme paramètre
        ':content' => $message
    ]);
}

function getGroupMessagesByChannel($dbh, $groupID, $channelID) {
    try {
        // Modifier la requête pour filtrer également par channelID
        $sql = "SELECT m.*, u.name AS userName 
                FROM messages m
                JOIN users u ON m.fromUserID = u.userID 
                WHERE m.groupID = :groupID AND m.channelID = :channelID
                ORDER BY m.dateTime ASC";
        
        // Préparer la requête avec PDO
        $stmt = $dbh->prepare($sql);
        
        // Exécuter la requête en passant l'ID du groupe et l'ID du canal comme paramètres
        $stmt->execute([':groupID' => $groupID, ':channelID' => $channelID]);
        
        // Récupérer tous les messages du groupe pour le canal spécifié
        $groupMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retourner les messages récupérés
        return $groupMessages;
    } catch (PDOException $e) {
        // En cas d'erreur, afficher le message d'erreur
        error_log("Erreur lors de la récupération des messages du groupe pour le canal spécifié : " . $e->getMessage());
        return [];
    }
}

function getChannels($dbh, $groupID) {
    try {
        // SQL query to select channels by groupID
        $sql = "SELECT channelID, name, description, creationDate FROM channels WHERE groupID = :groupID ORDER BY creationDate ASC";
        $stmt = $dbh->prepare($sql);
        
        // Execute the query with the provided groupID
        $stmt->execute([':groupID' => $groupID]);
        
        // Fetch all matching records
        $channels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the channels
        return $channels;
    } catch (PDOException $e) {
        // Handle any errors
        error_log("Error fetching channels: " . $e->getMessage());
        return [];
    }
}


function createChannel($dbh, $groupID, $channelName, $channelDescription) {
    try {
        // Ajoutez `description` dans la liste des colonnes et des valeurs de la requête SQL
        $sql = "INSERT INTO channels (groupID, name, description) VALUES (:groupID, :name, :description)";
        $stmt = $dbh->prepare($sql);
        // Passez `channelDescription` comme paramètre lors de l'exécution de la requête
        $stmt->execute([':groupID' => $groupID, ':name' => $channelName, ':description' => $channelDescription]);
        return $dbh->lastInsertId(); // Retourne l'ID du salon créé
    } catch (PDOException $e) {
        return "Error creating channel: " . $e->getMessage();
    }
}

function getGroupChannels($dbh, $groupID) {
    try {
        $sql = "SELECT * FROM channels WHERE groupID = :groupID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':groupID' => $groupID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error fetching channels: " . $e->getMessage();
    }
}

function updateChannel($dbh, $channelID, $name, $description) {
    try {
        $sql = "UPDATE channels SET name = :name, description = :description WHERE channelID = :channelID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':name' => $name, ':description' => $description, ':channelID' => $channelID]);
    } catch (PDOException $e) {
        error_log("Error updating channel: " . $e->getMessage());
    }
}

function deleteChannel($dbh, $channelID) {
    try {
        $sql = "DELETE FROM channels WHERE channelID = :channelID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':channelID' => $channelID]);
    } catch (PDOException $e) {
        error_log("Error deleting channel: " . $e->getMessage());
    }
}

?>