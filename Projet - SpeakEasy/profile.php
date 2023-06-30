<?php
require 'includes/inc-db-connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$id_utilisateur = $_GET['id_utilisateur'] ?? null;

if ($id_utilisateur === null) {
    header("Location: index.php");
    exit;
}

// Récupère les informations de l'utilisateur
function getUserInfo($dbh, $id_utilisateur)
{
    $sql = "SELECT utilisateur.nom_utilisateur, utilisateur.prenom_utilisateur, utilisateur.id_role, role.libelle_role 
            FROM utilisateur 
            JOIN role ON utilisateur.id_role = role.id_role
            WHERE utilisateur.id_utilisateur = :id_utilisateur";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}


$userInfo = getUserInfo($dbh, $id_utilisateur);
require 'includes/inc-top.php';
?>

<body>
<a class="back" href="/menu.php">Retour</a>
<div class="center">
    <div class="container">
        <h1>Profile de <?php echo htmlspecialchars($userInfo['nom_utilisateur']) . ' ' . htmlspecialchars($userInfo['prenom_utilisateur']); ?></h1>
        <p>Rôle : <?php echo htmlspecialchars($userInfo['libelle_role']); ?></p>
        <a class="message-link" href="messages.php?id_utilisateur=<?php echo $id_utilisateur; ?>">
            <?php echo htmlspecialchars($userInfo['prenom_utilisateur']) . ' ' . htmlspecialchars($userInfo['nom_utilisateur']); ?>
        </a>
    </div>
</div>
<? require 'includes/inc-bottom.php'; ?>