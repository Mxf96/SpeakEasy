<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/security-manager.php';
require_once '../managers/message-manager.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userID = $_SESSION['user_id'];

// Traitement de l'envoi d'un message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toUserID'])) {
    $toUserID = $_POST['toUserID'];
    $message = $_POST['message'] ?? '';
    $file = $_FILES['messageFile'] ?? null; // Adjusted to reflect the input field's name

    insertMessage($dbh, $userID, $toUserID, $message, $file);
    header("Location: private-messages.php?friendID=$toUserID");
    exit;
}

$selectedFriendID = $_GET['friendID'] ?? null;
$messages = [];
if ($selectedFriendID) {
    $messages = getMessagesBetweenUsers($dbh, $userID, $selectedFriendID);
}

require_once '../includes/inc-top-msg.php';
?>
<main class="main-container">
    <a href="/home/home.php" class="back">Retour</a>
    <section class="chat-section">
        <div class="message-display">
            <?php if ($selectedFriendID) : ?>
                <?php foreach ($messages as $message) : ?>
                    <div class="message <?= ($message['fromUserID'] == $userID) ? 'sent' : 'received' ?>">
                        <?php if (!empty($message['filePath'])) : ?>
                            <div class="message-file">
                                <?php
                                // Check if the file is an image
                                $fileExtension = strtolower(pathinfo($message['filePath'], PATHINFO_EXTENSION));
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                if (in_array($fileExtension, $imageExtensions)) : ?>
                                    <img src="<?= htmlspecialchars($message['filePath']) ?>" alt="Message image" style="max-width: 200px; display: block;">
                                <?php else : ?>
                                    <!-- Display a link for non-image files -->
                                    <a href="<?= htmlspecialchars($message['filePath']) ?>" download>Download <?= htmlspecialchars(basename($message['filePath'])) ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="message-text">
                            <?= htmlspecialchars($message['content']) ?>
                        </div>
                        <span class="message-date"><?= date('d/m/Y H:i', strtotime($message['dateTime'])) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php if ($selectedFriendID) : ?>
        <div class="message-input-area">
            <div id="filePreview" class="file-preview" style="display: none;"></div>
            <form action="private-messages.php" method="post" enctype="multipart/form-data" class="message-form" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                <input type="hidden" name="toUserID" value="<?= $selectedFriendID ?>">
                <label for="messageFile" class="image-upload-label">+</label>
                <input type="file" name="messageFile" accept="image/*, .txt, .html, .css, application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.presentationml.presentation" id="messageFile" style="display: none;">
                <input name="message" class="message-input"></input>
                <button type="submit" class="send-button" id="sendButton">Envoyer</button>
            </form>
        </div>
    <?php endif; ?>
</main>
<script src="../scripts/messages.js"></script>
<?php require_once '../includes/inc-bottom.php'; ?>