document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('confirmModal');
    var deleteLinks = document.querySelectorAll('.delete-project');
    var confirmButton = document.getElementById('confirmDelete');
    var cancelButton = document.getElementById('cancelDelete');
    var projectIdToDelete;
    deleteLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            projectIdToDelete = this.getAttribute('data-id');
            modal.style.display = 'block';
        });
    });
    confirmButton.addEventListener('click', function() {
        window.location.href = 'deleteproject.php?id=' + projectIdToDelete;
    });
    cancelButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});
