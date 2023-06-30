<?php
// Inclusion du fichier de connexion à la base de données
include '../../includes/inc-db-connect.php';

// Définition des différentes fonctions pour gérer les serveurs

function getAllServers($dbh) {
    $sql = "SELECT * FROM serveurs";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getServerById($dbh, $id) {
    $sql = "SELECT * FROM serveurs WHERE id_serveur = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function insertServer($dbh, $nom_serveur) {
    $sql = "INSERT INTO Serveur (nom_serveur) VALUES (?)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$nom_serveur]);
}

function updateServer($dbh, $id, $nom_serveur) {
    $sql = "UPDATE serveurs SET nom_serveur = ? WHERE id_serveur = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$nom_serveur, $id]);
}

function deleteServer($dbh, $id) {
    $sql = "DELETE FROM serveurs WHERE id_serveur = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
}
?>
