<?php
// Inclusion des fichiers nécessaires
include '../managers/utilisateur-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Suppression de l'utilisateur
    deleteUser($dbh, $_POST['id']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}

// Obtention de l'utilisateur à supprimer
$user = getUserById($dbh, $_GET['id']);
?>
<div class="center">
    <form method="POST" class="container center">
        <input type="hidden" name="id" value="<?php echo $user['id_utilisateur']; ?>">
        <p>Êtes-vous sûr de vouloir supprimer <?php echo $user['nom_utilisateur']; ?>?</p>
        <button type="submit">Supprimer Utilisateur</button>
    </form>
</div>
<?php
