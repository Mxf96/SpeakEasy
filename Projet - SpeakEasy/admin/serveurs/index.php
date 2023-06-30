<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../../includes/inc-db-connect.php';

if ($_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

// Inclusion du fichier utilisateur-manager.php pour gérer les utilisateurs
include '../managers/serveur-manager.php';

$serveurs = getAllServers($dbh);

require '../../includes/inc-top.php';
?>

<div class="center">
    <div class="container center">
        <h2>Serveurs</h2>
        <a class="add" href="new.php">Ajouter un Serveur</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($serveurs as $serveur) : ?>
                    <tr>
                        <td><?= $serveur['id_serveur'] ?></td>
                        <td><?= $serveur['nom_serveur'] ?></td>
                        <td>
                            <a class="edit" href="/admin/serveurs/edit.php?id=<?php echo  $serveur['id_serveur'] ?>">Modifier</a>
                            <a class="delete" href="/admin/serveurs/delete.php?id=<?php echo  $serveur['id_serveur'] ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require '../../includes/inc-bottom.php'; ?>