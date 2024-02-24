<?php
require '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';
require '../managers/message-manager.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userID = $_SESSION['user_id'];

// Traitement de l'envoi d'un message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['toUserID'])) {
    $toUserID = $_POST['toUserID'];
    $message = $_POST['message'];
    insertMessage($dbh, $userID, $toUserID, $message);
    header("Location: private-messages.php?friendID=$toUserID");
    exit;
}

$selectedFriendID = $_GET['friendID'] ?? null;
$messages = [];
if ($selectedFriendID) {
    $messages = getMessagesBetweenUsers($dbh, $userID, $selectedFriendID);
}

require '../includes/inc-top-msg.php';
?>
<main class="main-container">
    <a href="/home/home.php" class="back">Retour</a>
    <section class="chat-section">
        <div class="message-display">
            <?php if ($selectedFriendID) : ?>
                <?php foreach ($messages as $message) : ?>
                    <div class="message <?= ($message['fromUserID'] == $userID) ? 'sent' : 'received' ?>">
                        <?= sanitize_input($message['content']) ?>
                        <span class="message-date"><?= date('d/m/Y H:i', strtotime($message['dateTime'])) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php if ($selectedFriendID) : ?>
        <div class="message-input-area">
            <form action="private-messages.php" method="post" class="message-form" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                <input type="hidden" name="toUserID" value="<?= $selectedFriendID ?>">
                <textarea name="message" required class="message-input"></textarea>
                <button type="submit" class="send-button">Envoyer</button>
            </form>
        </div>
    <?php endif; ?>
</main>
<?php require '../includes/inc-bottom.php'; ?>