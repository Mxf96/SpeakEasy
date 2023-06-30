<?php
// Inclusion du fichier de connexion à la base de données
include '../../includes/inc-db-connect.php';

// Définition des différentes fonctions pour gérer les role

function getAllRoles($dbh) {
    $sql = "SELECT * FROM role";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getRoleById($dbh, $id) {
    $sql = "SELECT * FROM role WHERE id_role = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function insertRole($dbh, $libelle_role) {
    $sql = "INSERT INTO role (libelle_role) VALUES (?)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$libelle_role]);
}

function updateRole($dbh, $id, $libelle_role) {
    $sql = "UPDATE role SET libelle_role = ? WHERE id_role = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$libelle_role, $id]);
}

function deleteRole($dbh, $id) {
    $sql = "DELETE FROM role WHERE id_role = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);
}
?>
