<?php
require '../includes/inc-db-connect.php'; // Ensure this path is correct

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

?>