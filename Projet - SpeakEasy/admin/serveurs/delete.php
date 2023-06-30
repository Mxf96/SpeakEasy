<?php
// Inclusion des fichiers nécessaires
include '../managers/serveur-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Suppression du serveur
    deleteServer($dbh, $_POST['id']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}

// Obtention du serveur à supprimer
$serveur = getServerById($dbh, $_GET['id']);
?>
<div class="center">
<form method="POST" class="container center">
    <input type="hidden" name="id" value="<?php echo $serveur['id_serveur']; ?>">
    <p>Êtes-vous sûr de vouloir supprimer <?php echo $serveur['nom_serveur']; ?>?</p>
    <button type="submit">Supprimer le Serveur</button>
</form>
</div>
<?php 
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php'; 
?>
