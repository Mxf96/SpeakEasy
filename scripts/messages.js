document.getElementById('messageFile').addEventListener('change', function(event) {
    var file = event.target.files[0];
    var output = document.getElementById('filePreview');

    // Clear previous content
    output.innerHTML = '';

    if(file) {
        // Display the .file-preview div
        output.style.display = 'block';

        if(file.type.match('image.*')) {
            // For images, read and display the preview
            var reader = new FileReader();
            reader.onload = function(e) {
                output.innerHTML = '<img src="' + e.target.result + '" alt="File preview" style="max-height: 100px;">';
            };
            reader.readAsDataURL(file);
        } else {
            // For non-image files, display the file name or an icon
            output.innerHTML = 'File: ' + file.name;
        }
    } else {
        // Hide the .file-preview div if no file is selected
        output.style.display = 'none';
    }
});


document.addEventListener("DOMContentLoaded", function() {
    var messageDisplayContainer = document.getElementById('messageDisplayContainer');
    if (messageDisplayContainer) {
        messageDisplayContainer.scrollTop = messageDisplayContainer.scrollHeight;
    }
});
