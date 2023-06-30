<?php
require 'includes/inc-db-connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

function createNewServer($dbh, $nom_serveur)
{
    $sql = "INSERT INTO serveurs (nom_serveur) VALUES (:nom_serveur)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nom_serveur', $nom_serveur, PDO::PARAM_STR);
    $stmt->execute();

    $id_serveur = $dbh->lastInsertId();

    if ($id_serveur) {
        // Ajouter le serveur à la liste des serveurs de l'utilisateur
        $sql = "INSERT INTO associer (id_utilisateur, id_serveur) VALUES (:id_utilisateur, :id_serveur)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_utilisateur', $_SESSION['user']['id_utilisateur'], PDO::PARAM_INT);
        $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
        $stmt->execute();
    }
}

if (isset($_POST['creer'])) {
    $nom_serveur = $_POST['nom_serveur'];
    createNewServer($dbh, $nom_serveur);
    header("Location: messerveurs.php");
    exit;
}

require 'includes/inc-top.php';
?>

<h1>Créer un nouveau serveur</h1>
<a class="back" href="messerveurs.php">Retour</a>

<div class="center">
    <div class="container">
        <form method="post" action="">
            <label for="nom_serveur">Nom du serveur :</label>
            <input type="text" name="nom_serveur" id="nom_serveur">
            <button type="submit" name="creer">Créer</button>
        </form>
    </div>
</div>

<?php require 'includes/inc-bottom.php'; ?>
