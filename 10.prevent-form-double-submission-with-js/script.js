document.addEventListener('DOMContentLoaded', function() {
    // Get all forms on the page
    const forms = document.querySelectorAll('form');

    forms.forEach(function(form) {
        // Add a submit event listener to each form
        form.addEventListener('submit', function(event) {
            // Get all submit buttons within the form
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            
            // Disable each submit button
            submitButtons.forEach(function(button) {
                button.disabled = true;
            });
        });
    });
});

// shorter version
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", () => {
            form.querySelectorAll('[type="submit"]').forEach(
                (button) => (button.disabled = true)
            );
        });
    });
});
