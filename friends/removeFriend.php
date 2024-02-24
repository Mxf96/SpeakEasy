<?php
require '../includes/inc-db-connect.php';
require '../managers/friend-manager.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['friendUserID'])) {
    header('Location: ../log/login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$friendUserID = $_POST['friendUserID'];

// Appeler la fonction de suppression
$message = removeFriend($dbh, $userID, $friendUserID);

// Stocker le message dans la session pour l'afficher plus tard
$_SESSION['message'] = $message;

// Rediriger vers la page des amis
header('Location: friends.php');
exit;
?>
