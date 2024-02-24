<?php
// Start the session to access session variables
session_start();

// Include the database connection and group manager for database operations
require '../includes/inc-db-connect.php';
require '../managers/group-manager.php';

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: ../log/login.php');
    exit;
}

// Check if the groupID is set in the POST request
if (isset($_POST['groupID'])) {
    $userID = $_SESSION['user_id'];
    $groupID = $_POST['groupID'];

    // Call the function to remove the user from the group
    $resultMessage = removeUserFromGroup($dbh, $userID, $groupID);

    // Set a session message to display the result
    $_SESSION['message'] = $resultMessage;

    // Redirect back to the groups page or wherever appropriate
    header('Location: groups.php');
    exit;
} else {
    // If the groupID isn't set in the POST request, redirect with an error message
    $_SESSION['error'] = "Missing group ID.";
    header('Location: groups.php');
    exit;
}

// Function to remove a user from a group
function removeUserFromGroup($dbh, $userID, $groupID) {
    try {
        // Prepare the SQL statement to delete the user's membership from the GroupMemberships table
        $stmt = $dbh->prepare("DELETE FROM GroupMemberships WHERE userID = :userID AND groupID = :groupID");
        $stmt->execute([':userID' => $userID, ':groupID' => $groupID]);

        // Check if any row was actually deleted
        if ($stmt->rowCount() > 0) {
            return "Vous avez quitté le groupe avec succès.";
        } else {
            return "Vous n'êtes pas membre de ce groupe ou le groupe n'existe pas.";
        }
    } catch (PDOException $e) {
        // Return a generic error message in case of any exception
        return "Erreur lors de la tentative de quitter le groupe: " . $e->getMessage();
    }
}