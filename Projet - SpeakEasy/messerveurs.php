<?php
require 'includes/inc-db-connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Rechercher un serveur par nom_serveur
function getServeurId($dbh, $nom_serveur)
{
    $sql = "SELECT id_serveur, nom_serveur FROM serveurs WHERE nom_serveur = :nom_serveur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nom_serveur', $nom_serveur, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return $result;
    } else {
        return false;
    }
}

// Ajouter un serveur à la liste des serveurs de l'utilisateur
function addServeur($dbh, $id_utilisateur, $id_serveur)
{
    $sql = "INSERT INTO associer (id_utilisateur, id_serveur) VALUES (:id_utilisateur, :id_serveur)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
    $stmt->execute();
}

function removeServeur($dbh, $id_utilisateur, $id_serveur)
{
    $sql = "DELETE FROM associer WHERE id_utilisateur = :id_utilisateur AND id_serveur = :id_serveur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
    $stmt->execute();
}

function isServerAlreadyAdded($dbh, $id_utilisateur, $id_serveur)
{
    $sql = "SELECT * FROM associer WHERE id_utilisateur = :id_utilisateur AND id_serveur = :id_serveur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result !== false;
}

function getUserServers($dbh, $id_utilisateur)
{
    $sql = "SELECT s.id_serveur, s.nom_serveur 
            FROM associer a
            JOIN serveurs s ON a.id_serveur = s.id_serveur
            WHERE a.id_utilisateur = :id_utilisateur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

require 'includes/inc-top.php';

$serveur = null;

// Si le formulaire est soumis
if (isset($_POST['rechercher'])) {
    $nom_serveur = $_POST['nom_serveur'];
    $serveur = getServeurId($dbh, $nom_serveur);
}

if (isset($_POST['ajouter'])) {
    $id_serveur = $_POST['id_serveur'];
    if (!isServerAlreadyAdded($dbh, $_SESSION['user']['id_utilisateur'], $id_serveur)) {
        // Ajoutez le serveur à la liste des serveurs de l'utilisateur
        addServeur($dbh, $_SESSION['user']['id_utilisateur'], $id_serveur);
    }
}

if (isset($_POST['supprimer'])) {
    $id_serveur = $_POST['id_serveur'];
    removeServeur($dbh, $_SESSION['user']['id_utilisateur'], $id_serveur);
    header("Location: messerveurs.php");
    exit;
}

$myServers = getUserServers($dbh, $_SESSION['user']['id_utilisateur']);
?>
<h1>Mes Serveurs</h1>
<a class="back" href="menu.php">Retour</a>
<a class="addServ" href="/newserveur.php">Créer un nouveau serveur</a>
<div class="center">
    <ul class="container">
        <div class="center">
            <?php
            if (!empty($myServers)) {
                foreach ($myServers as $server) {
                    echo '<li><a class="message-link center link-hover" href="messerveurs/listeserveurs.php?id_serveur=' . $server['id_serveur'] . '">' . htmlspecialchars($server['nom_serveur']) . '</a>';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id_serveur" value="' . htmlspecialchars($server['id_serveur']) . '">';
                    echo '<button class="remove" type="submit" name="supprimer">Retirer</button>';
                    echo '</form>';
                    echo '</li>';
                }
            } else {
                echo 'Vous n\'avez ajouté aucun serveur.';
            }
            ?>
        </div>
    </ul>
    <div class="container">
        <form method="post" action="">
            <label for="nom_serveur">Nom du serveur :</label>
            <input type="text" name="nom_serveur" id="nom_serveur">
            <button type="submit" name="rechercher">Rechercher</button>
        </form>
    </div>
    <ul class="container">
        <?php
        if ($serveur !== null) {
            if ($serveur !== false) {
                echo '<li>';
                echo htmlspecialchars($serveur['nom_serveur']);
                if (!isServerAlreadyAdded($dbh, $_SESSION['user']['id_utilisateur'], $serveur['id_serveur'])) {
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id_serveur" value="' . htmlspecialchars($serveur['id_serveur']) . '">';
                    echo '<button type="submit" name="ajouter">Ajouter</button>';
                    echo '</form>';
                }
                echo '</li>';
            } else {
                echo 'Aucun serveur trouvé avec le nom donné.';
            }
        }
        ?>
    </ul>
</div>

<?php require 'includes/inc-bottom.php'; ?>
