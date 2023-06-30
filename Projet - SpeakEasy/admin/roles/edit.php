<?php
// Inclusion des fichiers nécessaires
include '../managers/role-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mise à jour du role
    updateRole($dbh, $_POST['id'], $_POST['libelle_role']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}

// Obtention du role à modifier
$role = getRoleById($dbh, $_GET['id']);
?>
<div class="center">
    <form method="POST" class="container center">
        <input type="hidden" name="id" value="<?php echo $role['id_role']; ?>">

        <label for="libelle_role">Nom du role:</label>
        <input type="text" id="libelle_role" name="libelle_role" value="<?php echo $role['libelle_role']; ?>" required>

        <button type="submit">Enregistrer les Modifications</button>
    </form>
</div>

<?php
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php';
?>