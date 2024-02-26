<?php
require '../includes/inc-db-connect.php';
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

// Récupérer les informations nécessaires de la base de données
$groupInfo = getGroupInfo($dbh, $groupID);
$groupMembers = getGroupMembers($dbh, $groupID);
$groupName = $groupInfo['name'] ?? 'Groupe Inconnu';
$channels = getChannels($dbh, $groupID);
$groupMessages = $channelID ? getGroupMessagesByChannel($dbh, $groupID, $channelID) : [];

require '../includes/inc-top-group.php';
?>

<main class="group-page-container">
    <div class="group-conversations">
        <div class="<?= htmlspecialchars($channelsContainerClass) ?>">
            <div class="channel-header">
                <span><?= htmlspecialchars($groupName) ?></span>
                <a href="#" id="addChannelButton" style="text-decoration: none;" class="add-channel-btn">+</a>
                <form id="addChannelForm" action="groupPage.php?groupID=<?= $groupID ?>" method="post" style="display: none;" class="form-container">
                    <input type="text" class="group-input" name="channelName" placeholder="Nom du salon" required />
                    <input type="text" class="group-input" name="channelDescription" placeholder="Description du salon" />
                    <button type="submit" class="group-button" name="addChannel">Créer le salon</button>
                </form>
            </div>
            <ul class="channels-list">
                <li>
                    <a href="groupPage.php?groupID=<?= htmlspecialchars($groupID) ?>" style="text-decoration: none; color: inherit;">Accueil</a>
                </li>
                <?php foreach ($channels as $channel) : ?>
                    <li>
                        <a href="groupPage.php?groupID=<?= htmlspecialchars($groupID) ?>&channelID=<?= htmlspecialchars($channel['channelID']) ?>" style="text-decoration: none; color: inherit;">
                            <?= htmlspecialchars($channel['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Container pour les messages du salon sélectionné -->
        <div class="message-area">
            <?php if (!$channelID) : ?>
                <!-- Afficher le nom et la description du groupe uniquement si aucun channelID n'est spécifié -->
                <div>
                    <h2><?= htmlspecialchars($groupName) ?></h2>
                    <p><?= htmlspecialchars($groupInfo['description']) ?></p>
                </div>
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
                <form action="groupPage.php?groupID=<?= htmlspecialchars($groupID) ?>&channelID=<?= htmlspecialchars($channelID) ?>" method="post" class="form">
                    <input type="hidden" name="groupID" value="<?= htmlspecialchars($groupID) ?>">
                    <input name="message" required class="message-input" />
                    <button type="submit" class="send-button">Envoyer</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="group-members">
        <h2>Membres du Groupe</h2>
        <ul>
            <?php foreach ($groupMembers as $member) : ?>
                <li><?= sanitize_input($member['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</main>

<script src="/scripts/addChannels.js"></script>
<?php require '../includes/inc-bottom.php' ?>
