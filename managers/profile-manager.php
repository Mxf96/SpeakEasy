<?php
require_once '../includes/inc-db-connect.php';

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
    // Chemin vers le dossier des images de profil
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/assets/pictures/userPictures/";
    $targetFile = $targetDir . $fileName;

    // Dimensions souhaitées
    $desiredWidth = 214;
    $desiredHeight = 261;

    // Redimensionner l'image
    list($originalWidth, $originalHeight) = getimagesize($targetFile);
    $src = imagecreatefromstring(file_get_contents($targetFile));
    $dst = imagecreatetruecolor($desiredWidth, $desiredHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

    // Enregistrer l'image redimensionnée
    $imageSaveFunction = getImageSaveFunction($fileName);
    if ($imageSaveFunction) {
        $imageSaveFunction($dst, $targetFile);
    }

    // Libérer les ressources
    imagedestroy($src);
    imagedestroy($dst);

    // Ajoute le chemin complet vers le fichier d'image
    $filePath = "/assets/pictures/userPictures/" . $fileName;

    // Mise à jour de la photo de profil dans la base de données
    $stmt = $dbh->prepare("UPDATE users SET profile_photo = :filePath WHERE userID = :userID");
    $stmt->execute([':filePath' => $filePath, ':userID' => $userID]);
}

/**
 * Détermine la fonction PHP à utiliser pour sauvegarder l'image selon son extension.
 */
function getImageSaveFunction($fileName) {
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            return 'imagejpeg';
        case 'png':
            return 'imagepng';
        case 'gif':
            return 'imagegif';
        default:
            return null;
    }
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

function addFriendProfile($dbh, $userID, $friendID) {
    // First, check if the friendship already exists to prevent duplicates
    $checkStmt = $dbh->prepare("SELECT COUNT(*) FROM userfriends WHERE (userID = :userID AND friendUserID = :friendID) OR (userID = :friendID AND friendUserID = :userID)");
    $checkStmt->execute([':userID' => $userID, ':friendID' => $friendID]);
    $exists = $checkStmt->fetchColumn() > 0;

    if ($exists) {
        // Friendship already exists
        return "Une amitié existe déjà ou une demande est en attente.";
    } else {
        // Insert the new friendship request
        $insertStmt = $dbh->prepare("INSERT INTO userfriends (userID, friendUserID, status) VALUES (:userID, :friendID, 'pending')");
        $success = $insertStmt->execute([':userID' => $userID, ':friendID' => $friendID]);

        if ($success) {
            return "Demande d'ami envoyée avec succès.";
        } else {
            return "Échec de l'envoi de la demande d'ami.";
        }
    }
}

function isProfileOwner($dbh, $userID) {
    if (!isset($_SESSION['user_id'])) {
        // Si l'utilisateur n'est pas connecté, retourner false
        return false;
    }

    // Récupérer l'ID de l'utilisateur connecté depuis la session
    $sessionUserID = $_SESSION['user_id'];

    // Vérifier si l'ID de l'utilisateur de la session correspond à l'ID du profil visualisé
    if ($userID == $sessionUserID) {
        // L'utilisateur actuel est le propriétaire du profil
        return true;
    } else {
        // L'utilisateur actuel n'est pas le propriétaire du profil
        return false;
    }
}

// Fonction pour vérifier si les utilisateurs sont déjà amis
function isAlreadyFriend($dbh, $friendID, $userID) {
    $stmt = $dbh->prepare("SELECT * FROM userfriends WHERE (userID = ? AND friendUserID = ?) OR (userID = ? AND friendUserID = ?)");
    $stmt->execute([$friendID, $userID, $userID, $friendID]);
    return $stmt->fetch() ? true : false;
}
?>