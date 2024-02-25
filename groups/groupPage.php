<?php
require '../includes/inc-db-connect.php'; // Assurez-vous que le chemin d'accès est correct
require_once '../managers/group-manager.php';
require_once '../managers/security-manager.php';

// Récupérer groupID depuis l'URL
$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;

require '../includes/inc-top-group.php'
?>


<?php require '../includes/inc-bottom.php'?>