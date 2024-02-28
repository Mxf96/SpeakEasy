document.addEventListener('DOMContentLoaded', function() {
    const addFriendButton = document.getElementById('addFriendButton');
    if (addFriendButton) {
        addFriendButton.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du bouton, si nécessaire
            const userID = this.getAttribute('data-userid');
            fetch('../friends/addFriend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'userID=' + userID
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Afficher le message de retour
                window.location.reload(); // Recharger la page
            })
            .catch(error => console.error('Erreur:', error));
        });
    }
});