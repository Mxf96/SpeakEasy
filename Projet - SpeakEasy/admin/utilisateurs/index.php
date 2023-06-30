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
include '../managers/utilisateur-manager.php';
include '../managers/role-manager.php';

$utilisateurs = getAllUsers($dbh);
$roles = getAllRoles($dbh);

require '../../includes/inc-top.php';
?>

<div class="center">
    <div class="container center">
        <h2>Utilisateurs</h2>
        <a class="add" href="new.php">Ajouter un utilisateur</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>E-mail</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur) : ?>
                    <tr>
                        <td><?= htmlspecialchars($utilisateur['id_utilisateur'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($utilisateur['nom_utilisateur'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($utilisateur['prenom_utilisateur'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($utilisateur['email_utilisateur'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($roles[$utilisateur['id_role'] - 1]['libelle_role'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a class="edit" href="edit.php?id=<?php echo htmlspecialchars($utilisateur['id_utilisateur'], ENT_QUOTES, 'UTF-8') ?>">Modifier</a>
                            <a class="delete" href="delete.php?id=<?php echo htmlspecialchars($utilisateur['id_utilisateur'], ENT_QUOTES, 'UTF-8') ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require '../../includes/inc-bottom.php'; ?>
