/**
 * Processos Module
 */
App.Processos = {
    init: function() {
        this.bindEvents();
    },

    bindEvents: function() {
        const statusSelect = document.querySelector('.status-select');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.status-select')) {
        App.Processos.init();
    }
});
