<?php
// Inclusion du fichier de connexion à la base de données
include '../../includes/inc-db-connect.php';

// Définition des différentes fonctions pour gérer les utilisateur

function getAllUsers($dbh) {
    $sql = "SELECT * FROM utilisateur";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserById($dbh, $id) {
    $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}



function insertUser($dbh, $nom_utilisateur) {
    $sql = "INSERT INTO utilisateur (nom_utilisateur) VALUES (?)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$nom_utilisateur]);
}

function updateUser($dbh, $id, $nom_utilisateur) {
    $sql = "UPDATE utilisateur SET nom_utilisateur = ? WHERE id_utilisateur = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$nom_utilisateur, $id]);
}

function deleteUser($dbh, $id) {
    $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
}
?>
