<?php
// Inclusion des fichiers nécessaires
include '../managers/serveur-manager.php';
include '../../includes/inc-top.php';

// Traitement du formulaire si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertion du nouveau serveur
    insertServer($dbh, $_POST['nom_serveur']);
    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}
?>
<div class="center">
    <form method="POST" class="container center">
        <form method="POST">
            <label for="nom_serveur">Nom du Serveur</label>
            <input type="text" id="nom_serveur" name="nom_serveur" required>

            <button type="submit">Ajouter Serveur</button>
        </form>
</div>
<?php
// Inclusion du fichier inc-bottom.php qui contient le pied de page de la page
include '../../includes/inc-bottom.php';
?>