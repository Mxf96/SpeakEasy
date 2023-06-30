<?php
// Inclusion des fichiers nécessaires
include '../managers/serveur-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mise à jour du serveur
    updateServer($dbh, $_POST['id'], $_POST['nom_serveur']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}

// Obtention du serveur à modifier
$serveur = getServerById($dbh, $_GET['id']);
?>
<div class="center">
    <form method="POST" class="container center">
        <input type="hidden" name="id" value="<?php echo $serveur['id_serveur']; ?>">

        <label for="nom_serveur">Nom du Serveur:</label>
        <input type="text" id="nom_serveur" name="nom_serveur" value="<?php echo $serveur['nom_serveur']; ?>" required>

        <button type="submit">Enregistrer les Modifications</button>
    </form>
</div>

<?php
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php';
?>