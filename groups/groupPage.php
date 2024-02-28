<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/group-manager.php';
require_once '../managers/security-manager.php';

// Récupérer groupID et channelID depuis l'URL ou le POST
$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;
$channelID = isset($_GET['channelID']) ? intval($_GET['channelID']) : null;

// Vérifier si `channelID` est absent dans l'URL
$isGroupPageOnly = !isset($_GET['channelID']);

// Choisir la classe CSS en fonction de la présence de `channelID`
$channelsContainerClass = $isGroupPageOnly ? "channels-container-fullwidth" : "channels-container";

// Traitement de l'ajout d'un nouveau message dans un canal spécifique
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['channelID'])) {
    $message = $_POST['message'];
    $channelID = $_POST['channelID']; // Récupérer channelID depuis le formulaire
    $fromUserID = $_SESSION['user_id']; // Assurez-vous que cette valeur est correctement initialisée lors de la connexion de l'utilisateur
    insertGroupMessage($dbh, $fromUserID, $groupID, $channelID, $message);
    header("Location: groupPage.php?groupID=$groupID&channelID=$channelID"); // Rediriger pour éviter les soumissions de formulaire en double
    exit;
}

// Traitement de l'ajout d'un nouveau salon
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addChannel'])) {
    $channelName = $_POST['channelName'];
    $channelDescription = $_POST['channelDescription'] ?? '';
    createChannel($dbh, $groupID, $channelName, $channelDescription);
    header("Location: groupPage.php?groupID=$groupID");
    exit;
}

// Traitement de la mise à jour d'un canal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateChannel'], $_POST['channelIDToUpdate'])) {
    $channelIDToUpdate = $_POST['channelIDToUpdate'];
    $newChannelName = $_POST['newChannelName'];
    $newChannelDescription = $_POST['newChannelDescription'] ?? '';
    updateChannel($dbh, $channelIDToUpdate, $newChannelName, $newChannelDescription);
    header("Location: groupPage.php?groupID=$groupID");
    exit;
}

// Traitement de la suppression d'un canal
if (isset($_GET['deleteChannelID'])) {
    $deleteChannelID = $_GET['deleteChannelID'];
    deleteChannel($dbh, $deleteChannelID);
    header("Location: groupPage.php?groupID=$groupID");
    exit;
}

// Récupérer les informations nécessaires de la base de données
$groupInfo = getGroupInfo($dbh, $groupID);
$groupMembers = getGroupMembers($dbh, $groupID);
$groupName = $groupInfo['name'] ?? 'Groupe Inconnu';
$channels = getChannels($dbh, $groupID);
$groupMessages = $channelID ? getGroupMessagesByChannel($dbh, $groupID, $channelID) : [];

require_once '../includes/inc-top-group.php';
?>

<main class="group-page-container">
    <div class="group-conversations">
        <div class="<?= sanitize_input($channelsContainerClass) ?>">
            <div class="channel-header">
                <a href="http://speakeasy.local/groups/groupPage.php?groupID=<?= intval($_GET['groupID']) ?>">
                    <span class="groupName"><?= sanitize_input($groupName) ?></span>
                </a>
                <a href="#" id="addChannelButton" style="text-decoration: none;" class="add-channel-btn">+</a>
                <form id="addChannelForm" action="groupPage.php?groupID=<?= $groupID ?>" method="post" style="display: none;" class="form-container">
                    <input type="text" class="group-input" name="channelName" placeholder="Nom du salon"/>
                    <input type="text" class="group-input" name="channelDescription" placeholder="Description du salon" />
                    <button type="submit" class="group-button" name="addChannel">Créer le salon</button>
                </form>
            </div>
            <ul class="channels-list">
                <?php foreach ($channels as $channel) : ?>
                    <li>
                        <a href="groupPage.php?groupID=<?= sanitize_input($groupID) ?>&channelID=<?= sanitize_input($channel['channelID']) ?>" style="text-decoration: none; color: inherit;">
                            <?= sanitize_input($channel['name']) ?>
                        </a>
                        <!-- Bouton pour supprimer le canal -->
                        <a href="groupPage.php?groupID=<?= $groupID ?>&deleteChannelID=<?= $channel['channelID'] ?>" onclick="return confirm('Confirmez-vous la suppression de ce canal ?');" class="del-channel-btn ">X</a>
                        <!-- Bouton pour modifier le canal -->
                        <a href="#" onclick="showUpdateForm(<?= $channel['channelID'] ?>, '<?= addslashes(sanitize_input($channel['name'])) ?>', '<?= addslashes(sanitize_input($channel['description'])) ?>')" class="up-channel-btn">+</a>
                        <form id="updateChannelForm" style="display: none;">
                            <input type="hidden" id="updateChannelID" name="channelIDToUpdate" class="group-input">
                            <input type="text" id="newChannelName" name="newChannelName" placeholder="Nouveau nom du salon" class="group-input">
                            <input type="text" id="newChannelDescription" name="newChannelDescription" placeholder="Nouvelle description du salon" class="group-input">
                            <button type="submit" class="group-button">Mettre à jour</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Container pour les messages du salon sélectionné -->
        <div class="message-area">
            <?php if (!$channelID) : ?>
                <!-- Afficher le nom et la description du groupe uniquement si aucun channelID n'est spécifié -->
                <div class="group-bio">
                    <h2><?= sanitize_input($groupName) ?></h2>
                    <p><?= sanitize_input($groupInfo['description']) ?></p>
                </div>
            <?php endif; ?>
            <?php if ($channelID) : ?>
                <?php
                // Récupérer les informations du canal actuel
                $currentChannelInfo = null;
                foreach ($channels as $channel) {
                    if ($channel['channelID'] == $channelID) {
                        $currentChannelInfo = $channel;
                        break;
                    }
                }
                ?>
                <?php if ($currentChannelInfo) : ?>
                    <div class="channel-navbar">
                        <h2><?= sanitize_input($currentChannelInfo['name']) ?></h2>
                        <p><?= sanitize_input($currentChannelInfo['description']) ?></p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="message-display">
                <?php foreach ($groupMessages as $message) : ?>
                    <div class="message <?= ($message['fromUserID'] == $userID) ? 'sent' : 'received' ?>">
                        <strong><?= sanitize_input($message['userName']) ?>:</strong>
                        <?= sanitize_input($message['content']) ?>
                        <span class="message-date"><?= date('d/m/Y H:i', strtotime($message['dateTime'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($channelID) : ?>
                <form action="groupPage.php?groupID=<?= sanitize_input($groupID) ?>&channelID=<?= sanitize_input($channelID) ?>" method="post" class="form">
                    <input type="hidden" name="groupID" value="<?= sanitize_input($groupID) ?>">
                    <input name="message" class="message-input" />
                    <button type="submit" class="send-button">Envoyer</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="group-members">
        <h2>Membres du Groupe</h2>
        <ul>
            <?php foreach ($groupMembers as $member) : ?>
                <!-- Ensure that the link points to the profile.php page with the correct userID -->
                <li><a style="text-decoration: none; color: inherit;" href="/profile/profile.php?userID=<?= htmlspecialchars($member['userID']) ?>"><?= htmlspecialchars($member['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</main>

<script src="../scripts/addChannels.js"></script>
<?php require_once '../includes/inc-bottom.php' ?>