/**
 * public/js/main.js
 * Global JavaScript file for the Supermarket POS system.
 * Contains common utility functions and event listeners that are shared
 * across various parts of the application, especially public-facing pages
 * or general components not specific to admin/seller dashboards.
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('main.js loaded and DOM fully parsed.');

    // --- Example Global Utility Functions ---

    /**
     * Shows a custom alert message to the user.
     * Replace native alert() with a more styled, non-blocking message.
     * In a real application, this would render a Bootstrap modal or a toast notification.
     * @param {string} message The message to display.
     * @param {string} type The type of message (e.g., 'success', 'error', 'info', 'warning').
     */
    function showCustomAlert(message, type = 'info') {
        // For demonstration, using a simple console log and temporary DOM manipulation.
        // In a real app, integrate with Bootstrap modals/toasts.
        console.log(`Alert (${type}): ${message}`);

        const alertContainer = document.getElementById('global-alert-container');
        if (!alertContainer) {
            // Create a container if it doesn't exist
            const body = document.querySelector('body');
            const newContainer = document.createElement('div');
            newContainer.id = 'global-alert-container';
            newContainer.style.position = 'fixed';
            newContainer.style.top = '20px';
            newContainer.style.left = '50%';
            newContainer.style.transform = 'translateX(-50%)';
            newContainer.style.zIndex = '9999';
            newContainer.style.width = '80%';
            newContainer.style.maxWidth = '400px';
            body.appendChild(newContainer);
            showCustomAlert(message, type); // Call itself again with the new container
            return;
        }

        const alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', 'alert-dismissible', 'fade', 'show', 'text-center');
        alertDiv.setAttribute('role', 'alert');

        switch (type) {
            case 'success':
                alertDiv.classList.add('alert-success');
                break;
            case 'error':
                alertDiv.classList.add('alert-danger');
                break;
            case 'warning':
                alertDiv.classList.add('alert-warning');
                break;
            case 'info':
            default:
                alertDiv.classList.add('alert-info');
                break;
        }

        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        alertContainer.appendChild(alertDiv);

        // Automatically fade out after 5 seconds
        setTimeout(() => {
            if (alertDiv) {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }
        }, 5000);
    }

    // Expose `showCustomAlert` globally for easy access from other scripts if needed
    window.showCustomAlert = showCustomAlert;


    /**
     * Simple client-side form validation example.
     * Prevents form submission if required fields are empty.
     * This is a basic example and would be expanded for specific form needs.
     */
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('[required]');

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                event.preventDefault(); // Stop form submission
                showCustomAlert('Please fill in all required fields.', 'error');
            }
        });

        // Remove validation feedback when user starts typing
        form.querySelectorAll('[required]').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    });

    // --- End Example Global Utility Functions ---

});
