document.getElementById('addFrienda').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('addFriendForm').style.display = 'block';
    document.getElementById('invitationsContainer').style.display = 'none';
});

document.getElementById('showInvitationsButton').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('invitationsContainer').style.display = 'block';
    document.getElementById('addFriendForm').style.display = 'none'; 
});