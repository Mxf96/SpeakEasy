<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/profile-manager.php';
require_once '../managers/friend-manager.php';


if (!isset($_SESSION['user_id'])) {
    echo "Utilisateur non connecté";
    exit;
}

// Utilisez $_POST au lieu de $_GET pour récupérer l'userID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {
    $userID = intval($_POST['userID']);
    $friendID = intval($_SESSION['user_id']);

    // Assurez-vous que la fonction isAlreadyFriend est bien définie et utilisable ici
    if (!isAlreadyFriend($dbh, $friendID, $userID)) {
        try {
            // L'insertion doit être ajustée pour ne pas insérer deux fois pour chaque paire d'amis
            $stmt = $dbh->prepare("INSERT INTO userfriends (userID, friendUserID, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$friendID, $userID]);
            echo "Demande d'ami envoyée avec succès";
        } catch (PDOException $e) {
            echo "Erreur lors de l'envoi de la demande d'ami: " . $e->getMessage();
        }
    } else {
        echo "Vous êtes déjà amis ou une demande est en cours";
    }
} else {
    echo "ID d'utilisateur non spécifié";
}
?>