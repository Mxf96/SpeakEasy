<?php
require 'includes/inc-db-connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

function getUtilisateurId($dbh, $nom_utilisateur, $prenom_utilisateur)
{
    $sql = "SELECT id_utilisateur, nom_utilisateur, prenom_utilisateur FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur AND prenom_utilisateur = :prenom_utilisateur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur, PDO::PARAM_STR);
    $stmt->bindParam(':prenom_utilisateur', $prenom_utilisateur, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return $result;
    } else {
        return false;
    }
}

function addAmi($dbh, $id_utilisateur, $id_ami)
{
    $sql = "INSERT INTO amis (id_utilisateur, id_ami) VALUES (:id_utilisateur, :id_ami)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_ami', $id_ami, PDO::PARAM_INT);
    $stmt->execute();
}

function removeAmi($dbh, $id_utilisateur, $id_ami)
{
    $sql = "DELETE FROM amis WHERE id_utilisateur = :id_utilisateur AND id_ami = :id_ami";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_ami', $id_ami, PDO::PARAM_INT);
    $stmt->execute();
}

function isFriendAlreadyAdded($dbh, $id_utilisateur, $id_ami)
{
    $sql = "SELECT * FROM amis WHERE id_utilisateur = :id_utilisateur AND id_ami = :id_ami";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_ami', $id_ami, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result !== false;
}

function getUserFriends($dbh, $id_utilisateur)
{
    $sql = "SELECT utilisateur.id_utilisateur, utilisateur.nom_utilisateur, utilisateur.prenom_utilisateur 
            FROM amis 
            JOIN utilisateur ON amis.id_ami = utilisateur.id_utilisateur
            WHERE amis.id_utilisateur = :id_utilisateur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

require 'includes/inc-top.php';

$utilisateur = null;

if (isset($_POST['rechercher'])) {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $prenom_utilisateur = $_POST['prenom_utilisateur'];
    $utilisateur = getUtilisateurId($dbh, $nom_utilisateur, $prenom_utilisateur);
}

if (isset($_POST['ajouter'])) {
    $id_ami = $_POST['id_ami'];
    if (!isFriendAlreadyAdded($dbh, $_SESSION['user']['id_utilisateur'], $id_ami)) {
        addAmi($dbh, $_SESSION['user']['id_utilisateur'], $id_ami);
    }
}

if (isset($_POST['supprimer'])) {
    $id_ami = $_POST['id_ami'];
    removeAmi($dbh, $_SESSION['user']['id_utilisateur'], $id_ami);
}

$myFriends = getUserFriends($dbh, $_SESSION['user']['id_utilisateur']);
?>
<a class="return" href="/menu.php">Retour</a>
<h1>Mes Amis</h1>
<a class="back" href="menu.php">Retour</a>
<div class="center">
    <ul class="container">
        <div class="center">
            <?php
            if (!empty($myFriends)) {
                foreach ($myFriends as $friend) {
                    echo '<li><a class="message-link" href="profile.php?id_utilisateur=' . $friend['id_utilisateur'] . '">' . htmlspecialchars($friend['prenom_utilisateur']) . ' ' . htmlspecialchars($friend['nom_utilisateur']) . '</a>';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id_ami" value="' . htmlspecialchars($friend['id_utilisateur']) . '">';
                    echo '<button class="remove" type="submit" name="supprimer">Retirer</button>';
                    echo '</form>';
                    echo '</li>';
                }
            } else {
                echo 'Vous n\'avez ajouté aucun ami.';
            }
            ?>
        </div>    
    </ul>
<div class="container">
    <div class="center">
        <form method="post" action="">
            <label for="nom_utilisateur">Nom de l'utilisateur :</label>
            <br>
            <input type="text" name="nom_utilisateur" id="nom_utilisateur">
            <br>
            <label for="prenom_utilisateur">Prénom de l'utilisateur :</label>
            <br>
            <input type="text" name="prenom_utilisateur" id="prenom_utilisateur">
            <br><br>
            <button type="submit" name="rechercher">Rechercher</button>
        </form>
    </div>
</div>
<ul class="container">
    <div class="center">
        <?php
        if ($utilisateur !== null) {
            if ($utilisateur !== false) {
                echo '<li>';
                echo htmlspecialchars($utilisateur['prenom_utilisateur']) . ' ' . htmlspecialchars($utilisateur['nom_utilisateur']);
                if (!isFriendAlreadyAdded($dbh, $_SESSION['user']['id_utilisateur'], $utilisateur['id_utilisateur'])) {
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id_ami" value="' . htmlspecialchars($utilisateur['id_utilisateur']) . '">';
                    echo '<button type="submit" name="ajouter">Ajouter</button>';
                    echo '</form>';
                }
                echo '</li>';
            } else {
                echo 'Aucun utilisateur trouvé avec le nom et le prénom donnés.';
            }
        }
        ?>
    </div>
</ul>
</div>
<?php require 'includes/inc-bottom.php'; ?>
