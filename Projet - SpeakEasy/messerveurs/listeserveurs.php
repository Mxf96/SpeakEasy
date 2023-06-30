<?php
require '../includes/inc-db-connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

function addMessage($dbh, $id_serveur, $id_utilisateur, $contenu_message)
{
    $date_message = date("Y-m-d");
    $heure_message = date("H:i:s");

    // Vérifier si le message existe déjà
    $stmt = $dbh->prepare("SELECT id_message FROM messages WHERE contenu_message = :contenu_message AND id_utilisateur = :id_utilisateur AND id_serveur = :id_serveur");
    $stmt->execute(['contenu_message' => $contenu_message, 'id_utilisateur' => $id_utilisateur, 'id_serveur' => $id_serveur]);
    $existingMessage = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingMessage) {
        $sql = "INSERT INTO messages (contenu_message, id_utilisateur, id_serveur, date_message, heure_message) VALUES (:contenu_message, :id_utilisateur, :id_serveur, :date_message, :heure_message)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':contenu_message', $contenu_message, PDO::PARAM_STR);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
        $stmt->bindParam(':date_message', $date_message, PDO::PARAM_STR);
        $stmt->bindParam(':heure_message', $heure_message, PDO::PARAM_STR);
        $stmt->execute();
    }
}

$id_serveur = $_GET['id_serveur'] ?? null;

if ($id_serveur === null) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenu_message = $_POST['contenu_message'] ?? null;
    if ($contenu_message !== null) {
        addMessage($dbh, $id_serveur, $_SESSION['user']['id_utilisateur'], $contenu_message);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

function getMessages($dbh, $id_serveur)
{
    $sql = "SELECT m.id_message, m.contenu_message, m.date_message, m.heure_message, u.nom_utilisateur, u.prenom_utilisateur 
        FROM messages m 
        JOIN associer a ON m.id_utilisateur = a.id_utilisateur
        JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur
        WHERE m.id_serveur = :id_serveur
        ORDER BY m.date_message DESC, m.heure_message DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getUsersByRole($dbh, $id_serveur, $id_role)
{
    $sql = "SELECT u.nom_utilisateur, u.prenom_utilisateur, r.libelle_role
    FROM associer a
    JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur
    JOIN role r ON u.id_role = r.id_role
    WHERE a.id_serveur = :id_serveur AND r.id_role = :id_role";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
    $stmt->bindParam(':id_role', $id_role, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getServerName($dbh, $id_serveur)
{
    $sql = "SELECT nom_serveur FROM serveurs WHERE id_serveur = :id_serveur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_serveur', $id_serveur, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['nom_serveur'];
}

$chatMessages = getMessages($dbh, $id_serveur);
$admins = getUsersByRole($dbh, $id_serveur, 1);
$teachers = getUsersByRole($dbh, $id_serveur, 2);
$users = getUsersByRole($dbh, $id_serveur, 3);

$serverName = getServerName($dbh, $id_serveur);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/stylesServeur.css">
    <script src="/logoutUser.js"></script>
</head>

<body>
    <div class="center">
        <img src='/assets/images/SpeakEasy.png' alt='Logo SpeakEasy' height="150" width="150" />
        <a class="logout" href="/logout.php">Déconnexion</a>
    </div>
    <br>
    <a class="back" href="/menu.php">Retour</a>
    <h1>Serveur : <?php echo htmlspecialchars($serverName); ?></h1>
    <div class="container">
        <div class="messages-container">
            <?php foreach ($chatMessages as $message) : ?>
                <div class="message">
                    <span class="message-content">
                        <span class="message-user"><?php echo htmlspecialchars($message['nom_utilisateur'] . ' ' . $message['prenom_utilisateur']); ?>:</span>
                        <span class="message-text"><?php echo htmlspecialchars($message['contenu_message']); ?></span>
                    </span>
                </div>
                <span class="message-datetime">
                        <span class="message-date"><?php echo htmlspecialchars($message['date_message']); ?></span>
                        <span class="message-time"><?php echo htmlspecialchars($message['heure_message']); ?></span>
                </span>
            <?php endforeach; ?>
            <div class="message-form-container">
                <form method="POST">
                    <textarea name="contenu_message" id="contenu_message" cols="50" rows="2"></textarea>
                    <button class="button" type="submit">Envoyer</button>
                </form>
            </div>
        </div>
        <div class="users-container">
            <div>
                <h2>Admins :</h2>
                <?php foreach ($admins as $admin) : ?>
                    <div class="user">
                        <?php echo htmlspecialchars($admin['nom_utilisateur']) . ' ' . htmlspecialchars($admin['prenom_utilisateur']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div>
                <h2>Enseignants :</h2>
                <?php foreach ($teachers as $teacher) : ?>
                    <div class="user">
                        <?php echo htmlspecialchars($teacher['nom_utilisateur']) . ' ' . htmlspecialchars($teacher['prenom_utilisateur']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div>
                <h2>Utilisateurs :</h2>
                <?php foreach ($users as $user) : ?>
                    <div class="user">
                        <?php echo htmlspecialchars($user['nom_utilisateur']) . ' ' . htmlspecialchars($user['prenom_utilisateur']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer">
            <p>&copy; 2023 SpeakEasy. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>
