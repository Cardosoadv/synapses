/**
 * Usuarios Module
 */
App.Usuarios = {
    init: function() {
        // User specific JS
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // Check if on users page
    if (window.location.pathname.includes('/usuarios')) {
        App.Usuarios.init();
    }
});
