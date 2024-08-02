document.addEventListener('DOMContentLoaded', function() {
    var messageContainer = document.getElementById('message-container');
    if (messageContainer) {
        setTimeout(function() {
            messageContainer.style.transition = 'opacity 1s';
            messageContainer.style.opacity = '0';
            setTimeout(function() {
                messageContainer.remove();
            }, 1000);
        }, 4000);
    }
});