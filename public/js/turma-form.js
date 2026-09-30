/**
 * Turma Form Handlers & Select2 Initialization
 */
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2, .select2-select').select2({
            placeholder: "Selecione uma opção",
            allowClear: true,
            width: '100%'
        });
    }
});
