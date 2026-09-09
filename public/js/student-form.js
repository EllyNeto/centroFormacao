/* Student avatar image instant preview with 2MB size check */
function previewStudentImage(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        // Verificação do tamanho máximo do ficheiro (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('A fotografia selecionada é demasiado grande! O tamanho máximo permitido é de 2MB.');
            input.value = '';
            return;
        }
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
