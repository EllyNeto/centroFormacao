/**
 * Turma Form Handlers & Select2 Initialization
 * Ficheiro externo de JavaScript para manipulação do formulário de Turmas.
 * Garante que nenhuma lógica de script fique acoplada aos ficheiros Blade.
 */
$(document).ready(function() {
    // 1. Inicialização do Select2 nos campos de seleção de turma
    if ($.fn.select2) {
        $('.select2, .select2-select').select2({
            placeholder: "Selecione uma opção",
            allowClear: true,
            width: '100%'
        });
    }

    // 2. Atualização automática da capacidade máxima com base na sala física selecionada
    var classroomSelect = document.getElementById('classroom_id');
    var maxCapacityInput = document.getElementById('max_capacity');

    function updateCapacity() {
        if (!classroomSelect || !maxCapacityInput) return;
        var selectedOption = classroomSelect.options[classroomSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset && selectedOption.dataset.capacity) {
            maxCapacityInput.value = selectedOption.dataset.capacity;
        } else if (!classroomSelect.value) {
            maxCapacityInput.value = '';
        }
    }

    if (classroomSelect && maxCapacityInput) {
        // Escuta evento nativo e evento disparado pelo Select2 / jQuery
        classroomSelect.addEventListener('change', updateCapacity);
        $(classroomSelect).on('change', updateCapacity);
        
        // Se a capacidade estiver vazia ao carregar, preenche com base na seleção inicial
        if (!maxCapacityInput.value) {
            updateCapacity();
        }
    }
});
