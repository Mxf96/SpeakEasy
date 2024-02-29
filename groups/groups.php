<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/profile-manager.php';
require_once '../managers/group-manager.php';
require_once '../managers/security-manager.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$message = '';

// Check if a create group request was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createGroup'])) {
    if (!empty($_POST['groupName']) && !empty($_POST['groupDescription'])) {
        $groupName = trim($_POST['groupName']);
        $groupDescription = trim($_POST['groupDescription']);
        $message = createGroup($dbh, $groupName, $groupDescription, $userID);
    } else {
        $message = "Veuillez remplir tous les champs pour créer un groupe.";
    }
}

// Check if a join group request was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['joinGroup'])) {
    if (!empty($_POST['groupName'])) {
        $groupName = trim($_POST['groupName']);
        $message = joinGroup($dbh, $groupName, $userID);
    } else {
        $message = "Veuillez fournir le nom du groupe pour rejoindre.";
    }
}

// Check if a delete group request was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteGroup'])) {
    if (!empty($_POST['groupID'])) {
        $groupID = trim($_POST['groupID']);
        $message = deleteGroup($dbh, $groupID, $userID);
    } else {
        $message = "Veuillez fournir l'ID du groupe pour supprimer.";
    }
}

$groupsList = getUserGroups($dbh, $userID);

require_once '../includes/inc-top-home.php';
?>
<main class="groups-page">
    <a href="/home/home.php" class="back">Retour</a>
    <section class="group-list">
        <h2>Vos Groupes</h2>
        <?php if (!empty($groupsList)) : ?>
            <ul>
                <?php foreach ($groupsList as $group) : ?>
                    <li class="groups-item">
                        <a class="a" href="/groups/groupPage.php?groupID=<?= $group['groupID']; ?>"><?= sanitize_input($group['name']); ?></a>
                        <?php if ($userID == $group['creatorID']) : ?> <!-- Vérifie si l'utilisateur connecté est le créateur du groupe -->
                            <form action="groups.php" method="post" style="display: inline;">
                                <input type="hidden" name="groupID" value="<?= $group['groupID']; ?>">
                                <button type="submit" name="deleteGroup" class="remove-group-btn" onclick="return confirm('Confirmez-vous la suppression de ce groupe ?');">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>C'est désert ici. Pourquoi ne pas rejoindre ou créer un groupe ?</p>
        <?php endif; ?>

    </section>
    <section class="group-actions">
        <div>
            <a id="createGroupBtn" class="action-btn">Créer un groupe</a>
            <a id="joinGroupBtn" class="action-btn">Rejoindre un groupe</a>
        </div>
        <div class="form-container" id="createGroupForm" style="display:none;">
            <form action="groups.php" method="post">
                <input type="text" name="groupName" placeholder="Nom du groupe" class="group-input">
                <input type="text" name="groupDescription" placeholder="Description du groupe">
                <button type="submit" class="group-button" name="createGroup">Créer</button>
            </form>
        </div>
        <div class="form-container2" id="joinGroupForm" style="display:none;">
            <form action="groups.php" method="post">
                <input type="text" name="groupName" placeholder="Nom du groupe à rejoindre" class="group-input">
                <button type="submit" class="group-button" name="joinGroup">Rejoindre</button>
            </form>
        </div>
    </section>
    <?php if ($message) : ?>
        <p><?= $message; ?></p>
    <?php endif; ?>
</main>
<script src="../scripts/addGroups.js"></script>
<?php require_once '../includes/inc-bottom.php'; ?>