/**
 * Teacher Form Image Preview Handler
 */
function previewTeacherImage(event) {
	const file = event.target.files[0];
	const fileNameDisplay = document.getElementById('fileNameDisplay');
	const output = document.getElementById('imagePreview');
	const defaultSvg = document.getElementById('defaultAvatarSvg');

	if (file) {
		if (fileNameDisplay) {
			fileNameDisplay.textContent = file.name;
		}
		const reader = new FileReader();
		reader.onload = function() {
			if (output) {
				output.src = reader.result;
				output.style.display = 'block';
			}
			if (defaultSvg) {
				defaultSvg.style.display = 'none';
			}
		};
		reader.readAsDataURL(file);
	} else {
		if (fileNameDisplay) {
			fileNameDisplay.textContent = 'Nenhum ficheiro selecionado';
		}
		if (output && !output.getAttribute('src')) {
			output.style.display = 'none';
			if (defaultSvg) {
				defaultSvg.style.display = 'block';
			}
		}
	}
}
