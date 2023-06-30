<?php
session_start();
require 'includes/inc-db-connect.php';

if (!empty($_POST['submit'])) {
    $errors = [];

    if (empty($_POST['email'])) {
        $errors['email'] = "Saisissez votre email.";
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "Saisissez votre mot de passe.";
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php");
        exit;
    }

    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $sql = "SELECT u.*, r.libelle_role FROM utilisateur u JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur JOIN role r ON ur.id_role = r.id_role WHERE email_utilisateur = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['mdp_utilisateur'])) {
            $_SESSION['user'] = [
                'id_utilisateur' => $user['id_utilisateur'],
                'nom_utilisateur' => $user['nom_utilisateur'],
                'prenom_utilisateur' => $user['prenom_utilisateur'],
                'role' => $user['libelle_role'],
            ];

            if ($user['libelle_role'] === 'Admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: menu.php");
            }
            exit;
        } else {
            $_SESSION['error'] = "Identifiants invalides.";
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Identifiants invalides.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
