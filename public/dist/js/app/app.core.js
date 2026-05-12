/**
 * Core Application Object
 */
window.App = {
    init: function() {
        // Global initializations
        this.bindEvents();
    },

    bindEvents: function() {
        // Universal event bindings
        document.querySelectorAll('[data-confirm]').forEach(element => {
            element.addEventListener('submit', (e) => {
                const message = element.getAttribute('data-confirm') || 'Tem certeza?';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        // Focus management for non-semantic buttons
        document.querySelectorAll('[role="button"][tabindex="0"]').forEach(element => {
            element.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    element.click();
                }
            });
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    App.init();
});
