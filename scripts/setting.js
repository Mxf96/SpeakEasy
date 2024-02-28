document.addEventListener('DOMContentLoaded', function () {
    var settingsIcon = document.getElementById('settingsIcon');
    if (settingsIcon) {
        settingsIcon.addEventListener('click', function() {
            document.getElementById('settingsForm').style.display = 'block';
        });
    }
});
