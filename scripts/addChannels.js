document.getElementById('addChannelButton').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('addChannelForm').style.display = 'block';
});

function showUpdateForm(channelID, channelName, channelDescription) {
    // Remplir le formulaire avec les valeurs actuelles
    document.getElementById('updateChannelID').value = channelID;
    document.getElementById('newChannelName').value = channelName;
    document.getElementById('newChannelDescription').value = channelDescription || ''; // Utilisez une chaîne vide si la description est null
    
    // Afficher le formulaire
    document.getElementById('updateChannelForm').style.display = 'block';
}