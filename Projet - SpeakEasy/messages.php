<?php
require 'includes/inc-db-connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['ami'])) {
    header("Location: menu.php");
    exit;
}

$ami_id = $_GET['ami'];

function getConversation($dbh, $id_utilisateur, $id_ami)
{
    $sql = "SELECT * FROM messages WHERE (id_utilisateur = :id_utilisateur AND id_ami = :id_ami) OR (id_utilisateur = :id_ami AND id_ami = :id_utilisateur) ORDER BY date_message, heure_message ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_ami', $id_ami, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function addMessage($dbh, $id_utilisateur, $id_ami, $message, $piece_jointe = null)
{
    $sql = "INSERT INTO messages (id_utilisateur, id_ami, contenu_message, date_message, heure_message, piece_jointe_message) VALUES (:id_utilisateur, :id_ami, :message, CURDATE(), CURTIME(), :piece_jointe)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':id_ami', $id_ami, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':piece_jointe', $piece_jointe, PDO::PARAM_STR);
    $stmt->execute();
}

$conversation = getConversation($dbh, $_SESSION['user']['id_utilisateur'], $ami_id);

if (isset($_POST['envoyer'])) {
    $message = $_POST['message'];
    addMessage($dbh, $_SESSION['user']['id_utilisateur'], $ami_id, $message);
    header("Location: message.php?ami=$ami_id");
    exit;
}
require 'includes/inc-top.php';
?>
<a class="return" href="/menu.php">Retour</a>
<h1>Conversation avec l'ami</h1>
<a class="back" style="margin-left: 20%;" href="menu.php">Retour</a>
<div class="center">
    <ul class="container">
        <div class="center">
            <?php
            if (!empty($conversation)) {
                foreach ($conversation as $message) {
                    $expediteur = $message['id_utilisateur'] === $_SESSION['user']['id_utilisateur'] ? 'Vous' : 'Ami';
                    echo '<li>';
                    echo $expediteur . ': ' . htmlspecialchars($message['contenu_message']) . ' (' . htmlspecialchars($message['date_message']) . ' ' . htmlspecialchars($message['heure_message']) . ')';
                    if (!empty($message['piece_jointe_message'])) {
                        echo ' - <a href="' . htmlspecialchars($message['piece_jointe_message']) . '">Pièce jointe</a>';
                    }
                    echo '</li>';
                }
            } else {
                echo 'Aucun message dans cette conversation.';
            }
            ?>
        </div>    
    </ul>
    <div class="container">
        <div class="center">
            <form method="post" action="" enctype="multipart/form-data">
                <label for="message">Message :</label>
                <br>
                <input type="text" name="message" id="message">
                <br><br>
                <label for="piece_jointe">Pièce jointe :</label>
                <br>
                <input type="file" name="piece_jointe" id="piece_jointe">
                <br><br>
                <button type="submit" name="envoyer">Envoyer</button>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/inc-bottom.php'; ?>
