<?php
require '../includes/inc-db-connect.php'; 
require '../managers/friend-manager.php';


if (!isset($_SESSION['user_id']) || !isset($_POST['friendUserID']) || $_POST['action'] !== 'accept') {
    header('Location: ../log/login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$friendUserID = $_POST['friendUserID'];

acceptFriendRequest($dbh, $userID, $friendUserID);

$_SESSION['message'] = "Demande d'amitié acceptée.";
header('Location: friends.php');
exit;