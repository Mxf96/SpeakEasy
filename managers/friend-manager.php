<?php
require '../includes/inc-db-connect.php';

// Fonction pour obtenir la liste des amis
function getFriendsList($dbh, $userID) {
    $friends = [];
    $stmt = $dbh->prepare("SELECT u.userID, u.name 
        FROM Users u
        JOIN UserFriends uf ON (uf.friendUserID = u.userID OR uf.userID = u.userID)
        WHERE (uf.userID = :userID OR uf.friendUserID = :userID)
        AND u.userID != :userID
        AND uf.status = 'accepted'"); // Only select accepted friendships
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $friends;
}

// Fonction pour ajouter un ami
function addFriend($dbh, $userID, $friendName) {
    // Trouver l'ID de l'ami par son nom
    $stmt = $dbh->prepare("SELECT userID FROM Users WHERE name = :friendName");
    $stmt->bindParam(':friendName', $friendName, PDO::PARAM_STR);
    $stmt->execute();
    $friend = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($friend) {
        $friendUserID = $friend['userID'];

        if ($userID == $friendUserID) {
            return "Vous ne pouvez pas vous ajouter vous-même comme ami.";
        }

        $stmt = $dbh->prepare("SELECT * FROM UserFriends WHERE (userID = :userID AND friendUserID = :friendUserID) OR (userID = :friendUserID AND friendUserID = :userID) AND status = 'pending'");
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':friendUserID', $friendUserID, PDO::PARAM_INT);
        $stmt->execute();
        $existingFriendship = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$existingFriendship) {
            $stmt = $dbh->prepare("INSERT INTO UserFriends (userID, friendUserID, status) VALUES (:userID, :friendUserID, 'pending')");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':friendUserID', $friendUserID, PDO::PARAM_INT);
            $stmt->execute();
            return "Demande d'amitié envoyée avec succès.";
        } else {
            return "Vous avez déjà envoyé une demande ou vous êtes déjà amis.";
        }
    } else {
        return "Aucun utilisateur trouvé avec ce nom.";
    }
}

// Fonction pour récupérer les demandes d'amitié en attente
function getPendingFriendRequests($dbh, $userID) {
    $stmt = $dbh->prepare("
        SELECT u.userID, u.name 
        FROM Users u
        JOIN UserFriends uf ON uf.userID = u.userID
        WHERE uf.friendUserID = :userID AND uf.status = 'pending'
    ");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function acceptFriendRequest($dbh, $userID, $friendUserID) {
    $stmt = $dbh->prepare("UPDATE UserFriends SET status = 'accepted' WHERE userID = :friendUserID AND friendUserID = :userID AND status = 'pending'");
    $stmt->execute([':friendUserID' => $friendUserID, ':userID' => $userID]);
}

function declineFriendRequest($dbh, $userID, $friendUserID) {
    $stmt = $dbh->prepare("DELETE FROM UserFriends WHERE userID = :friendUserID AND friendUserID = :userID AND status = 'pending'");
    $stmt->execute([':friendUserID' => $friendUserID, ':userID' => $userID]);
}

// Fonction pour suppprimer un ami de ça liste
function removeFriend($dbh, $userID, $friendUserID) {
    try {
        $stmt = $dbh->prepare("DELETE FROM UserFriends WHERE (userID = :userID AND friendUserID = :friendUserID) OR (userID = :friendUserID AND friendUserID = :userID)");
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':friendUserID', $friendUserID, PDO::PARAM_INT);
        $stmt->execute();
        return "L'ami a été supprimé avec succès.";
    } catch (PDOException $e) {
        return "Erreur lors de la suppression de l'ami : " . $e->getMessage();
    }
}