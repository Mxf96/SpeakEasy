<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/profile-manager.php';
require_once '../managers/security-manager.php';
require_once '../managers/home-manager.php';
require_once '../managers/message-manager.php';
require_once '../managers/friend-manager.php';

// Redirige vers la page de connexion si aucun utilisateur n'est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../log/login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$userName = getUserName($dbh, $userID);


// Supposons que vous avez des fonctions pour récupérer ces informations
$notifications = getRecentNotifications($userID);
$unreadMessages = getUnreadMessages($userID);
$groupSuggestions = getGroupSuggestions($userID);

// Supposons que $userID contient l'ID de l'utilisateur connecté
$unreadMessagesByUser = getUnreadMessagesByUser($dbh, $userID);
$lastConversations = getLastConversations($dbh, $userID);

// Supposons que $dbh est votre objet de connexion à la base de données et $userID l'ID de l'utilisateur connecté
$friendSuggestions = getFriendSuggestions($dbh, $userID);

require '../includes/inc-top-home.php';
?>
<main class="main-layout">
    <section class="friend-suggestions">
        <h2>Suggestions d'amis</h2>
        <?php if (!empty($friendSuggestions)) : ?>
            <div class="friend-suggestions-container">
                <?php foreach ($friendSuggestions as $suggestion) : ?>
                    <div class="friend-suggestion-card">
                        <a href="/profile/profile.php?userID=<?= htmlspecialchars($suggestion['userID']) ?>">
                            <img src="<?= htmlspecialchars($suggestion['profile_photo']); ?>" alt="Profile Picture" class="suggestion-profile-photo">
                        </a>
                        <p>
                            <a href="/profile/profile.php?userID=<?= htmlspecialchars($suggestion['userID']) ?>"><?= htmlspecialchars($suggestion['name']) ?></a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Pas de suggestions d'amis pour le moment.</p>
        <?php endif; ?>
    </section>

    <div class="unread-messages">
        <h2 class="h2">Bienvenue, <?= htmlspecialchars($userName); ?>!</h2>
        <?php if (!empty($unreadMessagesByUser)) : ?>
            <h3>Vous avez des messages non lus de :</h3>
            <ul>
                <?php foreach ($unreadMessagesByUser as $messageInfo) : ?>
                    <li><?= htmlspecialchars($messageInfo['name']) ?>: <?= $messageInfo['count'] ?> messages non lu(s)</li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Vous n'avez aucun message non lu.</p>
        <?php endif; ?>
    </div>

    <div class="dashboard">
        <section class="activities">
            <h2 class="h2">Activités récentes</h2>
            <?php foreach ($lastConversations as $conversation) : ?>
                <div class="conversation">
                    <p>
                        <strong>
                            <a href="/messages/private-messages.php?friendID=<?= htmlspecialchars($conversation['otherUserID']) ?>"><?= htmlspecialchars($conversation['userName']) ?></a>
                            </a>
                        </strong>
                    </p>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</main>
<?php
require '../includes/inc-bottom.php';
?>