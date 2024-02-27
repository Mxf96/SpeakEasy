document.getElementById('addFriendButton').addEventListener('click', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var userID = urlParams.get('userID'); // Supposons que l'ID est passé dans l'URL
    var friendName = 'NomAmi'; // Vous devrez obtenir ce nom d'une manière ou d'une autre, peut-être via un champ de saisie ou votre logique

    // Envoyer une requête AJAX au serveur pour ajouter l'ami
    fetch('/frinds/addFriendProfile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `userID=${userID}&friendName=${encodeURIComponent(friendName)}`,
    })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(error => console.error('Erreur:', error));
});
