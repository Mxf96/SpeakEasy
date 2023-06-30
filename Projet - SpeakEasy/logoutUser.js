// Fichier logout.js

// Fonction de déconnexion
function logoutUser() {
    // Effectuer les opérations de déconnexion ici
    // Par exemple, envoyer une requête au serveur pour enregistrer la déconnexion
}

// Événement beforeunload
window.addEventListener('beforeunload', function(event) {
    // Appeler la fonction de déconnexion
    logoutUser();

    // Message affiché à l'utilisateur (optionnel)
    // event.returnValue = 'Êtes-vous sûr de vouloir quitter cette page ?';l
});