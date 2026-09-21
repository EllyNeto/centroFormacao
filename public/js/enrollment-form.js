/**
 * Script responsável pelas interações dinâmicas nos formulários de inscrição (Enrollment).
 * Inclui validação e pré-visualização instantânea da fotografia de perfil do candidato.
 */

/**
 * Realiza a pré-visualização da imagem selecionada pelo utilizador no formulário.
 * Valida se o ficheiro não excede o limite máximo permitido de 2MB antes de carregar a imagem na interface.
 *
 * @param {HTMLInputElement} input Campo do tipo file selecionado pelo utilizador
 */
function previewStudentImage(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        // Verificação do tamanho máximo do ficheiro (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('A fotografia selecionada é demasiado grande! O tamanho máximo permitido é de 2MB.');
            input.value = ''; // Limpa a seleção caso o ficheiro seja inválido
            return;
        }

        // Lê o ficheiro de imagem em memória e atribui como background ao elemento de preview
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('imagePreview');
            if (preview) {
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
            }
        }
        reader.readAsDataURL(file);
    }
}

/**
 * Event Listener executado assim que o DOM estiver totalmente carregado.
 * Mantém ouvintes de eventos para desambiguação de IDs e campos dinâmicos caso existam datalists na página.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Gestão dinâmica de seleção de estudante via datalist se presente
    const studentSearchInput = document.getElementById('student_search');
    const studentHiddenInput = document.getElementById('student_id');
    const studentsDatalist   = document.getElementById('students_list');

    if (studentSearchInput && studentHiddenInput && studentsDatalist) {
        studentSearchInput.addEventListener('input', function() {
            const val = this.value;
            const options = studentsDatalist.options;
            studentHiddenInput.value = '';
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    studentHiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });
    }

    // Gestão dinâmica de seleção de curso via datalist se presente
    const courseSearchInput = document.getElementById('course_search');
    const courseHiddenInput = document.getElementById('course_id');
    const coursesDatalist   = document.getElementById('courses_list');

    if (courseSearchInput && courseHiddenInput && coursesDatalist) {
        courseSearchInput.addEventListener('input', function() {
            const val = this.value;
            const options = coursesDatalist.options;
            courseHiddenInput.value = '';
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    courseHiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });
    }
});
