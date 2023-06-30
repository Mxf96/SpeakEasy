<?php
// Inclusion des fichiers nécessaires
include '../managers/role-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Suppression du role
    deleteRole($dbh, $_POST['id']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}

// Obtention du role à supprimer
$role = getRoleById($dbh, $_GET['id']);
?>
<div class="center">
<form method="POST" class="container center">
    <input type="hidden" name="id" value="<?php echo $role['id_role']; ?>">
    <p>Êtes-vous sûr de vouloir supprimer <?php echo $role['libelle_role']; ?>?</p>
    <button type="submit">Supprimer le role</button>
</form>
</div>
<?php 
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php'; 
?>
