<?php
// Inclusion des fichiers nécessaires
include '../managers/role-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertion du nouveau role
    insertRole($dbh, $_POST['libelle_role']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}
?>
<div class="center">
    <form method="POST" class="container center">
        <form method="POST">
            <label for="libelle_role">Libelle du role</label>
            <input type="text" id="libelle_role" name="libelle_role" required>

            <button type="submit">Ajouter un role</button>
        </form>
</div>
<?php
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php';
?>