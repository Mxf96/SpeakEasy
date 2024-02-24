<?php
require_once '../managers/profile-manager.php';
require '../managers/friend-manager.php';
require_once '../managers/security-manager.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['friendName'])) {
    $message = addFriend($dbh, $userID, $_POST['friendName']);
}

$friendsList = getFriendsList($dbh, $userID);
$pendingRequests = getPendingFriendRequests($dbh, $userID);

require '../includes/inc-top-home.php';
?>
<main class="friends-page">
    <a href="/home/home.php" class="back">Retour</a>
    <section class="friends-list">
        <h2>Liste des amis</h2>
        <?php if (!empty($friendsList)) : ?>
            <ul>
                <?php foreach ($friendsList as $friend) : ?>
                    <li class="friend-item">
                        <span><?= sanitize_input($friend['name']); ?></span>
                        <a href="/messages/private-messages.php?friendID=<?= $friend['userID']; ?>" class="message-btn">Discuter</a>
                        <form action="removeFriend.php" method="post" class="remove-friend-form">
                            <input type="hidden" name="friendUserID" value="<?= $friend['userID']; ?>">
                            <button type="submit" class="remove-btn">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>C'est désert ici. Pourquoi ne pas ajouter quelques amis ?</p>
        <?php endif; ?>
    </section>
    <section class="friends-actions">
        <div>
            <a id="addFrienda" class="addFriends">Ajouter un(e) ami(e)</a>
            <a id="showInvitationsButton" class="addFriends">Demandes d'invitation</a>
        </div>
        <div id="addFriendForm" style="display:none;">
            <form action="friends.php" method="post">
                <input type="text" name="friendName" placeholder="Nom de l'ami(e)">
                <button type="submit">Ajouter</button>
            </form>
        </div>
        <div id="invitationsContainer" class="invitations-container" style="display:none;">
            <?php if (!empty($pendingRequests)) : ?>
                <h3>Demandes d'amitié en attente</h3>
                <div class="invitation-list">
                    <?php foreach ($pendingRequests as $request) : ?>
                        <div class="invitation-item">
                            <span class="friend-name"><?= sanitize_input($request['name']); ?></span>
                            <div class="invitation-actions">
                                <form action="acceptFriendRequest.php" method="post" class="invitation-form">
                                    <input type="hidden" name="friendUserID" value="<?= $request['userID']; ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="accept-btn">Accepter</button>
                                </form>
                                <form action="declineFriendRequest.php" method="post" class="invitation-form">
                                    <input type="hidden" name="friendUserID" value="<?= $request['userID']; ?>">
                                    <input type="hidden" name="action" value="decline">
                                    <button type="submit" class="decline-btn">Décliner</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p>Aucune demande d'amitié en attente.</p>
            <?php endif; ?>
        </div>
        <?php if (!empty($message)) : ?>
            <p><?= sanitize_input($message); ?></p>
        <?php endif; ?>
    </section>
</main>
<script src="/scripts/addFriend.js"></script>
<?php require '../includes/inc-bottom.php'; ?>