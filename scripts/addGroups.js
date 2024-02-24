document.getElementById('createGroupBtn').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('createGroupForm').style.display = 'block';
    document.getElementById('joinGroupForm').style.display = 'none';
});

document.getElementById('joinGroupBtn').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('joinGroupForm').style.display = 'block';
    document.getElementById('createGroupForm').style.display = 'none';
});
