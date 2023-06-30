<?php
// Inclusion des fichiers nécessaires
include '../managers/utilisateur-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertion du nouvel utilisateur
    insertUser($dbh, $_POST['nom_utilisateur']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}
?>
<div class="center">
    <form method="POST" class="container center">
        <label for="nom_utilisateur">Nom de l'utilisateur</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>

        <button type="submit">Ajouter Utilisateur</button>
    </form>
</div>
<?php
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php';
?>
