<?php

require 'includes/inc-db-connect.php';
session_start();
unset($_SESSION['error']);

if (!empty($_POST['submit'])) {

    $errors = [];

    // On nettoie les données utilisateur
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = htmlspecialchars($_POST['mdp']);
    $confirm_mdp = htmlspecialchars($_POST['confirm_mdp']);

    // On vérifie tous les champs du formulaire

    if (empty($nom))
        $errors['nom'] = "Votre nom est obligatoire.";

    if (empty($prenom))
        $errors['prenom'] = "Votre prénom est obligatoire.";

    if (empty($email))
        $errors['email'] = "Votre email est obligatoire.";

    if (empty($mdp))
        $errors['mdp'] = "Le mot de passe est obligatoire.";

    if (empty($confirm_mdp))
        $errors['confirm_mdp'] = "Le mot de passe est obligatoire.";

    if ($mdp != $confirm_mdp)
        $errors['confirm_mdp'] = "Les mots de passe ne sont pas identiques.";

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['values'] = $_POST;
        header("Location: register.php");
        die;
    }


    // On vérifie que l'utilisateur n'existe pas
    $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = :email";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'email' => $email
    ]);

    if ($query->rowCount() > 0) {
        $_SESSION['error'] = "Un utilisateur existe déjà avec cette adresse email.";
        header("Location: register.php");
        die;
    }

    // On insère l'utilisateur en BDD
    $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email_utilisateur, mdp_utilisateur) 
    VALUES (:nom_utilisateur, :prenom_utilisateur, :email_utilisateur, :mdp_utilisateur)";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'nom_utilisateur' => $nom,
        'prenom_utilisateur' => $prenom,
        'email_utilisateur' => $email,
        'mdp_utilisateur' => password_hash($mdp, PASSWORD_DEFAULT)
    ]);

    if ($res) {
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Une erreur est survenue.";
        header("Location: register.php");
        die;
    }
}
