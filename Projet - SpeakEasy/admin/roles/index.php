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
include '../managers/role-manager.php';

$roles = getAllRoles($dbh);

require '../../includes/inc-top.php';
?>

<div class="center">
    <div class="container center">
        <h2>Roles</h2>
        <a class="add" href="new.php">Ajouter un Role</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libelle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role) : ?>
                    <tr>
                        <td><?= $role['id_role'] ?></td>
                        <td><?= $role['libelle_role'] ?></td>
                        <td>
                            <a class="edit" href="/admin/roles/edit.php?id=<?php echo  $role['id_role'] ?>">Modifier</a>
                            <a class="delete" href="/admin/roles/delete.php?id=<?php echo  $role['id_role'] ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require '../../includes/inc-bottom.php'; ?>