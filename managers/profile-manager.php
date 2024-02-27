<?php
require '../includes/inc-db-connect.php';

function getUserName($dbh, $userID) {
    try {
        $stmt = $dbh->prepare("SELECT `name` FROM `users` WHERE `userID` = :userID");
        $stmt->execute([':userID' => $userID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
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

function updateUserProfileImage($dbh, $userID, $fileName) {
    // Ici, on enregistre seulement le nom du fichier en base de données.
    $stmt = $dbh->prepare("UPDATE users SET profile_photo = :fileName WHERE userID = :userID");
    $stmt->execute([':fileName' => $fileName, ':userID' => $userID]);
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

function addFriendProfile($pdo, $userID, $friendID) {
    // First, check if the friendship already exists to prevent duplicates
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM userfriends WHERE (userID = :userID AND friendUserID = :friendID) OR (userID = :friendID AND friendUserID = :userID)");
    $checkStmt->execute([':userID' => $userID, ':friendID' => $friendID]);
    $exists = $checkStmt->fetchColumn() > 0;

    if ($exists) {
        // Friendship already exists
        return "Une amitié existe déjà ou une demande est en attente.";
    } else {
        // Insert the new friendship request
        $insertStmt = $pdo->prepare("INSERT INTO userfriends (userID, friendUserID, status) VALUES (:userID, :friendID, 'pending')");
        $success = $insertStmt->execute([':userID' => $userID, ':friendID' => $friendID]);

        if ($success) {
            return "Demande d'ami envoyée avec succès.";
        } else {
            return "Échec de l'envoi de la demande d'ami.";
        }
    }
}

// Fonction pour vérifier si les utilisateurs sont déjà amis
function isAlreadyFriend($dbh, $friendID, $userID) {
    $stmt = $dbh->prepare("SELECT * FROM userfriends WHERE (userID = ? AND friendUserID = ?) OR (userID = ? AND friendUserID = ?)");
    $stmt->execute([$friendID, $userID, $userID, $friendID]);
    return $stmt->fetch() ? true : false;
}
?>