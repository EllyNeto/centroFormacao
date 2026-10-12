/**
 * Script de Manipulação e Interatividade para Inscrições / Candidaturas
 * Módulo 5: Inscrições
 * Desacopla 100% de JavaScript dos ficheiros Blade do módulo de inscrições.
 */
document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------------------
    // 1. Pré-visualização da Fotografia do Candidato
    // -------------------------------------------------------------------------
    var imageInput = document.getElementById('candidate_image_input') || document.getElementById('image');
    var preview = document.getElementById('candidateImagePreview') || document.getElementById('imagePreview');
    var defaultSvg = document.getElementById('defaultAvatarSvg');
    var fileNameDisplay = document.getElementById('fileNameDisplay');

    if (imageInput) {
        imageInput.addEventListener('change', function (e) {
            var file = e.target.files && e.target.files[0];
            if (file) {
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = file.name;
                }
                var reader = new FileReader();
                reader.onload = function (event) {
                    if (preview) {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        preview.classList.remove('d-none');
                    }
                    if (defaultSvg) {
                        defaultSvg.style.display = 'none';
                        defaultSvg.classList.add('d-none');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // -------------------------------------------------------------------------
    // 2. Filtragem Dinâmica de Turmas de acordo com o Curso Selecionado
    // -------------------------------------------------------------------------
    var courseSelect = document.getElementById('course_id');
    var roomSelect = document.getElementById('room_id');

    function filterRoomsByCourse() {
        if (!courseSelect || !roomSelect) return;
        var selectedCourseId = courseSelect.value;
        var roomOptions = roomSelect.querySelectorAll('option');

        roomOptions.forEach(function (option) {
            var optionCourseId = option.getAttribute('data-course-id');
            // Se for opção vazia (ex: "Sem turma atribuída / Lista de Espera") mantém sempre visível
            if (!optionCourseId) {
                option.style.display = '';
                option.disabled = false;
            } else if (!selectedCourseId || optionCourseId === selectedCourseId) {
                option.style.display = '';
                option.disabled = false;
            } else {
                option.style.display = 'none';
                option.disabled = true;
                if (option.selected) {
                    roomSelect.value = '';
                }
            }
        });

        // Se o Select2 estiver ativo, dispara o evento change para re-renderizar
        if (window.jQuery && $(roomSelect).data('select2')) {
            $(roomSelect).trigger('change.select2');
        }
    }

    if (courseSelect && roomSelect) {
        courseSelect.addEventListener('change', filterRoomsByCourse);
        if (window.jQuery) {
            $(courseSelect).on('change', filterRoomsByCourse);
        }
        // Executa uma primeira filtragem se já houver um curso selecionado
        if (courseSelect.value) {
            filterRoomsByCourse();
        }
    }
});
