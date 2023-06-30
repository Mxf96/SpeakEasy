<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../../includes/inc-db-connect.php';

if ($_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

include '../managers/utilisateur-manager.php';
include '../managers/role-manager.php';

$id_utilisateur = $_GET['id'] ?? null;
if ($id_utilisateur === null) {
    exit('ID de l\'utilisateur non fourni');
}
$utilisateur = getUserById($dbh, $id_utilisateur);
if ($utilisateur === null) {
    exit('Utilisateur non trouvé');
}

$roles = getAllRoles($dbh);

require '../../includes/inc-top.php';
?>

<div class="center">
    <h2>Modifier Utilisateur</h2>
    <form class="container center" action="update.php" method="post">
        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">
        <label for="nom_utilisateur">Nom de l'utilisateur</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" value="<?= $utilisateur['nom_utilisateur']; ?>" required>
        <label for="prenom_utilisateur">Prenom de l'utilisateur</label>
        <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" value="<?= $utilisateur['prenom_utilisateur']; ?>" required>
        <label for="email_utilisateur">E-mail de l'utilisateur</label>
        <input type="text" id="email_utilisateur" name="email_utilisateur" value="<?= $utilisateur['email_utilisateur']; ?>" required>
        <label for="id_role">Role de l'utilisateur</label>
        <select id="id_role" name="id_role" required>
            <?php foreach ($roles as $role) : ?>
                <option value="<?= $role['id_role'] ?>" <?php if ($role['id_role'] == $utilisateur['id_role']) : ?> selected <?php endif; ?>>
                    <?= htmlspecialchars($role['libelle_role'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Modifier">
    </form>
</div>

<?php require '../../includes/inc-bottom.php'; ?>
