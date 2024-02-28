<?php
require '../includes/inc-db-connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "Utilisateur non connecté";
    exit;
}

if (isset($_GET['userID'])) {
    $userID = intval($_GET['userID']);
    $friendID = intval($_SESSION['user_id']);

    // Implémentez la fonction isAlreadyFriend pour vérifier l'existence d'une amitié
    if (!isAlreadyFriend($dbh, $friendID, $userID)) {
        try {
            // Insertion conforme à la structure de la table userfriends
            $stmt = $dbh->prepare("INSERT INTO userfriends (userID, friendUserID, status) VALUES (?, ?, 'pending'), (?, ?, 'pending')");
            $stmt->execute([$friendID, $userID, $userID, $friendID]);
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